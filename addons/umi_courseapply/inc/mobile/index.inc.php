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
$_W['page']['title'] = $setting['title'] ? $setting['title'] : '中国少年报名';

$member = $this->member;

youmi_puv('index');

$_share['title'] = $setting['share_title'];
$_share['imgUrl'] = tomedia($setting['share_image']);
$_share['desc'] = $setting['share_desc'];
$_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index');

if ($op == 'display') {

    include $this->template('index');
    exit();
}

if ($op == 'course') {
    $prizes = pdo_getall(YOUMI_NAME . '_' . 'prize', array('uniacid' => $uniacid, 'status' => 1));
    foreach ($prizes as &$pv){
        $order= pdo_get(YOUMI_NAME . '_' . 'order', ['uniacid' => $uniacid,'prize_id' => $pv['id'],'openid' => $openid]);
        if(!$order){
            $pv['statusname']='立即报名';//未开始
            $pv['status2']=1;//已报名、在报名时间内
            if(time()<$pv['starttime']){
                $pv['status2']=2;//未开始
                $pv['statusname']='报名未开放';//未开始
            }else {
                if (time() > $pv['endtime']) {
                    $pv['status2'] = 3;//已结束
                    $pv['statusname'] = '报名已结束';//未开始
                }else {
                    $order_all = pdo_getall(YOUMI_NAME . '_' . 'order', ['uniacid' => $uniacid, 'prize_id' => $pv['id']]);
                    if (count($order_all) >= intval($pv['num'])) {
                        $pv['status2'] = 4;//报名人数已满
                        $pv['statusname'] = '报名已满';//未开始
                    }
                }
            }
        }else{
            $pv['status2']=1;//已报名、在报名时间内
            $pv['statusname']='查看报名';//未开始
        }
        unset($pv);
    }

    include $this->template('course');
    exit();
}
if ($op == 'signup') {
    $id = intval($_GPC['id']);
    $prize = pdo_get(YOUMI_NAME . '_' . 'prize', array('uniacid' => $uniacid, 'id' => $id));
    $order= pdo_get(YOUMI_NAME . '_' . 'order', ['uniacid' => $uniacid,'prize_id' => $id,'openid' => $openid]);
    include $this->template('signup');
    exit();
}
if ($op == 'hexiao') {
    $id = intval($_GPC['id']);
    $saler = pdo_get(YOUMI_NAME . '_' . 'saler', ['uniacid' => $uniacid, 'mid' => $this->mid]);
    if ($saler['status'] != 1||empty($saler)) {
        message('无权核销');
    }
    $order = pdo_get(YOUMI_NAME . '_' . 'order', array('uniacid' => $uniacid, 'id' => $id));
    $order['prize']= pdo_get(YOUMI_NAME . '_' . 'prize', ['uniacid' => $uniacid,'id' => $order['prize_id']]);
    if(empty($order)){
        message('暂无报名记录');
    }else{
        if($order['status']==3){
            message('已核销');
        }
    }
    include $this->template('hexiao');
    exit();
}
/**
 * 报名
 * do  index  op  apply
 * prize_id 票区id
 * realname 姓名
 * mobile   电话
 */
if ($op == 'apply') {
    $prize_id = intval($_GPC['prize_id']);
    $order= pdo_get(YOUMI_NAME . '_' . 'order', ['uniacid' => $uniacid,'prize_id' => $prize_id,'openid' => $openid]);
    $prize = pdo_get(YOUMI_NAME . '_' . 'prize', array('uniacid' => $uniacid, 'id' => $prize_id));
    if ($order) {
        youmi_result(1, '请勿重复报名');
    }
    if (!$prize_id) {
        youmi_result(1, '请选择课程');
    }
    if(time()<$prize['starttime']){
        youmi_result(1, '报名暂未开放');
    }
    if(time()>$prize['endtime']){
        youmi_result(1, '报名已结束');
    }
    $order_all = pdo_getall(YOUMI_NAME . '_' . 'order', ['uniacid' => $uniacid,'prize_id' => $prize_id]);
    if (count($order_all)>=intval($prize['num'])) {
        youmi_result(1, "报名人数已满");
    }
    $order = [
        'uniacid' => $uniacid,
        'mid' => $this->mid,
        'openid' => $this->openid,
        'nickname' => $member['nickname'],
        'avatar' => $member['avatar'],
        'prize_id' => $prize_id,
        'title' => $prize['title'],
        'realname' => trim($_GPC['realname']),
        'mobile' => trim($_GPC['mobile']),
        'number' => trim($_GPC['number']),
        'status' => 1,
        'createtime' => time(),
    ];
    pdo_insert(YOUMI_NAME . '_' . 'order', $order);
    $order['id'] = pdo_insertid();
    youmi_result(0, '报名成功', $order);
}

/**
 * 核销
 * do  index  op  check
 * order_id 订单id
 */
if ($op == 'check') {
    $id = intval($_GPC['id']);
    $order= pdo_get(YOUMI_NAME . '_' . 'order', ['uniacid' => $uniacid,'id' => $id]);
    if ($order['status'] == 3) {
        youmi_result(1, '订单已核销或未完成');
    }
    $saler = pdo_get(YOUMI_NAME . '_' . 'saler', ['uniacid' => $uniacid, 'mid' => $this->mid]);
    if ($saler['status'] != 1) {
        youmi_result(1, '无权核销');
    }
    pdo_update(YOUMI_NAME . '_' . 'order', ['status' => 3, 'saler_time' => time(), 'saler_id' => $this->mid], ['id' => $id]);
    youmi_result(0, '核销成功', $order);
}
