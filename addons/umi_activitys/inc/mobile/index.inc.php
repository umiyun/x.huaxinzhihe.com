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
$_W['page']['title'] = $setting['title'] ? $setting['title'] : '活动宝';

$member = $this->member;

$shop=pdo_get(YOUMI_NAME . '_' . 'shop',array('mid'=>$member['mid']));
$notice=pdo_getall(YOUMI_NAME . '_' . 'notice',array('uniacid'=>$uniacid,'status'=>1),array() , '' , 'sort DESC,createtime DESC');
$navs=pdo_getall(YOUMI_NAME . '_' . 'navs',array('uniacid'=>$uniacid,'status'=>1),array() , '' , 'sort DESC,createtime DESC');
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
        $is_vip=1;
    }

}

if ($op == 'display') {



    $_share['title'] = $setting['share_title'];
    $_share['imgUrl'] = tomedia($setting['share_image']);
    $_share['desc'] = $setting['share_desc'];
    $_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index', array('fopenid' => $openid));
    $banner = pdo_getall(YOUMI_NAME . '_' . 'banner', ['uniacid' => $uniacid, 'status' => 1],array() , '' , 'sort DESC,createtime DESC');
    foreach ($banner as &$item) {
        $item['image'] = tomedia($item['image']);
        unset($item);
    }

    $temp=$setting['template'];
    if($temp==1){
        include $this->template('index');
    }elseif($temp==2){
        $cate=pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_cate') . ' where uniacid = ' . $uniacid . ' and status > -1 and type=1  and hot = 1 order by sort desc,createtime desc');
        foreach ($cate as &$cv){
            $cate_id = $cv['id'];
            $where['uniacid'] = $uniacid;
            $where['status'] = 1;

            switch ($cv['type']) {
                case 0 :
                    $where['status'] = 1;
                    break;
                case 1 :
                    $where['cate_id1'] = $cate_id;
                    break;
                case 2 :
                    $where['cate_id2'] = $cate_id;
                    break;
                case 3 :
                    $where['cate_id3'] = $cate_id;
                    break;
                case 4 :
                    $where['cate_id4'] = $cate_id;
                    break;
            }
            $cv['case'] = pdo_getall(YOUMI_NAME . '_' . 'case', $where,array() , '' , 'sort DESC,createtime DESC', array(1,6));
            foreach ($cv['case'] as &$item) {
                $item['logo'] = tomedia($item['logo']);
                unset($item);
            }
            unset($cv);
        }

        include $this->template('index2');
    }

    exit();
}
if ($op == 'notice') {
    $notice=pdo_get(YOUMI_NAME . '_' . 'notice',array('uniacid'=>$uniacid,'status'=>1,'id'=>$_GPC['id']));
    include $this->template('notice');
    exit();
}
