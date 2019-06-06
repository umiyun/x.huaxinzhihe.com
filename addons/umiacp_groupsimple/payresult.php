<?php
/**
 * Created by IntelliJ IDEA.
 * User: 7³
 * Date: 2019-03-14
 * Time: 16:11
 */

define('IN_MOBILE', true);
require '../../framework/bootstrap.inc.php';
require_once './core/defines.php';
require_once IA_ROOT . '/addons/' . YOUMI_NAME . '/core/functions/setting.php';
require_once IA_ROOT . '/addons/' . YOUMI_NAME . '/core/functions/wxpay.php';


function insert_log($data, $path = 'wxPayResult')
{
//TODO 创建日志
    $path = IA_ROOT . '/addons/' . YOUMI_NAME . '/data/log/' . $path . '/';
//首先判断目录存在否
    if (!is_dir($path)) {
        //第3个参数“true”意思是能创建多级目录，iconv防止中文目录乱码
        $res = mkdir(iconv('UTF-8', 'GBK', $path), 0777, true);
    }
    $date = date('Y-m-d');
    file_put_contents($path . $date . '.log', var_export(
            array(
                'args' => $data, //传入值
                'time' => date('Y-m-d H:i:s')
            ),
            true) . PHP_EOL, FILE_APPEND);
    return true;
}

$input = file_get_contents('php://input');
$isxml = true;
if (!empty($input) && empty($_GET['out_trade_no'])) {
    $obj = isimplexml_load_string($input, 'SimpleXMLElement', LIBXML_NOCDATA);
    $data = json_decode(json_encode($obj), true);
    if (empty($data)) {
        $result = array(
            'return_code' => 'FAIL',
            'return_msg' => ''
        );
        echo array2xml($result);
        exit;
    }
    if ($data['result_code'] != 'SUCCESS' || $data['return_code'] != 'SUCCESS') {
        $result = array(
            'return_code' => 'FAIL',
            'return_msg' => empty($data['return_msg']) ? $data['err_code_des'] : $data['return_msg']
        );
        echo array2xml($result);
        exit;
    }
    $get = $data;
} else {
    $isxml = false;
    $get = $_GET;
}

insert_log($get);

load()->web('common');
load()->classs('coupon');

$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `tid`=:tid';
$params = array();
$params[':tid'] = $get['out_trade_no'];
$log = pdo_fetch($sql, $params);

$_W['uniacid'] = $_W['weid'] = intval($log['uniacid']);
$_W['uniaccount'] = $_W['account'] = uni_fetch($_W['uniacid']);
$_W['acid'] = $_W['uniaccount']['acid'];
$setting = uni_setting($_W['uniacid'], array('payment'));
if ($get['trade_type'] == 'NATIVE') {
    $setting = setting_load('store_pay');
    $setting['payment']['wechat'] = $setting['store_pay']['wechat'];
}

if (is_array($setting['payment'])) {
    $wechat = $setting['payment']['wechat'];
    WeUtility::logging('pay', var_export($get, true));
    if (!empty($wechat)) {
        ksort($get);
        $string1 = '';
        foreach ($get as $k => $v) {
            if ($v != '' && $k != 'sign') {
                $string1 .= "{$k}={$v}&";
            }
        }

        if (intval($wechat['switch']) == 3) {
            $facilitator_setting = uni_setting($wechat['service'], array('payment'));
            $wechat['signkey'] = $facilitator_setting['payment']['wechat_facilitator']['signkey'];
        } else {
            $wechat['signkey'] = ($wechat['version'] == 1) ? $wechat['key'] : $wechat['signkey'];
        }
        $sign = strtoupper(md5($string1 . "key={$wechat['signkey']}"));

        if ($sign == $get['sign']) {

            if (intval($wechat['switch']) == PAYMENT_WECHAT_TYPE_SERVICE) {
                $get['openid'] = $log['openid'];
            }
            if (!empty($log) && $log['status'] == '0' && (($get['total_fee'] / 100) == $log['card_fee'])) {
                $log['tag'] = iunserializer($log['tag']);
                $log['tag']['transaction_id'] = $get['transaction_id'];
                $log['uid'] = $log['tag']['uid'];
                $record = array();
                $record['status'] = '1';
                $record['tag'] = iserializer($log['tag']);
                pdo_update('core_paylog', $record, array('plid' => $log['plid']));
                $mix_pay_credit_log = pdo_get('core_paylog', array('module' => $log['module'], 'tid' => $log['tid'], 'uniacid' => $log['uniacid'], 'type' => 'credit'));
                if (!empty($mix_pay_credit_log)) {
                    pdo_update('core_paylog', array('status' => 1), array('plid' => $mix_pay_credit_log['plid']));
                    $log['fee'] = $mix_pay_credit_log['fee'] + $log['fee'];
                    $log['card_fee'] = $mix_pay_credit_log['fee'] + $log['card_fee'];
                    $setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
                    $credtis = mc_credit_fetch($log['uid']);
                    mc_credit_update($log['uid'], $setting['creditbehaviors']['currency'], -$mix_pay_credit_log['fee'], array($log['uid'], '消费' . $setting['creditbehaviors']['currency'] . ':' . $fee));
                }
                if ($log['is_usecard'] == 1 && !empty($log['encrypt_code'])) {
                    $coupon_info = pdo_get('coupon', array('id' => $log['card_id']), array('id'));
                    $coupon_record = pdo_get('coupon_record', array('code' => $log['encrypt_code'], 'status' => '1'));
                    load()->model('activity');
                    $status = activity_coupon_use($coupon_info['id'], $coupon_record['id'], $log['module']);
                }

                $order = pdo_get(YOUMI_NAME . '_' . 'order', ['uniacid' => $log['uniacid'], 'tid' => $log['tid']]);
                $activity = pdo_get(YOUMI_NAME . '_' . 'activity', ['uniacid' => $log['uniacid'], 'id' => $order['activity_id']]);

                if ($order['status'] == 1) {
                    if ($activity['gnum'] > $activity['success']) {
                        pdo_update(YOUMI_NAME . '_' . 'order', ['status' => 2, 'pay_time' => TIMESTAMP, 'transid' => $get['transaction_id']], ['id' => $order['id']]);
                        pdo_update(YOUMI_NAME . '_activity', ['success +=' => 1], ['id' => $order['activity_id']]);
                        if ($order['leader'] == 1) {

                            pdo_update(YOUMI_NAME . '_group', ['status' => 3], ['id' => $order['group_id']]);
                        } else {


                            //判断团支付成功人数是否等于成团人数
                            $successNum = pdo_fetchcolumn("select count(id) from " . tablename(YOUMI_NAME . '_order') . " where group_id = :group_id and status = 2", [':group_id' => $order['group_id']]);

                            if ($successNum == $activity['group_num']) {
                                pdo_update(YOUMI_NAME . '_group', ['status' => 1, 'success_time' => time(), 'now_num' => $successNum], ['id' => $order['group_id']]);

                                //发放佣金
                                $orders = pdo_getall(YOUMI_NAME . '_' . 'order', ['group_id' => $order['group_id'], 'status' => 2, 'fmid !=' => 0], ['id', 'price', 'fmid', 'uniacid']);
                                $commission = $group = pdo_getcolumn(YOUMI_NAME . '_group', ['id' => $order['group_id']], 'commission');

//                                $forders = [];
//                                $fmids = [];
//                                foreach ($orders as $order) {
//                                    if (!in_array($order['fmid'], $fmids)) {
//                                        $fmids[] = $order['fmid'];
//                                        $forders[] = $order;
//                                    }
//                                }
//                                foreach ($forders as $order) {
//                                    sendCommission($order, $commission);
//                                }

                                $sum_coms=[];
                                foreach ($orders as $order) {
                                    if ($sum_coms[$order['fmid']]){

                                        $sum_coms[$order['fmid']]+=$order['commission'];
                                    }else{
                                        $sum_coms[$order['fmid']]=$order['commission'];
                                    }
                                }
                                foreach ($sum_coms as $fmid=> $com) {
                                    pdo_update(YOUMI_NAME . '_order',['commission'=>$com],['mid'=>$fmid,'group_id'=>$order['group_id']]);
                                }
                                foreach ($orders as $order) {
                                    sendCommission($order, $commission);
                                }

                            } else {
                                pdo_update(YOUMI_NAME . '_group', ['now_num' => $successNum], ['id' => $order['group_id']]);
                            }
                        }


                        require_once IA_ROOT . '/addons/' . YOUMI_NAME . '/core/functions/order.php';

                        youmi_settlement_log($order, 1, $order['price'], YOUMI_NAME . '用户支付订单：订单ID：' . $order['id'] . '，支付时间：' . date('Y-m-d H:i:s'));
                    } else {
                        pdo_update(YOUMI_NAME . '_' . 'order', ['status' => 7, 'pay_time' => TIMESTAMP, 'transid' => $get['transaction_id']], ['id' => $order['id']]);
                    }
                }
                if ($isxml) {
                    $result = array(
                        'return_code' => 'SUCCESS',
                        'return_msg' => 'OK'
                    );
                    echo array2xml($result);
                    exit;
                } else {
                    exit('success');
                }
            }
        }
    }
}
if ($isxml) {
    $result = array(
        'return_code' => 'FAIL',
        'return_msg' => ''
    );
    echo array2xml($result);
    exit;
} else {
    exit('fail');
}
function sendCommission($order, $commission)
{

//    if (empty($order['fmid'])) {
//        return;
//    }
    $f_member = pdo_get(YOUMI_NAME . '_member', array('mid' => $order['fmid'], 'uniacid' => $order['uniacid']), ['openid', 'mid']);

    insert_log($f_member);
    $tid = 'groupsimple' . time() . $order['id'];
    if ($order['price'] >= $commission) {

        $res = youmi_finance($f_member['openid'], $tid, $commission, '佣金');
        insert_log($res);

        if ($res['result_code'] == 'SUCCESS') {
            pdo_update(YOUMI_NAME . '_order', array('send_status' => 1), array('id' => $order['id']));
        } else {
            pdo_update(YOUMI_NAME . '_order', array('send_status' => 2), array('id' => $order['id']));
        }

    }
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

