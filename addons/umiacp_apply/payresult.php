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

if(is_array($setting['payment'])) {
    $wechat = $setting['payment']['wechat'];
    WeUtility::logging('pay', var_export($get, true));
    if(!empty($wechat)) {
        ksort($get);
        $string1 = '';
        foreach($get as $k => $v) {
            if($v != '' && $k != 'sign') {
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

        if($sign == $get['sign']) {

            if (intval($wechat['switch']) == PAYMENT_WECHAT_TYPE_SERVICE) {
                $get['openid'] = $log['openid'];
            }
            if(!empty($log) && $log['status'] == '0' && (($get['total_fee'] / 100) == $log['card_fee'])) {
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

                    if ($activity['signstatus'] == 2 && $activity['signnum'] <= $activity['success']) {

                        pdo_update(YOUMI_NAME . '_' . 'order', ['status' => 7, 'pay_time' => TIMESTAMP, 'transid' => $get['transaction_id']], ['id' => $order['id']]);
                    } else {


                        pdo_update(YOUMI_NAME . '_' . 'order', ['status' => 2, 'pay_time' => TIMESTAMP, 'transid' => $get['transaction_id']], ['id' => $order['id']]);
                        pdo_update(YOUMI_NAME . '_activity', ['success +=' => 1], ['id' => $order['activity_id']]);
                        pdo_update(YOUMI_NAME . '_cut', ['status' => 3], ['id' => $order['cut_id']]);



                        //发送订单通知
//                        $url=$_W['siteroot'] . "app/" . createMobileUrl('index', array( 'activity_id' => $order['activity_id']));
//
//                        $res=     handleOrderMsg($_W['openid'],$activity['title2'],$order['tid'],$url);

                        require_once IA_ROOT . '/addons/' . YOUMI_NAME . '/core/functions/order.php';
                        youmi_settlement_log($order, 1, $order['price'], YOUMI_NAME . '用户支付订单：订单ID：' . $order['id'] . '，支付时间：' . date('Y-m-d H:i:s'));
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

 function createMobileUrl($do, $query = array(), $noredirect = true) {
insert_log('创建链接');
    $query['do'] = $do;
    $query['m'] = strtolower($this->modulename);
    require_once '../../framework/function/global.func.php';
    return murl('entry', $query, $noredirect);
}
function handleOrderMsg($openid,$name,$tid,$url){

    $args=[
        'keyword1'=>$name,
        'keyword2'=>$tid,
        'keyword3'=>'下单时间 '.date('Y-m-d H:i:s'),
    ];
    return sendOrderMsg($openid,$args,$url);
}
function sendOrderMsg($openid,$args,$url){
    require_once IA_ROOT . '/addons/umiacp_apply/core/functions/setting.php';
    insert_log('sendOrderMsg');
    $setting=  youmi_setting_get_list();
    $data = array(
        'first' => array(
            'value' => $setting['order_first'],
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
        'remark' => array(
            'value' => $setting['order_remark'],
            'color' => '#ff510'
        ),
    );
    insert_log($setting);
    $account_api = WeAccount::create();
    insert_log('WeAccount');
    return $account_api->sendTplNotice($openid, $setting['order_id'], $data,$url);
}
if($isxml) {
    $result = array(
        'return_code' => 'FAIL',
        'return_msg' => ''
    );
    echo array2xml($result);
    exit;
} else {
    exit('fail');
}
