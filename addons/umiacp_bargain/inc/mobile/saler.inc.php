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

    if (!$saler && !$shop) {
        message('非常抱歉，您无权核销此商品');
    }
}

$order['goods'] = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'goods') . ' where uniacid = :uniacid and id = ' . $order['goods_id'], [':uniacid' => $uniacid]);
$order['goods']['image'] = tomedia($order['goods']['image']);
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
    }
//    message($res);
    $errno = $res ? 0 : 1;
    if ($res){
        //发送核销通知
        $url=$_W['siteroot'] . "app/" .$this->createMobileUrl('index', array( 'activity_id' => $order['activity_id']));
        $res=     handleLightMsg($_W['openid'],$order['title'],$order['price'], $this->username,$url);
    }


    message('核销' . ($res ? '成功' : '失败'), '', $res ? 'success' : 'error');

    youmi_result($errno, '核销' . ($res ? '成功' : '失败'));

}
function handleSalerMsg($openid,$k1,$k2, $k3,$url){

    $args=[
        'keyword1'=>$k1,
        'keyword2'=>$k2,
        'keyword3'=>$k3,
        'keyword4'=>date('Y-m-d H:i:s'),
    ];
    return sendLightMsg($openid,$args,$url);
}
function sendSalerMsg($openid,$args,$url){

    $setting=  youmi_setting_get_list();
    $data = array(
        'first' => array(
            'value' => $setting['saler_first'],
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
        'remark' => array(
            'value' => $setting['saler_remark'],
            'color' => '#ff510'
        ),
    );

    $account_api = WeAccount::create();

    return $account_api->sendTplNotice($openid, $setting['saler_id'], $data,$url);
}
