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

$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'display';

$setting = youmi_setting_get_list();
$_W['page']['title'] = $setting['title'] ? $setting['title'] : '我的';

$member = $this->member;

$shop=pdo_get(YOUMI_NAME . '_' . 'shop',array('mid'=>$member['mid']));

$account = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'account') . ' where uniacid = :uniacid and shop_id = ' . $shop['id'], [':uniacid' => $uniacid]);
$total=pdo_fetchcolumn('select count(*) from '. tablename(YOUMI_NAME . '_' . 'activity') .' where uniacid = :uniacid and shop_id =:shop_id',array(':uniacid'=>$uniacid,':shop_id'=>$shop['id']));
$total_pv=pdo_fetchcolumn('select sum(pv) from '. tablename(YOUMI_NAME . '_' . 'activity') .' where uniacid = :uniacid and shop_id =:shop_id',array(':uniacid'=>$uniacid,':shop_id'=>$shop['id']));
$total_participate=pdo_fetchcolumn('select sum(participate) from '. tablename(YOUMI_NAME . '_' . 'activity') .' where uniacid = :uniacid and shop_id =:shop_id',array(':uniacid'=>$uniacid,':shop_id'=>$shop['id']));

if(empty($shop)){
    $is_vip=0;
}else{
    if ($shop['endtime']<time()){
        $is_vip=0;
        $times=0;
        if($setting['vip_times']>$shop['times']){
            $times=$setting['vip_times']-$shop['times'];
        }
    }else{
        $endtime=date('Y-m-d',$shop['endtime']);
        $is_vip=1;
    }
}


if ($op == 'display') {
    $_share['title'] = $setting['share_title'];
    $_share['imgUrl'] = tomedia($setting['share_image']);
    $_share['desc'] = $setting['share_desc'];
    $_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index', array('fopenid' => $openid));

    $temp=$setting['template'];
    if($temp==1){
        include $this->template('user2');
    }else{
        $shop = pdo_get(YOUMI_NAME . '_' . 'shop', ['uniacid' => $this->uniacid, 'mid' => $this->mid]);
        include $this->template('user2');
    }
    exit();
}
if ($op == 'user_balance') {

    include $this->template('user_balance');
    exit();
}
if($op=='balance_list')
{
    include $this->template('balance_list');
    exit();
}
/**
 * 商家退款
 * do   store   op  refund
 * order_id     订单id
 * reason       退款原因
 */
if ($op == 'refund') {

    $order_id = intval($_GPC['order_id']);
    if (!$order_id) {
        youmi_result(1, '订单不存在');
    }
    $reason = trim($_GPC['reason']);
    youmi_puv('store_refund', $order_id, $reason);

    $order = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' where uniacid = :uniacid and id = ' . $order_id, [':uniacid' => $uniacid]);
    if (!$order) {
        youmi_result(1, '订单不存在');
    }
    if ($order['status'] == 4) {
        youmi_result(0, '订单已取消');
    }
    if ($order['status'] == 5) {
        youmi_result(0, '订单已退款');
    }
    if ($order['status'] != 3 || $order['goods_type'] != 2) {
        youmi_result(1, '订单无法退款');
    }
    $res = youmi_refund($order_id);
    if ($res['errno'] === 0) {
        pdo_update(YOUMI_NAME . '_' . 'order', ['reason' => $reason], ['id' => $order_id]);
        youmi_result(0, '订单退款成功！');
    } else {
        youmi_result(1, $res['err_code_des']);
    }

}


/**
 * 商家提现
 * do   store   op  withdraw
 * shop_id      提现商家
 * money        提现金额
 */
if ($op == 'withdraw') {
    $shop_id = intval($shop['id']);
    $money = floatval($_GPC['money']);
    if (!$shop_id) {
        youmi_result(1, '商家不存在');
    }
    youmi_puv(YOUMI_NAME . '_' .'withdraw', $shop_id, $money);



    $shop = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'shop') . ' where uniacid = :uniacid and id = ' . $shop_id, [':uniacid' => $uniacid]);
    if (!$account || !$shop) {
        youmi_result(1, '商家不存在');
    }
    if ($account['status'] == 1) {
        youmi_result(1, '存在未打款的记录，请等候打款成功后在申请提现');
    }
    if ($money < 2 || $money > 5000) {
        youmi_result(1, '单笔提现金额不能低于2元且不能高于5000元');
    }
    if (floatval($account['settled']) < $money) {
        youmi_result(1, '可提现金额不足');
    }

    $shop['proportion'] = floatval($setting['proportion']);

    $withdraw['uniacid'] = $uniacid;
    $withdraw['shop_id'] = $shop_id;
    $withdraw['apply'] = $money;
    $withdraw['proportion'] = $shop['proportion'];
    $withdraw['commission'] = $money * $setting['proportion'] * 0.01;
    $withdraw['withdraw'] = $money * (100-$setting['proportion']) * 0.01;
    $withdraw['tid'] = 'TX' . date('YmdHis') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
    $withdraw['status'] = 1;
    $withdraw['cardno'] = $_GPC['cardno'];
    $withdraw['createtime'] = TIMESTAMP;
    $res = pdo_insert(YOUMI_NAME . '_' . 'withdraw', $withdraw);
    $withdraw['id'] = pdo_insertid();
    if ($res) {

        $result = youmi_settlement_log($withdraw, 3, $money, '商家申请提现：申请人：' . ($_W['user'] ? $_W['username'] : $_W['fans']['nickname']) . '，申请时间：' . date('Y-m-d H:i:s'));
//        pdo_update(YOUMI_NAME . '_' . 'account', ['settled -=' => $money, 'apply' => $money, 'status' => 1], ['uniacid' => $uniacid, 'shop_id' => $shop_id]);
    }
    youmi_result($res ? 0 : 1, '申请' . ($res ? '成功' : '失败'));


}


/**
 * 商家提现列表
 * do   store   op  withdraw_list
 * shop_id      提现商家
 */
if ($op == 'withdraw_list') {
    $shop_id = intval($shop['id']);
    if (!$shop_id) {
        message('商家不存在');
    }
    $withdraw = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'withdraw') . ' where uniacid = :uniacid and shop_id = ' . $shop_id, [':uniacid' => $this->uniacid]);
    foreach ($withdraw as &$item) {
        switch ($item['status']) {
            case 1 :
                $item['statusname'] = '申请中';
                break;
            case 2 :
                $item['statusname'] = '已确认';
                break;
            case 3 :
                $item['statusname'] = '已打款';
                break;
            case 4 :
                $item['statusname'] = '打款失败';
                break;
            case 5 :
                $item['statusname'] = '拒绝打款';
                break;
            default :
                $item['statusname'] = '';
                break;
        }
        unset($item);
    }
//    $data['list'] = $withdraw ? $withdraw : [];
//    youmi_result(0, '商家提现列表', $data);

    include $this->template('balance_list');
    exit();
}
