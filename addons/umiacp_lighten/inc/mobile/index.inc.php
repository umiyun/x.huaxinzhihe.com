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

$setting_activity = pdo_getall(UMI_NAME . '_' . 'setting', array('uniacid' => $uniacid));
foreach ($setting_activity as $key => &$value) {
    $svalue = unserialize($value['svalue']);
    if ($svalue) {
        foreach ($svalue as $k => $v) {
            if (strstr($k, 'image')) {
                if (is_array($v)) {
                    foreach ($v as &$item) {
                        $item = tomedia($item);
                        unset($item);
                    }
                } else {
                    $v = tomedia($v);
                }
            }
            $setting_activity[$k] = $v;
        }
    }
}

if ($op == 'display') {
    $activity_id = intval($_GPC['activity_id']);
    $cut_id = intval($_GPC['cut_id']);
    $fmember = $this->getMemberInfo($fmid);
    $cut = pdo_get(YOUMI_NAME . '_cut', ['id' => $cut_id]);
    if (!$activity_id) {
        message('请选择对应活动');
    }
    $fmid = intval($cut['mid']);

//    if(empty($_GPC['cut_id'])){
        $cut_my = pdo_get(YOUMI_NAME . '_cut', ['mid' => $this->mid, 'activity_id' => $activity_id]);
        if(!empty($cut_my)&&empty($_GPC['cut_id'])){
            header('Location: ' . $_W['siteroot'] . "app/index.php?i={$this->uniacid}&c=entry&do=index&m=" . YOUMI_NAME."&cut_id=".$cut_my['id'].'&activity_id='.$activity_id);
        }
//    }
    $con = '';

    $activity = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_activity') . ' where uniacid = ' . $uniacid . ' and id = ' . $activity_id);
    $record_list = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_record') .' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id .' and cut_id= '.$cut['id']);

    if(count($record_list)>$activity['lnum'])
    {
        pdo_update(YOUMI_NAME . '_cut',['dltotal'=>$activity['lnum']],['id' => $cut_id]);
    }

    $check_editdlnum=$activity['lnum']-count($record_list);
    for ($i=0;$i<$check_editdlnum;$i++){
        $data_record=array(
            'uniacid'=>$uniacid,
            'shop_id'=>$activity['shop_id'],
            'activity_id'=>$activity['id'],
            'cut_id'=>$cut_id,
            'fmid'=>$cut['mid'],
            'number'=>($i+1),
            'createtime'=>time()
        );
        pdo_insert(YOUMI_NAME . '_' . 'record', $data_record);
        unset($data_record);
    }
    if ($check_editdlnum>0){
        pdo_query('UPDATE '.tablename(YOUMI_NAME . '_cut').' SET times='.$activity['lnum'].'-dltotal WHERE activity_id='.$activity_id);
        $record_list = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_record') .' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id .' and cut_id= '.$cut['id']);

    }
    if ($activity['status'] != 1 || $activity['endtime'] < time()) {
        $activity['status2']=2;
    }else{
        if(empty($cut_my)){//未报名
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
    $_W['page']['title'] = $activity['title'] ? $activity['title'] : '活动宝点亮助力';

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

    $records = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_cut') .' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id .' and status > 0 order by dltotal DESC,createtime ASC');
    foreach ($records as &$record) {

        $record['realname'] =handleAnonymous(  $record['realname']);
        
        unset($record);
    }

    if ($activity['endtime'] < time()) {
        $update['status'] = 2;
        $activity['status'] = 2;
    }

    $update['pv +='] = 1;
    pdo_update(YOUMI_NAME . '_activity', $update, ['id' => $activity_id]);
    pdo_update(UMI_NAME . '_activity', $update, ['shop_id' => $activity['shop_id'], 'module' => YOUMI_NAME, 'activity_id' => $activity_id]);
    if($activity['shop_id']>0){
        $update1['times +='] = 1;
        pdo_update(UMI_NAME . '_shop', $update1, ['id' => $activity['shop_id']]);
    }

    $_share['title'] = $activity['share_title'];
    $_share['imgUrl'] = $activity['share_img'];
    $_share['desc'] = $activity['share_desc'];
    if(empty($setting['cannon_fodder'])){
        $_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index', array('fmid' => $this->mid, 'activity_id' => $activity_id,'cut_id'=>$cut['id']));
    }else{
        $_share['link'] = $setting['cannon_fodder'] . "app/" . $this->createMobileUrl('index', array('fmid' => $this->mid, 'activity_id' => $activity_id,'cut_id'=>$cut['id']));
    }


    if($activity['endtime']<time()&&$activity['qrcode']==0){
        pdo_update(YOUMI_NAME . '_cut', ['islottery' => 1], ['activity_id' => $activity_id]);
        pdo_update(YOUMI_NAME . '_activity', ['qrcode' => 1], ['id' => $activity_id]);
        pdo_query('update ims_'.YOUMI_NAME . '_cut'." set islottery=2 where activity_id=".$activity_id." order by dltotal DESC limit ".$activity['gnum']);
    }



    include $this->template('index');
    exit();
}

if ($op == 'share') {

    $activity_id = intval($_GPC['activity_id']);
    $update['share +='] = 1;
    pdo_update(YOUMI_NAME . '_activity', $update, ['id' => $activity_id]);

}


if ($op == 'sign_up') {
    $activity_id = intval($_GPC['activity_id']);
    $activity = pdo_get(YOUMI_NAME . '_activity', ['id' => $activity_id]);
    if ($activity['status'] != 1 || $activity['endtime'] < time()) {
        youmi_result(1, '活动已下架');
    }
    if ($activity['starttime'] > time()) {
        youmi_result(1, '活动未开始');
    }
    if($activity['shop_id']>0){
        $shop=pdo_get(UMI_NAME . '_shop', ['id' => $activity['shop_id']]);
        if($shop['endtime']<time()&&$shop['times']>$setting['vip_times']){
            $activity['status']=2;
            youmi_result(1, '活动已下架');
        }
    }
    $records = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_cut') .' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id .' and status = "3" ');

    $cut = pdo_get(YOUMI_NAME . '_cut', ['mid' => $this->mid, 'activity_id' => $activity_id]);
    if ($cut) {
        youmi_result(1, '请勿重复报名');
    }

    $cut['activity_id'] = $activity_id;
    $cut['goods_id'] = '';
    $cut['shop_id'] = $activity['shop_id'];
    $cut['uniacid'] = $uniacid;
    $cut['mid'] = $this->mid;
    $cut['oprice'] = '';
    $cut['rprice'] = '';
    $cut['times'] = '';
    $cut['price'] = '';
    $cut['nprice'] = '';
    $cut['status'] = '1';

    $cut['realname'] = trim($_GPC['realname']);
    $cut['avatar'] = $member['avatar'];
    $cut['mobile'] = trim($_GPC['mobile']);
    $cut['userinfo'] = $_GPC['userinfo'];

    $cut['createtime'] = time();
    pdo_insert(YOUMI_NAME . '_cut', $cut);
    $cut_id = pdo_insertid();
//    $data['activity_id'] = $activity_id;
//    $data['goods_id'] = $goods_id;
//    $data['uniacid'] = $uniacid;
//    $data['shop_id'] = $cut['shop_id'];
    $data['cut_id'] = $cut_id;
    for ($i=0;$i<$activity['lnum'];$i++){
        $data_record=array(
            'uniacid'=>$uniacid,
            'shop_id'=>$activity['shop_id'],
            'activity_id'=>$activity['id'],
            'cut_id'=>$cut_id,
            'fmid'=>$cut['mid'],
            'number'=>($i+1),
            'createtime'=>time()
        );
        pdo_insert(YOUMI_NAME . '_' . 'record', $data_record);
//        pdo_debug();
//        die(json_encode($data_record));
        unset($data_record);
    }
    pdo_update(YOUMI_NAME . '_activity', ['participate +=' => 1], ['id' => $activity_id]);
    pdo_update(UMI_NAME . '_activity', ['participate +=' => 1], ['shop_id' => $activity['shop_id'], 'module' => YOUMI_NAME, 'activity_id' => $activity_id]);

    if (!$this->member['mobile']) {
        pdo_update(YOUMI_NAME . '_member', ['realname' => $cut['realname'], 'mobile' => $cut['mobile']], ['mid' => $this->mid]);
    }
    youmi_result(0, '报名成功', $data);

}

if ($op == 'complain') {

    $activity_id = intval($_GPC['activity_id']);
    $activity = pdo_get(YOUMI_NAME . '_activity', ['id' => $activity_id]);
    if ($activity['status'] != 1 || $activity['endtime'] < time()) {
        youmi_result(1, '活动已下架');
    }
    if($activity['shop_id']>0){
        $shop=pdo_get(UMI_NAME . '_shop', ['id' => $activity['shop_id']]);
        if($shop['endtime']<time()&&$shop['times']>$setting['vip_times']){
            $activity['status']=2;
            youmi_result(1, '活动已下架');
        }
    }
    $complain = pdo_get(YOUMI_NAME . '_complain', ['activity_id' => $activity_id,'userinfo'=>$openid]);
    if ($complain) {
        youmi_result(1, '您已提交投诉信息，请勿重复投诉');
    }

    $data['uniacid'] = $uniacid;
    $data['activity_id'] = $activity_id;
    $data['shop_id'] = $activity['shop_id'];
    $data['title'] = $activity['title'];
    $data['reason'] = $_GPC['reason'];
    $data['desc'] = $_GPC['desc'];
    $data['mobile'] = $_GPC['mobile'];
    $data['userinfo'] = $openid;
    $data['createtime'] = TIMESTAMP;
    $status = pdo_insert(YOUMI_NAME . '_' . 'complain', $data);

    youmi_result(0, '投诉'.($status?'成功':'失败'), $data);
}


if ($op == 'cheating') {
    $cut_id = intval($_GPC['cut_id']);
    $activity_id = intval($_GPC['activity_id']);
    $member_helping = pdo_get(YOUMI_NAME . '_record', ['cut_id' => $cut_id,'openid'=>$openid]);
    $activity = pdo_get(YOUMI_NAME . '_activity', ['id' => $activity_id]);
    $all_helping = pdo_getall(YOUMI_NAME . '_record', ['cut_id' => $cut_id,'openid !='=>'']);
    if(count($all_helping)>=$activity['lnum'])
    {
        youmi_result(1, '助力已满，无需再助力~');
    }
    if($member_helping){
        youmi_result(1, '您已经帮好友点亮过啦~');
    }else{
        $data=array(
            'openid'=>$openid,
            'mid'=>$this->mid,
            'avatar'=>$member['avatar'],
            'nickname'=>$member['nickname'],
            'createtime'=>time()
        );
        $status=pdo_update(YOUMI_NAME . '_record',$data,['id' => $_GPC['id']]);
        if($status){
            $dltotal=pdo_getcolumn(YOUMI_NAME . '_cut', ['id'=>$cut_id,'uniacid'=>$uniacid],'dltotal',1);
            $status=pdo_update(YOUMI_NAME . '_cut',['dltotal'=>(intval($dltotal)+1),'dltime'=>time()],['id' => $cut_id,'uniacid' => $uniacid]);
        }
        $data['d_time']=date('y-m-d H:i',time());
        youmi_result(0,'助力成功',$data);
    }
}
//区域限制
if($op == 'regional'){

    $activity_id = intval($_GPC['activity_id']);
    if (!$activity_id) {
        message('请选择对应活动');
    }
    $activity = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_activity') . ' where uniacid = ' . $uniacid . ' and id = ' . $activity_id);
    if(empty($activity['ak'])&&$activity['regional']==2){
        youmi_result(1, '未填写密钥', []);
    }
    load()->func('communication');
    $lng = $_GPC['lng'];
    $lat = $_GPC['lat'];
    $url = 'http://api.map.baidu.com/geocoder/v2/?location='.$lat.','.$lng.'&output=json&pois=1&ak='.$activity['ak'];
    $result = ihttp_get($url);
    $result = json_decode($result['content'],true);
    // var_dump($result);exit;
    $arr = array();
//    $arr['uniacid'] = $_W['uniacid'];
//    $arr['openid'] = $_W['openid'];
//    $arr['rid'] = $_GPC['rid'];
    $arr['lng'] = $lng;
    $arr['lat'] = $lat;
//    $arr['time'] = time();
//    $arr['date'] = date('Ymd');
//    $arr['country'] = $result['result']['addressComponent']['country'];
//    $arr['province'] = $result['result']['addressComponent']['province'];
//    $arr['city'] = $result['result']['addressComponent']['city'];
    $arr['district'] = $result['result']['addressComponent']['district'];
    $arr['address'] = $result['result']['formatted_address'];
    $arr['business'] = $result['result']['business'];
    $arr['r_address']=unserialize($activity['r_address']);

    if(strpos($activity['r_address'],$arr['district']) !== false){//包含
        youmi_result(0, '区域限制，可以报名', $arr);
    }else{//不包含
        youmi_result(1, '您不在活动区域范围内', $arr);
    }
    // var_dump($arr);exit;
}
function handleAnonymous($name){

    $firstStr = mb_substr($name, 0, 1, 'utf-8');
    $lastStr = mb_substr($name, -1, 1, 'utf-8');
    return $firstStr . str_repeat('*', 3) . $lastStr;
}
