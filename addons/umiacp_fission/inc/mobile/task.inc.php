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

//https://x.huaxinzhihe.com/app/index.php?i=3&c=entry&do=task&m=umiacp_fission&user_type=1

if ($op == 'display') {
    youmi_internal_log('tasklog', time());
    send_com($uniacid);
    exit();
}

/**
 * 实时发送佣金
 */
function send_com($uniacid)
{
    $success = 0;
    $count = 0;
    $error = 0;

    $orders = pdo_getall(YOUMI_NAME . '_' . 'order', ['uniacid' => $uniacid, 'send_status !=' => 1, 'status' => 2, 'fmid !=' => 0, 'commission >' => 0]);

    foreach ($orders as &$order) {
        $commission = floatval($order['commission']);
        if (floatval($order['price']) <= $commission) {
            $count++;
            pdo_update(YOUMI_NAME . '_' . 'order', ['send_status' => 3, 'send_result' => '金额错误跳过'], ['id' => $order['id']]);
            continue;
        }
        $f_member = pdo_get(YOUMI_NAME . '_member', array('mid' => $order['fmid'], 'uniacid' => $order['uniacid']), ['openid', 'mid']);
        $result = youmi_finance($f_member['openid'], 'DK' . $order['tid'], $commission, '分享赚佣金');

        if (($result['return_code'] == 'SUCCESS') && ($result['result_code'] == 'SUCCESS')) {
            $success++;
            pdo_update(YOUMI_NAME . '_' . 'order', ['send_status' => 1, 'send_result' => '发放成功'], ['id' => $order['id']]);
            pdo_update(YOUMI_NAME . '_' . 'cut', ['commision +=' => $commission], ['mid' => $order['fmid'], 'activity_id' => $order['activity_id']]);
            pdo_debug();
        } else {
            $result['msg'] = (empty($result['err_code_des']) ? $result['return_msg'] : $result['err_code_des']);
            pdo_update(YOUMI_NAME . '_' . 'order', ['send_status' => 2, 'send_result' => $result['msg']], ['id' => $order['id']]);
            $error++;
            break;
        }
        unset($order);
    }

    echo '发放佣金成功' . $success . ',失败' . $error . ',跳过' . $count . '<br/>';

}


function youmi_finance($openid = '', $tid, $money, $desc = '')
{
    global $_W;
    $desc = empty($desc) ? '商家提现' : $desc;

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

    $pay = new WeixinPay($appid, $openid, $mch_id, $key, $tid, $desc, $money);

    if (empty($openid)) return error(-1, 'openid不能为空');

    $pars = array();
    $pars['mch_appid'] = $appid;
    $pars['mchid'] = $mch_id;
    $pars['partner_trade_no'] = $tid;
    $pars['openid'] = $openid;
    $pars['check_name'] = 'NO_CHECK';
    $pars['amount'] = floatval($money) * 100;
    $pars['desc'] = $desc;
    $pars['spbill_create_ip'] = gethostbyname($_SERVER["HTTP_HOST"]);
    if (empty($pars['mch_appid']) || empty($pars['mchid'])) {
        $rearr['err_code_des'] = '请先在系统设置-小程序参数设置内设置微信商户号和秘钥';
        return $rearr;
    }

    $rearr = $pay->finance($pars);

    return $rearr;

}
