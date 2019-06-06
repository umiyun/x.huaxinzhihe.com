<?php
/**
 * Created by PhpStorm.
 * User: umi
 * Date: 2019/6/3
 * Time: 10:59
 */
require_once YOUMI_COMMON_FUNCTIONS.'wxpay.php';

function _pay($order,$_W,$ref){

    if ($order && $order['status'] == 1) {
        //构造支付请求中的参数
        $params = array(
            'tid' => $order['tid'],             //模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
            'ordersn' => $order['ordersn'],     //收银台中显示的订单号
            'title' => $order['title'],         //收银台中显示的标题
            'fee' => $order['price'],           //收银台中显示需要支付的金额,只能大于 0
            'user' => $order['mid'],            //付款用户, 付款的用户名(选填项)
        );


        load()->model('activity');
        load()->model('module');
        activity_coupon_type_init();
        if (!$ref->inMobile) {
            youmi_result(1, '支付功能只能在手机上使用');
        }

        $params['module'] = $ref->module['name'];

        $return = _prePay($order,$_W);
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
function _prePay($order,$_W){

    load()->model('account');

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

    return _wxapp_pay($order, $paylog['openid'],$_W);
}

function _wxapp_pay($order, $openid,$_W)
{

    load()->model('account');
    $setting = uni_setting($_W['uniacid'], array('payment'));
    if(is_array($setting['payment'])) {
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

    $weixinpay = new WeixinPayCommon($appid, $openid, $mch_id, $key, $order['tid'], $order['title'], $order['price'],$_W);
    $return = $weixinpay->pay();
    return $return;
}
