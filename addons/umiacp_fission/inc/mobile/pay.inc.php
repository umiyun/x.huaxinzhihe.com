<?php
/**
 * Created by IntelliJ IDEA.
 * User: 7³
 * Date: 2018/7/30 0030
 * Time: 12:18
 */

global $_W, $_GPC;

$uniacid = intval($this->uniacid);
$openid = trim($this->openid);

if (!$this->mid || !$this->openid) {
    youmi_result(1, '请先登录');
}

$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'pay';

/**
 *  执行支付.
 * do   pay
 * order_id     订单
 */
if ($op == 'pay') {
    $order_id = intval($_GPC['order_id']);
    // 判断权限
    $order = $this->hasOrder($order_id);

    $activity = pdo_fetch("select * from " . tablename(YOUMI_NAME . '_' . 'activity') . " where `uniacid` = {$uniacid} and id = {$order['activity_id']} ");

    if ($activity['status'] == 2) {
        youmi_result(1, '活动已下架');
    }
    if ($activity['status'] == 3) {
        youmi_result(1, '活动未开始');
    }

    if ($order['price'] <= 0) {
        youmi_result(1, '金额错误');
    }

    if ($order && $order['status'] == 1) {
        //构造支付请求中的参数
        $params = array(
            'tid' => $order['tid'],             //模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
            'ordersn' => $order['ordersn'],     //收银台中显示的订单号
            'title' => $order['title'],         //收银台中显示的标题
            'fee' => $order['price'],           //收银台中显示需要支付的金额,只能大于 0
            'user' => $order['mid'],            //付款用户, 付款的用户名(选填项)
        );

        global $_W;
        load()->model('activity');
        load()->model('module');
        activity_coupon_type_init();
        if (!$this->inMobile) {
            youmi_result(1, '支付功能只能在手机上使用');
        }

        $params['module'] = $this->module['name'];

        $return = pay($order);
        if (is_error($return)) {
            youmi_result(1, $return['message'], $return);
        }
        if ($return['return_code'] == 'FAIL') {
            youmi_result(1, $return['return_msg']);
        }
        youmi_result(0, '', $return);
    } else {
        if ($order['status'] == 4) {
            youmi_result(1, '订单已取消');
        }
        if ($order['status'] == 2) {
            youmi_result(1, '订单已支付');
        }
        youmi_result(1, '订单不存在或已支付');
    }
}

function pay($order)
{
    global $_W, $_GPC;
    load()->model('account');
    $paytype = !empty($order['paytype']) ? $order['paytype'] : 'wechat';
    $moduels = uni_modules();
    if (empty($order) || !array_key_exists(YOUMI_NAME, $moduels)) {
        return error(1, '模块不存在');
    }

    $uniontid = $order['tid'];
    $paylog = pdo_get('core_paylog', array('uniacid' => $_W['uniacid'], 'module' => YOUMI_NAME, 'tid' => $order['tid']));
    if (empty($paylog)) {
        $paylog = array(
            'uniacid' => $_W['uniacid'],
            'acid' => $_W['acid'],
            'type' => 'wechat',
            'openid' => $_W['openid'],
            'module' => YOUMI_NAME,
            'tid' => $order['tid'],
            'uniontid' => $uniontid,
            'fee' => floatval($order['price']),
            'card_fee' => floatval($order['price']),
            'status' => '0',
            'is_usecard' => '0',
            'tag' => iserializer(array('acid' => $_W['acid'], 'uid' => $_W['member']['uid']))
        );
        pdo_insert('core_paylog', $paylog);
        $paylog['plid'] = pdo_insertid();
    }
    if (!empty($paylog) && $paylog['status'] != '0') {
        return error(1, '这个订单已经支付成功, 不需要重复支付.');
    }
    if (!empty($paylog) && empty($paylog['uniontid'])) {
        pdo_update('core_paylog', array(
            'uniontid' => $uniontid,
        ), array('plid' => $paylog['plid']));
        $paylog['uniontid'] = $uniontid;
    }

    return wxapp_pay($order, $paylog['openid']);
}

/**
 *  小程序支付回调
 * do   pay     op      payResult
 * order_id     订单
 */
if ($op == 'payResult') {
    //订单id
    $order_id = intval($_GPC['order_id']);
// 判断权限
    $order = $this->hasOrder($order_id);
    if ($order && $order['status'] == 1) {
        //订单
        $paylog = pdo_get('core_paylog', array('uniacid' => $this->uniacid, 'module' => YOUMI_NAME, 'tid' => $order['tid']));
        if ($paylog['status'] == 0) {
            $setting = youmi_setting_get_list();

            $appid = trim($setting["wxapp_appid"]);
            $mch_id = trim($setting["wxapp_mchid"]);
            $key = trim($setting["wxapp_signkey"]);

            $weixinpay = new WeixinPay($appid, $openid, $mch_id, $key, $order['tid'], $order['title'], $order['price']);
            $return = $weixinpay->orderquery();
            if ($return['return_code'] == 'SUCCESS' && $return['result_code'] == 'SUCCESS') {
                if ($return['trade_state'] == 'SUCCESS' && floatval($return['total_fee']) / 100 == $order['price']) {
                    $paylog['status'] = 1;
                    $tag = unserialize($paylog['tag']);
                    $tag['transaction_id'] = $return['transaction_id'];
                    $paylog['tag'] = serialize($tag);
                    pdo_update('core_paylog', ['status' => 1, 'tag' => $paylog['tag']], ['plid' => $paylog['plid']]);
                }
            }
        }
        $tag = unserialize($paylog['tag']);
        $transaction_id = $tag['transaction_id'];
        $status = intval($paylog['status']) === 1;
        if ($status) {
            $res = pdo_update(YOUMI_NAME . '_' . 'order', ['status' => 2, 'pay_time' => TIMESTAMP, 'transid' => $transaction_id], ['id' => $order['id']]);
            $errno = $res ? 0 : 1;
            youmi_result($errno, '支付' . ($res ? '成功' : '失败'), ['member_info' => $this->member['mobile'] ? 0 : 1]);
        }
    } else {
        youmi_result(1, '订单不存在或已支付');
    }
}


function wxapp_pay($order, $openid)
{
    global $_W, $_GPC;
    load()->model('account');
    $setting = uni_setting($_W['uniacid'], array('payment'));
    if (is_array($setting['payment'])) {
        $wechat = $setting['payment']['wechat'];
        if (intval($wechat['switch']) == 3) {
            $facilitator_setting = uni_setting($wechat['service'], array('payment'));
            $wechat['signkey'] = $facilitator_setting['payment']['wechat_facilitator']['signkey'];
        } else {
            $wechat['signkey'] = ($wechat['version'] == 1) ? $wechat['key'] : $wechat['signkey'];
        }
    }

    $appid = trim($_W['uniaccount']["key"]);
    $mch_id = trim($wechat["mchid"]);
    $key = trim($wechat["signkey"]);

    $weixinpay = new WeixinPay($appid, $openid, $mch_id, $key, $order['tid'], $order['title'], $order['price']);
    $return = $weixinpay->pay();
    return $return;
}

