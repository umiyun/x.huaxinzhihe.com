<?php
/**
 * Created by IntelliJ IDEA.
 * User: 7³
 * Date: 2018/7/30 0030
 * Time: 12:18
 */

global $_W, $_GPC;

checkauth();

$uniacid = intval($_W['uniacid']);
$openid = trim($_W['openid']);

//获取用户要充值的金额数
$money = floatval($_GPC['money']);
$id = intval($_GPC['id']);
if ($id <= 0) {
    die(json_encode(['status' => 0, 'message' => '请选择活动']));
}
if ($money <= 0) {
    die(json_encode(['status' => 0, 'message' => '充值金额错误']));
}
$order = pdo_get(YOUMI_NAME . '_' . 'order', ['uniacid' => $uniacid, 'openid' => $openid, 'money' => $money, 'status' => 0]);
$status = 0;
if (!$order) {
    $tid = date('YmdHis') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
    $order['uniacid'] = $uniacid;
    $order['openid'] = $openid;
    $order['activity_id'] = $id;
    $order['title'] = '活动红包充值';
    $order['ordersn'] = $tid;
    $order['tid'] = $tid;
    $order['price'] = $money;
    $order['status'] = 0;
    $order['createtime'] = TIMESTAMP;
    $status = pdo_insert(YOUMI_NAME . '_' . 'order', $order);
    $order['id'] = pdo_insertid();
}
die(json_encode(['status' => $status, 'message' => '下单' . ($status ? '成功' : '失败'), 'orderid' => $order['id']]));
