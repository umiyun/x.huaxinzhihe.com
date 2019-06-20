<?php
/**
 * Created by IntelliJ IDEA.
 * User: 7³
 * Date: 2018/7/30 0030
 * Time: 12:18
 */

global $_W, $_GPC;

//error_reporting(E_ALL);
//ini_set('display_errors', 'On');

$uniacid = intval($this->uniacid);

$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'display';

$setting = youmi_setting_get_list();

if ($op == 'display') {
    youmi_internal_log('tasklog',time());
    send_com($uniacid);
    send_msg($uniacid);
    exit();
}


if ($op == 'update_group') {

    $paras[':uniacid'] = $this->uniacid;
    $groups = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'group') . ' where uniacid = :uniacid and activity_id = 186  ', $paras);
    foreach ($groups as &$group) {
        $now_num = pdo_fetchcolumn('select count(id) from ' . tablename(YOUMI_NAME . '_' . 'order') . ' where uniacid = ' . $group['uniacid'] . ' and group_id = ' . $group['id'] . ' and status = 2');

        if ($now_num >= 3 && $group['status'] == 3) {
            $res += pdo_update(YOUMI_NAME . '_' . 'group', ['status' => 1, 'now_num' => 3, 'success_time' => time()], ['id' => $group['id']]);
        } elseif ($group['status'] == 1 && $now_num >= 3) {
            $res += pdo_update(YOUMI_NAME . '_' . 'group', ['now_num' => 3], ['id' => $group['id']]);
        } else if ($now_num < 3) {
            $res += pdo_update(YOUMI_NAME . '_' . 'group', ['now_num' => $now_num], ['id' => $group['id']]);
        }

        unset($group);
    }
    youmi_result($res);

}

/**
 * 实时更新订单
 * do : task   op : reset_order
 */
if ($op == 'reset_order') {
    $referrer = trim($_GPC['referrer']);
//    if (!$referrer) {
//        youmi_result(1, '非法访问');
//    }

    $paras[':uniacid'] = $this->uniacid;
    $new = TIMESTAMP;


    $orders = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' where uniacid = :uniacid and activity_id = 186 and query_lock = 0 limit 20 ', $paras);
    $res = 0;

//    youmi_result(1, '', $orders);
    if ($orders) {

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

        foreach ($orders as $order) {

//            $order['tid'] = '2019061909195300000018000616';
//            $order['title'] = '德州学房英语学院618双抢嗨';
//            $order['price'] = '99';

            $weixinpay = new WeixinPay($appid, $openid, $mch_id, $key, $order['tid'], $order['title'], $order['price']);
            $return = $weixinpay->orderquery();

//            youmi_result(1, $return);

            if ($return['return_code'] == 'SUCCESS' && $return['result_code'] == 'SUCCESS') {
                if ($return['trade_state'] == 'SUCCESS' && floatval($return['total_fee']) / 100 == $order['price']) {
                    $update['status'] = 2;
                    $update['pay_time'] = TIMESTAMP;
                    $update['transid'] = $return['transaction_id'];
                    if (!$order['group_id'] && $order['leader'] == 1) {
                        pdo_insert(YOUMI_NAME . '_' . 'group', [
                            'uniacid' => $order['uniacid'],
                            'activity_id' => $order['activity_id'],
                            'shop_id' => $order['shop_id'],
                            'mid' => $order['mid'],
                            'createtime' => $order['createtime'],
                            'status' => 3,
                            'group_id' => $return['transaction_id'],
                            'group_num' => 3,
                            'now_num' => 1,
                            'avatar' => $order['avatar'],
                            'nickname' => $order['nickname'],
                        ]);
                        $update['group_id'] = pdo_insertid();
                    }
                }
            }
            if ($return['trade_state'] == 'REFUND') {
                $update['status'] = 5;
                $update['transid'] = $return['transaction_id'];
            }

            if ($return['return_code'] == 'SUCCESS' && $return['result_code'] != 'SUCCESS') {

            }
            $update['query_lock'] = 1;
            $res += pdo_update(YOUMI_NAME . '_' . 'order', $update, ['id' => $order['id']]);
            unset($order);
            unset($return);
        }
    }
    youmi_result(0, '成功处理' . $res . '条订单！');

}


/**
 * 实时发送佣金
 * do : task   op : display
 */
function send_com($uniacid)
{

    $groups = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_group') . ' where uniacid = :uniacid and status = 1 and com_status = :com_status', [':uniacid' => $uniacid, ':com_status' => 0]);

    $success = 0;
    $count = 0;
    $error = 0;
    foreach ($groups as &$group) {

        $orders = pdo_getall(YOUMI_NAME . '_' . 'order', ['group_id' => $group['id'], 'send_status !=' => 1, 'status' => 2, 'fmid !=' => 0]);

        foreach ($orders as &$order) {
            $commission = floatval($order['commission']);
            if (floatval($order['price']) <= $commission) {
                $count++;
                continue;
            }
            $f_member = pdo_get(YOUMI_NAME . '_member', array('mid' => $order['fmid'], 'uniacid' => $order['uniacid']), ['openid', 'mid']);
            $result = youmi_finance($f_member['openid'], 'DK' . $order['tid'], $commission, '佣金');

            if (($result['return_code'] == 'SUCCESS') && ($result['result_code'] == 'SUCCESS')) {
                $success++;
                pdo_update(YOUMI_NAME . '_' . 'order', ['send_status' => 1, 'send_result' => '发放成功'], ['id' => $order['id']]);
            } else {
                $result['msg'] = (empty($result['err_code_des']) ? $result['return_msg'] : $result['err_code_des']);
                pdo_update(YOUMI_NAME . '_' . 'order', ['send_status' => 2, 'send_result' => $result['msg']], ['id' => $order['id']]);
                $error++;
                break 2;
            }

            unset($order);
        }

        pdo_update(YOUMI_NAME . '_' . 'group', ['com_status' => 1], ['id' => $group['id']]);

        unset($group);
    }
    echo '成功' . $success . ',失败' . $error . ',跳过' . $count . '<br/>';

}

/**
 * 实时发送模版消息
 * do : task   op : display
 */
function send_msg($uniacid)
{

    global $_W;
    $groups = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_group') . ' where uniacid = :uniacid and status = 1 and msg_status = :msg_status', [':uniacid' => $uniacid, ':msg_status' => 0]);

    $success = 0;
    $error = 0;
    foreach ($groups as &$group) {
        $orders = pdo_getall(YOUMI_NAME . '_' . 'order', ['group_id' => $group['id'], 'msg_status !=' => 1, 'status' => 2]);
        foreach ($orders as &$order) {
            $f_member = pdo_get(YOUMI_NAME . '_member', array('mid' => $order['mid'], 'uniacid' => $order['uniacid']), ['openid', 'mid']);

            //发送拼团通知
            $url = $_W['siteroot'] . "app/index.php?i={$uniacid}&c=entry&activity_id={$group['activity_id']}&op=detail&id={$group['id']}&do=index&m=umiacp_groupsimple";
            $result = handleGroupMsg($f_member['openid'], $order['title'], $order['price'], $group['now_num'], $url);

            if (!is_error($result)) {
                $success++;
                pdo_update(YOUMI_NAME . '_' . 'order', ['msg_status' => 1, 'msg_result' => '发放成功'], ['id' => $order['id']]);
            } else {
                $result['msg'] = (empty($result['err_code_des']) ? $result['return_msg'] : $result['err_code_des']);
                pdo_update(YOUMI_NAME . '_' . 'order', ['msg_status' => 2, 'msg_result' => $result['msg']], ['id' => $order['id']]);
                $error++;
                break 2;
            }

            unset($order);
        }

        pdo_update(YOUMI_NAME . '_' . 'group', ['msg_status' => 1], ['id' => $group['id']]);

        unset($group);
    }
    echo '成功' . $success . ',失败' . $error . '<br/>';

}

function youmi_finance($openid = '', $tid, $money, $desc = '')
{


    $desc = empty($desc) ? '商家提现' : $desc;
    $setting = youmi_setting_get_list();

    $pay = new WeixinPay($setting['wxapp_appid'], $openid, $setting['wxapp_mchid'], $setting['wxapp_signkey'], $tid, $desc, $money);

    if (empty($openid)) return error(-1, 'openid不能为空');

    $pars = array();
    $pars['mch_appid'] = $setting['wxapp_appid'];
    $pars['mchid'] = $setting['wxapp_mchid'];
    $pars['partner_trade_no'] = $tid;
    $pars['openid'] = $openid;
    $pars['check_name'] = 'NO_CHECK';
    $pars['amount'] = floatval($money) * 100;
    $pars['desc'] = $desc;
    $pars['spbill_create_ip'] = gethostbyname($_SERVER["HTTP_HOST"]);
    if (empty($pars['mch_appid']) || empty($pars['mchid'])) {
        $rearr['err_code'] = '请先在系统设置-小程序参数设置内设置微信商户号和秘钥';
        return $rearr;
    }

    $rearr = $pay->finance($pars);

    return $rearr;

}

function handleGroupMsg($openid, $k2, $k3, $k4, $url)
{

    $args = [
        'keyword1' => '参团成功',
        'keyword2' => $k2,
        'keyword3' => $k3,
        'keyword4' => $k4,
        'keyword5' => date('Y-m-d H:i:s'),
    ];
    return sendGroupMsg($openid, $args, $url);
}

function sendGroupMsg($openid, $args, $url)
{

    $setting = youmi_setting_get_list();
    $data = array(
        'first' => array(
            'value' => $setting['cut_first'],
            'color' => '#ff510'
        ),
        'keyword1' => array(
            'value' => $args['keyword1'],
            'color' => '#ff510'
        ),
        'keyword2' => array(
            'value' => $args['keyword2'],
            'color' => '#ff510'
        ),
        'keyword3' => array(
            'value' => $args['keyword3'],
            'color' => '#ff510'
        ),
        'keyword4' => array(
            'value' => $args['keyword4'],
            'color' => '#ff510'
        ),
        'keyword5' => array(
            'value' => $args['keyword5'],
            'color' => '#ff510'
        ),
        'remark' => array(
            'value' => $setting['cut_remark'],
            'color' => '#ff510'
        ),
    );
die(json_encode($setting));
    $account_api = WeAccount::create();

    return $account_api->sendTplNotice($openid, $setting['cut_id'], $data, $url);
}
