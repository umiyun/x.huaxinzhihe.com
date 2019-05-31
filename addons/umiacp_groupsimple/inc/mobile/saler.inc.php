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
if (!$openid) {
    message('请在微信端打开');
}
if (!$this->mid) {
    message('请在先登录');
}

$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'display';

$setting = youmi_setting_get_list();

$order_id = intval($_GPC['order_id']);
if (!$order_id) {
    message('订单不存在');
}
$order = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' where uniacid = :uniacid and id = ' . $order_id, [':uniacid' => $uniacid]);
$cut = pdo_get(YOUMI_NAME . '_cut', ['id' => $order['cut_id']]);
$order['cut'] = $cut;

if (!$order) {
    message('订单不存在');
}

$saler = pdo_get(YOUMI_NAME . '_' . 'saler', ['uniacid' => $uniacid,'activity_id' => $order['activity_id'], 'shop_id' => $order['shop_id'], 'openid' => $this->openid, 'status' => 1]);
if (!$saler) {
    $shop = pdo_get(UMI_NAME . '_' . 'shop', ['uniacid' => $uniacid, 'id' => $order['shop_id'], 'openid' => $this->openid, 'status' => 2]);
    $member= pdo_get(UMI_NAME . '_' . 'saler', ['uniacid' => $uniacid,'openid' => $this->openid]);

    if (!$saler && !$shop&&!$member) {
        message('非常抱歉，您无权核销此商品');
    }
}

$order['goods'] = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'activity') . ' where uniacid = :uniacid and id = ' . $order['activity_id'], [':uniacid' => $uniacid]);
$order['shop'] = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'shop') . ' where uniacid = :uniacid and id = ' . $order['shop_id'], [':uniacid' => $uniacid]);


if ($order['status'] == 3) {
    message('订单已核销');
}
if ($order['status'] == 4) {
    message('订单已取消');
}
if ($order['status'] == 5) {
    message('订单已退款');
}


if ($op == 'display') {
    include $this->template('verification');
}

/**
 * 订单核销
 * do : saler   op : check
 * order_id     订单
 */
if ($op == 'check') {

    $res = pdo_update(YOUMI_NAME . '_' . 'order', ['status' => 3, 'saler_id' => $this->mid, 'saler_openid' => $this->openid, 'salertime' => time()], ['id' => $order_id]);
    if ($res) {
       $res= youmi_settlement_log($order, 2, $order['price'], '核销订单：订单ID：' . $order_id . '，核销员：' . $this->username . '，核销时间：' . date('Y-m-d H:i:s'));
//die(json_encode($res));
    }
//    message($res);
    $errno = $res ? 0 : 1;
//    message('核销' . ($res ? '成功' : '失败'), '', $res ? 'success' : 'error');
    youmi_result($errno, '核销' . ($res ? '成功' : '失败'));

}
