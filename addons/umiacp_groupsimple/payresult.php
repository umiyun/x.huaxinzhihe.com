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

function create_order_message($order)
{
    $type = 'create_order';
    $temp = youmi_setting_get_by_skey($type);
    if (!$temp['temp_id']) {
        return false;
    }
    $openid = pdo_getcolumn(YOUMI_NAME . '_member', ['mid' => $order['mid']], 'openid');

    $data['uniacid'] = $order['uniacid'];
    $data['openid'] = $openid;
    $data['type'] = $type;
    $data['order_id'] = $order['id'];
    $data['temp_id'] = trim($temp['temp_id']);
    $data['url'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . "/app/index.php?i={$order['uniacid']}&c=entry&do=order&m=" . YOUMI_NAME;

    $send = $temp['data'];

    $send['first']['value'] = str_replace('#昵称#', $order['nickname'], $send['first']['value']);
    $send['first']['value'] = str_replace('#姓名#', $order['realname'], $send['first']['value']);
    $send['first']['value'] = str_replace('#电话#', $order['mobile'], $send['first']['value']);
    $send['first']['value'] = str_replace('#商品#', $order['title'], $send['first']['value']);

    $send['keyword1']['value'] = $order['tid'];
    $send['keyword2']['value'] = $order['price'];

    $send['remark']['value'] = str_replace('#昵称#', $order['nickname'], $send['remark']['value']);
    $send['remark']['value'] = str_replace('#姓名#', $order['realname'], $send['remark']['value']);
    $send['remark']['value'] = str_replace('#电话#', $order['mobile'], $send['remark']['value']);
    $send['remark']['value'] = str_replace('#商品#', $order['title'], $send['remark']['value']);

    $data['send'] = json_encode($send, 256);
    $data['status'] = 1;
    $data['createtime'] = time();
    pdo_insert(YOUMI_NAME . '_message', $data);
    return pdo_insertid();
}

function group_success_message($group_id)
{
    $type = 'group_success';
    $temp = youmi_setting_get_by_skey($type);
    if (!$temp['temp_id']) {
        return false;
    }
    $orders = pdo_getall(YOUMI_NAME . '_order', ['group_id' => $group_id, 'status' => 2]);
    $count = 0;
    if ($orders) {
        foreach ($orders as &$order) {
            $openid = pdo_getcolumn(YOUMI_NAME . '_member', ['mid' => $order['mid']], 'openid');

            $data['uniacid'] = $order['uniacid'];
            $data['openid'] = $openid;
            $data['type'] = $type;
            $data['order_id'] = $order['id'];
            $data['temp_id'] = trim($temp['temp_id']);
            $data['url'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . "/app/index.php?i={$order['uniacid']}&c=entry&activity_id={$order['activity_id']}&op=detail&id={$group_id}&do=index&m=" . YOUMI_NAME;

            $send = $temp['data'];

            $send['first']['value'] = str_replace('#昵称#', $order['nickname'], $send['first']['value']);
            $send['first']['value'] = str_replace('#姓名#', $order['realname'], $send['first']['value']);
            $send['first']['value'] = str_replace('#电话#', $order['mobile'], $send['first']['value']);
            $send['first']['value'] = str_replace('#商品#', $order['title'], $send['first']['value']);

            $send['keyword1']['value'] = $order['tid'];
            $send['keyword2']['value'] = $order['title'];

            $send['remark']['value'] = str_replace('#昵称#', $order['nickname'], $send['remark']['value']);
            $send['remark']['value'] = str_replace('#姓名#', $order['realname'], $send['remark']['value']);
            $send['remark']['value'] = str_replace('#电话#', $order['mobile'], $send['remark']['value']);
            $send['remark']['value'] = str_replace('#商品#', $order['title'], $send['remark']['value']);

            $data['send'] = json_encode($send, 256);
            $data['status'] = 1;
            $data['createtime'] = time();
            $count += pdo_insert(YOUMI_NAME . '_message', $data);

            unset($send);
            unset($data);
            unset($openid);
            unset($order);
        }
    }
    return $count;
}

function no_stock_message($order)
{
    $type = 'no_stock';
    $temp = youmi_setting_get_by_skey('no_stock');
    if (!$temp['temp_id']) {
        return false;
    }
    $openid = pdo_getcolumn(YOUMI_NAME . '_member', ['mid' => $order['mid']], 'openid');

    $data['uniacid'] = $order['uniacid'];
    $data['openid'] = $openid;
    $data['type'] = $type;
    $data['order_id'] = $order['id'];
    $data['temp_id'] = trim($temp['temp_id']);
    $data['url'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . "/app/index.php?i={$order['uniacid']}&c=entry&do=order&m=" . YOUMI_NAME;

    $send = $temp['data'];

    $send['first']['value'] = str_replace('#昵称#', $order['nickname'], $send['first']['value']);
    $send['first']['value'] = str_replace('#姓名#', $order['realname'], $send['first']['value']);
    $send['first']['value'] = str_replace('#电话#', $order['mobile'], $send['first']['value']);
    $send['first']['value'] = str_replace('#商品#', $order['title'], $send['first']['value']);

    $send['keyword1']['value'] = 0;
    $send['keyword2']['value'] = date('Y-m-d H:i:s');

    $send['remark']['value'] = str_replace('#昵称#', $order['nickname'], $send['remark']['value']);
    $send['remark']['value'] = str_replace('#姓名#', $order['realname'], $send['remark']['value']);
    $send['remark']['value'] = str_replace('#电话#', $order['mobile'], $send['remark']['value']);
    $send['remark']['value'] = str_replace('#商品#', $order['title'], $send['remark']['value']);

    $data['send'] = json_encode($send, 256);
    $data['status'] = 1;
    $data['createtime'] = time();
    pdo_insert(YOUMI_NAME . '_message', $data);
    return pdo_insertid();
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

                $commission = 0;
                if ($order['status'] == 1) {

                    if ($order['pay_type'] != 3) {

                        $num = intval($activity['group_num']);
                        $commission = floatval($activity['commission']);
                        if (intval($activity['success']) + intval($num) <= intval($activity['gnum'])) {
                            pdo_update(YOUMI_NAME . '_' . 'order', ['status' => 2, 'pay_time' => TIMESTAMP, 'commission' => $activity['commission'], 'transid' => $get['transaction_id']], ['id' => $order['id']]);

                            //购买成功模版
                            create_order_message($order);


                            if ($order['leader'] == 1) {

                                $group['uniacid'] = $order['uniacid'];
                                $group['mid'] = $order['mid'];
                                $group['activity_id'] = $activity['id'];
                                $group['shop_id'] = $activity['shop_id'];
                                $group['commission'] = $activity['commission'];
                                $group['createtime'] = time();
                                $group['group_num'] = $activity['group_num'];
                                $group['now_num'] = 1;
                                $group['group_id'] = $order['tid'];
                                $group['nickname'] = $order['nickname'];
                                $group['avatar'] = $order['avatar'];
                                $group['status'] = 3;

                                pdo_insert(YOUMI_NAME . '_group', $group);
                                $group_id = pdo_insertid();
                                pdo_update(YOUMI_NAME . '_order', ['group_id' => $group_id], ['id' => $order['id']]);
                            } else {

                                //判断团支付成功人数是否等于成团人数
                                $successNum = pdo_fetchcolumn("select count(id) from " . tablename(YOUMI_NAME . '_order') . " where group_id = :group_id and status = 2", [':group_id' => $order['group_id']]);

                                if ($successNum == $activity['group_num']) {
                                    //团成功
                                    pdo_update(YOUMI_NAME . '_group', ['status' => 1, 'success_time' => time(), 'now_num' => $successNum], ['id' => $order['group_id']]);
                                    pdo_update(YOUMI_NAME . '_activity', ['success +=' => $successNum], ['id' => $order['activity_id']]);

                                    //团成功模版
                                    group_success_message($order['group_id']);

                                } else {

                                    pdo_update(YOUMI_NAME . '_group', ['now_num' => $successNum], ['id' => $order['group_id']]);
                                }

                            }
                        } else {

                            //库存不足一团所有在拼团全失败
                            pdo_update(YOUMI_NAME . '_group', ['status' => 2], ['activity_id' => $activity['id'], 'status' => 3]);
                            pdo_update(YOUMI_NAME . '_' . 'order', ['status' => 2, 'pay_time' => TIMESTAMP, 'commission' => $activity['commission'], 'transid' => $get['transaction_id']], ['group_id' => $order['group_id']]);

                            //拼团失败模版

                        }

                    } else {

                        if (intval($activity['success']) >= intval($activity['gnum'])) {
                            pdo_update(YOUMI_NAME . '_' . 'order', ['status' => 8, 'pay_time' => TIMESTAMP, 'transid' => $get['transaction_id']], ['id' => $order['id']]);

                            //库存不足模版
                            no_stock_message($order);

                        } else {
                            pdo_update(YOUMI_NAME . '_' . 'order', ['status' => 2, 'pay_time' => TIMESTAMP, 'transid' => $get['transaction_id']], ['id' => $order['id']]);

                            //购买成功模版
                            create_order_message($order);

                        }
                    }

                    require_once IA_ROOT . '/addons/' . YOUMI_NAME . '/core/functions/order.php';
                    youmi_settlement_log($order, 1, floatval($order['price']) - floatval($commission), YOUMI_NAME . '用户支付订单：订单ID：' . $order['id'] . '，支付时间：' . date('Y-m-d H:i:s'));

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
