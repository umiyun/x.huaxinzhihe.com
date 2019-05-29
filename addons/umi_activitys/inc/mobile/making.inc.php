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
$_W['page']['title'] = $setting['title'] ? $setting['title'] : '活动制作';

$member = $this->member;

$module = trim($_GPC['module']);
$file = IA_ROOT . '/addons/' . $module . '/site.php';
if (!is_file($file)) {
    message('当前模块不存在');
}

require_once IA_ROOT . '/addons/umi_activitys/core/module/' . $module .'.php';

if ($op == 'display') {
    $_share['title'] = $setting['share_title'];
    $_share['imgUrl'] = tomedia($setting['share_image']);
    $_share['desc'] = $setting['share_desc'];
    $_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index', array('fopenid' => $openid));

    $activity_id = intval($_GPC['activity_id']);
    $activity = youmi_get_activity($activity_id);

    $item_mid = pdo_get(YOUMI_NAME . '_activity', ['id' => $activity_id]);

    $master=pdo_get(YOUMI_NAME . '_master', ['openid'=>$openid,'status'=>1,'uniacid'=>$uniacid]);
    if($item_mid['mid']!=$this->mid&&$activity_id){
        if(!$master) {
            message('暂无权限操作');
        }
    }

    $shop=pdo_get(YOUMI_NAME . '_' . 'shop',array('mid'=>$member['mid']));
    if(empty($shop)){
        $is_vip=0;
    }else{
        if ($shop['endtime']<time()){
            $is_vip=0;
        }else{
            $is_vip=1;
        }
    }

    $t_module = pdo_getall(YOUMI_NAME . '_template_module', ['uniacid' => $uniacid, 'module' => $module]);
    $template=array();
    if($t_module){
        foreach ($t_module as &$tmd){
            $template_one=pdo_get(YOUMI_NAME . '_template', ['uniacid' => $uniacid, 'id' => $tmd['template_id'], 'status' => 1]);
            if($template_one){
                $template_one['image'] = tomedia($template_one['image']);
                $template_one['bgimage'] = tomedia($template_one['bgimage']);
                $template_one['titlebgimg'] = tomedia($template_one['titlebgimg']);
                $template_one['shop_code'] = tomedia($template_one['shop_code']);
                $template[]=$template_one;
                unset($template_one);
                unset($tmd);
            }
        }

    }
//    $template = pdo_getall(YOUMI_NAME . '_template', ['uniacid' => $uniacid, 'status' => 1]);
//    foreach ($template as &$img) {
//        $img['image'] = tomedia($img['image']);
//        $img['bgimage'] = tomedia($img['bgimage']);
//        $img['titlebgimg'] = tomedia($img['titlebgimg']);
//        unset($img);
//    }

    $case_id = intval($_GPC['case_id']);
    $case = pdo_get(YOUMI_NAME . '_case', ['id' => $case_id]);
    $case['template']= pdo_get(YOUMI_NAME . '_template', ['id' => $case['template_id']]);
    if($case['template']){
        $case['template']['bgimage']=tomedia($case['template']['bgimage']);
        $case['template']['image']=tomedia($case['template']['image']);
        $case['template']['titlebgimg']=tomedia($case['template']['titlebgimg']);
    }else{
        $case['template']=$template[0];
        $case['template']['bgimage']=tomedia($case['template']['bgimage']);
        $case['template']['image']=tomedia($case['template']['image']);
        $case['template']['titlebgimg']=tomedia($case['template']['titlebgimg']);
    }


    $imgcate = pdo_getall(YOUMI_NAME . '_imgcate', ['uniacid' => $uniacid, 'status' => 1]);
    foreach ($imgcate as &$item) {
        $item['img'] = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_img') . ' where uniacid = ' . $uniacid . ' and status = 1 and (`public` = 1 or mid = ' . $this->mid . ') and imgcate_id = ' . $item['id']);
        foreach ($item['img'] as &$img) {
            $img['image'] = tomedia($img['image']);
            unset($img);
        }
        unset($item);
    }

    $musiccate = pdo_getall(YOUMI_NAME . '_musiccate', ['uniacid' => $uniacid, 'status' => 1]);
    foreach ($musiccate as &$item) {
        $item['music'] = pdo_getall(YOUMI_NAME . '_music', ['uniacid' => $uniacid, 'status' => 1, 'musiccate_id' => $item['id']]);
        unset($item);
    }

    $effectscate = pdo_getall(YOUMI_NAME . '_effectscate', ['uniacid' => $uniacid, 'status' => 1]);
    foreach ($effectscate as &$item) {
        $item['effects'] = pdo_getall(YOUMI_NAME . '_effects', ['uniacid' => $uniacid, 'status' => 1, 'effectscate_id' => $item['id']]);
        foreach ($item['effects'] as &$etem) {
            $etem['imgs']=unserialize($etem['imgs']);
            foreach ($etem['imgs'] as &$eitem) {
                $eitem=tomedia($eitem);
            }
            unset($etem);
        }
        unset($item);
    }
    include $this->template('making/' . $module);
    exit();
}

if ($op == 'post') {

    $from = $_GPC['from'];
    $shop = pdo_get(YOUMI_NAME . '_shop', ['mid' => $this->mid]);
    $from['uniacid'] = $uniacid;
    $from['mid'] = $this->mid;
    $from['shop_id'] = $shop['id'];
    $from['case_id'] = intval($_GPC['case_id']);
    $activity = youmi_save_activity($from,$setting['shop_default'],$shop);
    youmi_result($activity['errno'], $activity['message'], $activity['data']);

}

if ($op == 'upimg') {

    unset($file);
    $file['file'] = $_GPC['file'];
    $file['name'] = $_GPC['name'];
    $file['size'] = $_GPC['size'];
//    $file['type'] = $_GPC['type'];
    $file['error'] = 0;

    $res = umi_uploadimg($file, 'image');

    if (!$res['errno']) {

        $data['uniacid'] = $this->uniacid;
        $data['mid'] = $this->mid;
        $data['imgcate_id'] = intval($_GPC['imgcate_id']);
        $data['title'] = trim($file['name']);
        $data['image'] = trim($res['message']);
        $data['public'] = intval($_GPC['public']);
        $data['status'] = 1;
        $data['createtime'] = time();
        $res = pdo_insert(YOUMI_NAME . '_img', $data);
        if ($res) {
            $data['image'] = tomedia($data['image']);
            youmi_result(0, '上传成功', $data);
        }
    }
    youmi_result(0, '上传失败');

}

if ($op == 'del_goods') {

    $activity_id = intval($_GPC['activity_id']);
    $goods_id = intval($_GPC['goods_id']);
    youmi_del_goods($activity_id, $goods_id);

}