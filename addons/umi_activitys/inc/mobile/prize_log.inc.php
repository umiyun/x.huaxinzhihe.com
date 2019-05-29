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
if(empty($_GPC['status'])){
    $_GPC['status']=3;
}
$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'display';

$setting = youmi_setting_get_list();
$_W['page']['title'] = $setting['title'] ? $setting['title'] : '中奖管理';
$goods=pdo_getall($_GPC['module'].'_goods',array('activity_id'=>$_GPC['activity_id']));
$member = $this->member;
$con='';
if(!empty($goods)) {
    if (intval($_GPC['goods_id']) == 0) {
        $_GPC['goods_id'] = $goods[0]['id'];
    }
    $con = ' and goods_id=' . $_GPC['goods_id'];
}
$data=array();
$data['activity_id']=$_GPC['activity_id'];
$data['status']=1;
$list=pdo_getall($_GPC['module'].'_prize_log',$data);
foreach ($list as &$item){
    $item['member']=pdo_get($_GPC['module'].'_member',array('mid'=>$item['mid']));
    $item['createtime']=date('Y-m-d H:i:s',$item['createtime']);
    unset($item);
}

if ($op == 'display') {

    include $this->template('prize_log');
    exit();
}