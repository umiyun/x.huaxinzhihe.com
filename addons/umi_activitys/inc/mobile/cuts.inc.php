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
$_W['page']['title'] = '报名信息管理';

$member = $this->member;

foreach ($list as &$item){
    $item['member']=pdo_get($_GPC['module'].'_member',array('mid'=>$item['mid']));
    $item['salertime']=date('Y-d-m H:i:s',$item['salertime']);
    unset($item);
}

if ($op == 'display') {
    $activity = pdo_get($_GPC['module'] . '_activity', ['id' => $_GPC['activity_id']]);
    $list = pdo_fetchall('SELECT * FROM ' . tablename($_GPC['module'] . '_cut') . ' where uniacid = :uniacid and activity_id = :activity_id order by createtime DESC ' , [':uniacid'=>$uniacid,':activity_id'=>$_GPC['activity_id']]);

    if($_GPC['module']=='umiacp_vote'){
        foreach ($list as &$item) {
            switch ($item['status']) {
                case 1 :
                    $item['statusname'] = '审核中';
                    break;
                case 2 :
                    $item['statusname'] = '已通过';
                    break;
                case 3 :
                    $item['statusname'] = '已拒绝';
                    break;
                default :
                    $item['statusname'] = '';
                    break;
            }
            $item['vote_imgs']=unserialize($item['vote_imgs']);
            unset($item);
        }
    }else{
        foreach ($list as &$item) {

            switch ($item['status']) {
                case 1 :
                    $item['statusname'] = '已报名';
                    break;
                case 2 :
                    $item['statusname'] = '砍到底';
                    break;
                case 3 :
                    $item['statusname'] = '已支付';
                    break;
                case 4 :
                    $item['statusname'] = '';
                    break;
                default :
                    $item['statusname'] = '';
                    break;
            }
            unset($item);
        }
    }


    include $this->template('cuts');
    exit();
}
if ($op == 'prize') {

    $list = pdo_fetchall('SELECT * FROM ' . tablename($_GPC['module'] . '_reward') . ' where uniacid = :uniacid and activity_id = :activity_id order by createtime DESC ' , [':uniacid'=>$uniacid,':activity_id'=>$_GPC['activity_id']]);
    foreach ($list as &$item) {

        switch ($item['status']) {
            case 1 :
                $item['statusname'] = '已报名';
                break;
            case 2 :
                $item['statusname'] = '已支付';
                break;
            case 3 :
                $item['statusname'] = '已购买';
                break;
            case 4 :
                $item['statusname'] = '';
                break;
            default :
                $item['statusname'] = '';
                break;
        }
        unset($item);
    }

    include $this->template('cuts');
    exit();
}
if($op=='audit'){
    $id = intval($_GPC['id']);
    $status = intval($_GPC['status']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename($_GPC['module'] . '_cut'). ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        youmi_result(1, '报名人员不存在或是已经被删除！');
    }
    pdo_update($_GPC['module'] . '_' . 'cut', ['status' => $status], array('id' => $id));
    youmi_result(0, '操作成功');
}