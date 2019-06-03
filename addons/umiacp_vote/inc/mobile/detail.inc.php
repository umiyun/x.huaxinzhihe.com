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

$member = $this->member;
$setting = youmi_setting_get_list();
youmi_puv('index');


if ($op == 'display') {
    $cut_id=intval($_GPC['cut_id']);
    $activity_id = intval($_GPC['activity_id']);

    if (!$activity_id) {
        message('请选择对应活动');
    }
    $cut = pdo_get(YOUMI_NAME . '_cut', ['id' => $cut_id]);
    $cut['vote_imgs']=unserialize($cut['vote_imgs']);

    if ($cut['status']==1) {
        message('信息审核中');
    }
    if ($cut['status']==3) {
        message('信息内容不合规范，拒绝通过');
    }

    $cut_my = pdo_get(YOUMI_NAME . '_cut', ['activity_id' => $activity_id,'mid' => $this->mid]);
    $activity = pdo_get(YOUMI_NAME . '_activity', ['id' => $activity_id]);

//    名次
    $ranking = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_cut') .' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id .' and status=2 and times > '.$cut['times'].' order by times ASC ');
    $ranking2 = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_cut') .' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id .' and status=2 and times < '.$cut['times'].' order by times desc ');
    if($ranking){
        $ranking_prev=$ranking[0]['times']-$cut['times'];
    }else{
        $ranking_prev=0;
    }
    if($ranking2){
        $ranking_next=$cut['times']-$ranking2[0]['times'];
    }else{
        $ranking_next=0;
    }

    $m_avatar=pdo_fetchall('select avatar from ' . tablename(YOUMI_NAME . '_record') .' where uniacid = ' . $uniacid . ' and cut_id = ' . $cut_id.' group by avatar order by createtime ASC');

    $con = '';
    if ($activity['status'] == 1 || $activity['endtime'] < time()) {
        if(!$cut_my){//未报名
            $activity['status2']=4;
        }else{
            $activity['status2']=6;
        }
    }
    if($activity['shop_id']>0){
        $shop=pdo_get(UMI_NAME . '_shop', ['id' => $activity['shop_id']]);
        if($shop['endtime']<time()&&$shop['times']>$setting['vip_times']){
            $activity['status2']=2;
            pdo_update(UMI_NAME . '_activity',array('status'=>2),['shop_id' => $activity['shop_id']]);
            pdo_update(YOUMI_NAME . '_activity',array('status'=>2),['id' => $activity['activity_id']]);
        }
    }
    $_W['page']['title'] = $activity['title'] ? $activity['title'] : '活动宝投票活动';
    $isvoter=pdo_get(YOUMI_NAME . '_voter', ['activity_id' => $activity_id,'mid' => $this->mid]);
    $activity['image'] = tomedia($activity['image']);
    $activity['bgimage'] = tomedia($activity['bgimage']);
    $activity['dl_img'] = tomedia($activity['dl_img']);
    $activity['dlactive_img'] = tomedia($activity['dlactive_img']);
    $activity['banner'] = tomedia($activity['banner']);
    $activity['desc_imgs'] = unserialize($activity['desc_imgs']);
    $activity['preferential_val'] = unserialize($activity['preferential_val']);
    foreach ($activity['desc_imgs'] as &$desc_img) {
        $desc_img = tomedia($desc_img);
        unset($desc_img);
    }
    $activity['shop_imgs'] = unserialize($activity['shop_imgs']);
    $activity['effects_imgs'] = unserialize($activity['effects_imgs']);
    foreach ($activity['shop_imgs'] as &$desc_img) {
        $desc_img = tomedia($desc_img);
        unset($desc_img);
    }
    foreach ($activity['effects_imgs'] as &$desc_img) {
        $desc_img = tomedia($desc_img);
        unset($desc_img);
    }
    $activity['userinfo'] = unserialize($activity['userinfo']);

    $records = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_cut') .' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id .' and status =2 order by times DESC,createtime ASC');

    $update['pv +='] = 1;
    pdo_update(YOUMI_NAME . '_cut', $update, ['id' => $cut_id]);

    $_share['title'] = "我是".$cut['realname'].",第".$cut['id']."号,正在参与".$activity['title'];
    $_share['imgUrl'] = $cut['vote_imgs'][0];
    $_share['desc'] = $activity['title'];
    if(empty($setting['cannon_fodder'])){
        $_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('detail', array('fmid' => $this->mid, 'activity_id' => $activity_id,'cut_id'=>$cut['id']));
    }else{
        $_share['link'] = $setting['cannon_fodder'] . "app/" . $this->createMobileUrl('detail', array('fmid' => $this->mid, 'activity_id' => $activity_id,'cut_id'=>$cut['id']));
    }

    include $this->template('detail');
    exit();
}

if ($op == 'share') {
    $cut_id = intval($_GPC['cut_id']);
    $update['share +='] = 1;
    pdo_update(YOUMI_NAME . '_cut', $update, ['id' => $cut_id]);
}
