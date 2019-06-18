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
        $activity = pdo_get(YOUMI_NAME . '_' . 'activity', ['uniacid' => $uniacid, 'id' => $group['activity_id']]);
        $commission = floatval($activity['commission']);

        $orders = pdo_getall(YOUMI_NAME . '_' . 'order', ['group_id' => $group['id'], 'send_status !=' => 1, 'status' => 2, 'fmid !=' => 0]);

        foreach ($orders as &$order) {
            if (floatval($order['price']) <= $commission) {
                $count++;
                continue;
            }
            $f_member = pdo_get(YOUMI_NAME . '_member', array('mid' => $order['fmid'], 'uniacid' => $order['uniacid']), ['openid', 'mid']);
            $result = youmi_finance($f_member['openid'], 'DK' . $order['tid'], $commission, '佣金');

            if (($result['return_code'] == 'SUCCESS') && ($result['result_code'] == 'SUCCESS')) {
                $success++;
                pdo_update(YOUMI_NAME . '_' . 'order', ['send_status' => 1, 'commission' => $commission, 'send_result' => '发放成功'], ['id' => $order['id']]);
            } else {
                $result['msg'] = (empty($result['err_code_des']) ? $result['return_msg'] : $result['err_code_des']);
                pdo_update(YOUMI_NAME . '_' . 'order', ['send_status' => 2, 'commission' => $commission, 'send_result' => $result['msg']], ['id' => $order['id']]);
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
die(json_encode($result));
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
