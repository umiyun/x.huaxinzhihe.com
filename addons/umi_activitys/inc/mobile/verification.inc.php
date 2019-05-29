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
$_W['page']['title'] = $setting['title'] ? $setting['title'] : '核销管理';
$goods=pdo_getall($_GPC['module'].'_goods',array('activity_id'=>$_GPC['activity_id']));
$member = $this->member;
$con='';
if(!empty($goods)) {
    if (intval($_GPC['goods_id']) == 0) {
        $_GPC['goods_id'] = $goods[0]['id'];
    }
    $con = ' and goods_id=' . $_GPC['goods_id'];
}
$total_saler=pdo_fetchcolumn('select count(*) from '.tablename($_GPC['module'].'_order').' where activity_id=:activity_id and salertime>0'.$con,array(
    ':activity_id'=>$_GPC['activity_id']
));
$total_saler2=pdo_fetchcolumn('select count(*) from '.tablename($_GPC['module'].'_cut').' where activity_id=:activity_id and islottery=3'.$con,array(
    ':activity_id'=>$_GPC['activity_id']
));
$total_nosaler=pdo_fetchcolumn('select count(*) from '.tablename($_GPC['module'].'_order').' where activity_id=:activity_id and salertime=0 and status=2 '.$con,array(
    ':activity_id'=>$_GPC['activity_id']
));
$total_nosaler2=pdo_fetchcolumn('select count(*) from '.tablename($_GPC['module'].'_cut').' where activity_id=:activity_id and islottery=2 '.$con,array(
    ':activity_id'=>$_GPC['activity_id']
));
$data=array();
$data['activity_id']=$_GPC['activity_id'];
$data['status']=$_GPC['status'];
if($_GPC['goods_id']>0){
    $data['goods_id']=$_GPC['goods_id'];
}
$data2=array();
$data2['activity_id']=$_GPC['activity_id'];
$data2['islottery']=$_GPC['islottery']?$_GPC['islottery']:3;

$list=pdo_getall($_GPC['module'].'_order',$data);
$list2=pdo_getall($_GPC['module'].'_cut',$data2);
foreach ($list as &$item){
    $item['member']=pdo_get($_GPC['module'].'_member',array('mid'=>$item['mid']));
    $item['salertime']=date('Y-d-m H:i:s',$item['salertime']);
    unset($item);
}
foreach ($list2 as &$item){
    $item['member']=pdo_get($_GPC['module'].'_member',array('mid'=>$item['mid']));
//    $item['salertime']=date('Y-d-m H:i:s',$item['salertime']);
    unset($item);
}

if ($op == 'display') {
    $_share['title'] = $setting['share_title'];
    $_share['imgUrl'] = tomedia($setting['share_image']);
    $_share['desc'] = $setting['share_desc'];
    $_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index', array('fopenid' => $openid));

    if($_GPC['module']=='umiacp_vote'||$_GPC['module']=='umiacp_lighten'){
        include $this->template('verification2');
    }else{
        include $this->template('verification');
    }

    exit();
}
if ($op == 'setting') {

    $fmid= pdo_getcolumn(YOUMI_NAME.'_shop',array('uniacid'=>$_W['uniacid'],'openid'=>$openid),'mid');
    $list=pdo_getall(YOUMI_NAME.'_saler',array('fmid'=>$fmid));
    include $this->template('verification_set');
    exit();
}
if ($op == 'search') {
    $keyword=$_GPC['keyword'];
    $list=pdo_getall('mc_mapping_fans',array('uniacid'=>$_W['uniacid'],'nickname LIKE'=>'%'.$keyword.'%'));
    foreach ($list as &$item)
    {
        $item['avatar']=pdo_fetchcolumn('select avatar from '.tablename('mc_members').' where uid=:uid',array(':uid'=>$item['uid']));
        unset($item);
    }
    die(json_encode($list));
    exit();
}
if ($op == 'addmember') {
    $havemember=pdo_get(YOUMI_NAME.'_saler',array('openid'=>$_GPC['nopenid']));
    $nmember= pdo_get('mc_mapping_fans',array('uniacid'=>$_W['uniacid'],'openid'=>$_GPC['nopenid']));
    $avatar=pdo_fetchcolumn('select avatar from '.tablename('mc_members').' where uid=:uid',array(':uid'=>$nmember['uid']));
    $fmid=pdo_get(YOUMI_NAME.'_shop',array('openid'=>$openid,'uniacid'=>$uniacid));
    if($havemember){
        $message='已经是核销员，请勿重复添加';
    }else{
        $data=array(
            'uniacid'=>$uniacid,
            'openid'=>$_GPC['nopenid'],
            'nickname'=>$nmember['nickname'],
            'avatar'=>$avatar,
            'fmid'=>$fmid['mid'],
            'createtime'=>TIMESTAMP
        );
        $status=pdo_insert(YOUMI_NAME . '_' . 'saler', $data);
    }
    $errno = !$havemember ? 0 : 1;

    youmi_result($errno, ($status ? '新增成功' : $message), $data);
}
if ($op == 'delmember') {

    $result = pdo_delete(YOUMI_NAME.'_saler', array('openid' => $_GPC['openid']));

    $errno = !$result ? 0 : 1;

    youmi_result(0, '删除'.($result ? '成功' : '失败'));
}