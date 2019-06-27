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

    $con = '';
    $activity_id = intval($_GPC['activity_id']);
    if (!$activity_id) {
        message('请选择对应活动');
    }
    $fmid = intval($_GPC['fmid']);
    if ($fmid == $this->mid) {
        $fmid = 0;
    }
    $records = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_cut') .' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id .' and status in (2,3) order by createtime DESC limit 20 ');
    $records2 = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_cut') .' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id .' and status in (2,3) order by createtime DESC');
    $records_count=count($records);
    $fmember = $this->getMemberInfo($fmid);
    $cut = pdo_get(YOUMI_NAME . '_cut', ['mid' => $this->mid, 'activity_id' => $activity_id,'status !='=>'4']);

    $activity = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_activity') . ' where uniacid = ' . $uniacid . ' and id = ' . $activity_id);
    if($activity['participate']<$activity['success']){
        $activity['participate']=$activity['success'];
    }
    if ($activity['status'] != 1 || $activity['endtime'] < time()||($records_count>=$activity['gnum'])) {
        $activity['status2']=2;
    }else{
        if(empty($cut)){//未参团
            $activity['status2']=4;
        }else{
            $order = pdo_get(YOUMI_NAME . '_order', ['mid' => $this->mid, 'activity_id' => $activity_id,'cut_id' => $cut['id'],'status >='=>2]);
            if(empty($order)){//未支付
                $activity['status2']=5;
            }else{
                $activity['status2']=6;
            }
        }
    }


    if($activity['shop_id']>0){
        $shop=pdo_get(UMI_NAME . '_shop', ['id' => $activity['shop_id']]);
        if ($setting_activity['vip_days'] > 0) {
            if ($shop['endtime'] < time()) {
                $activity['status2'] = 2;
                pdo_update(UMI_NAME . '_activity', array('status' => 2), ['shop_id' => $activity['shop_id']]);
                pdo_update(YOUMI_NAME . '_activity', array('status' => 2), ['id' => $activity['activity_id']]);
            }
        } else {
            if ($shop['times'] > $setting_activity['vip_times']) {
                $activity['status2'] = 2;
                pdo_update(UMI_NAME . '_activity', array('status' => 2), ['shop_id' => $activity['shop_id']]);
                pdo_update(YOUMI_NAME . '_activity', array('status' => 2), ['id' => $activity['activity_id']]);
            }
        }
        $activity_umi=pdo_get(UMI_NAME . '_activity', ['activity_id' => $activity['id'],'module' => $_W['current_module']['name']]);
    }
    $_W['page']['title'] = $activity['title'] ? $activity['title'] : '活动宝活动团购';

    $activity['image'] = tomedia($activity['image']);
    $activity['bgimage'] = tomedia($activity['bgimage']);
    $activity['desc_imgs'] = unserialize($activity['desc_imgs']);
    $activity['preferential_val'] = unserialize($activity['preferential_val']);
    foreach ($activity['desc_imgs'] as &$desc_img) {
        $desc_img = tomedia($desc_img);
        unset($desc_img);
    }
    $activity['shop_imgs'] = unserialize($activity['shop_imgs']);
    $activity['effects_imgs'] = unserialize($activity['effects_imgs']);
    $activity['goods_imgs'] = unserialize($activity['goods_imgs']);
    $activity['jieti'] = unserialize($activity['jieti']);
    foreach ($activity['shop_imgs'] as &$desc_img) {
        $desc_img = tomedia($desc_img);
        unset($desc_img);
    }
    foreach ($activity['effects_imgs'] as &$desc_img) {
        $desc_img = tomedia($desc_img);
        unset($desc_img);
    }
    foreach ($activity['goods_imgs'] as &$desc_img) {
        $desc_img = tomedia($desc_img);
        unset($desc_img);
    }
    $activity['userinfo'] = unserialize($activity['userinfo']);


    foreach ($records as &$record) {
        $strlen = mb_strlen($record['realname'], 'utf-8');
        $firstStr = mb_substr($record['realname'], 0, 1, 'utf-8');
        $lastStr = mb_substr($record['realname'], -1, 1, 'utf-8');
        if ($record['mid']!=-1){


        $record['avatar'] = $this->getMemberInfo($record['mid'])['avatar'];
        }
        $record['realname'] = $firstStr . str_repeat('*', 3) . $lastStr;

        $record['tips'] = $record['realname'] . ' 参团成功';

        unset($record);
    }
    foreach ($records2 as &$record) {
        $strlen = mb_strlen($record['realname'], 'utf-8');
        $firstStr = mb_substr($record['realname'], 0, 1, 'utf-8');
        $lastStr = mb_substr($record['realname'], -1, 1, 'utf-8');

        $record['realname'] = $firstStr . str_repeat('*', 3) . $lastStr;


        unset($record);
    }

    if ($activity['endtime'] < time()) {
        $update['status'] = 2;
        $activity['status2'] = 2;
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
        $_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index', array('fmid' => $this->mid, 'activity_id' => $activity_id));
    }else{
        $_share['link'] = $setting['cannon_fodder'] . "app/" . $this->createMobileUrl('index', array('fmid' => $this->mid, 'activity_id' => $activity_id));
    }


    //    当前价格
    if($records_count>=intval($activity['jieti']['num'][0])){
        foreach ($activity['jieti']['num'] as $key => $jtem){
            if($activity['jieti']['num'][$key]>0){
                if(intval($activity['jieti']['num'][$key+1])<=0){
                    if($records_count>=intval($activity['jieti']['num'][$key])){
                        $activity['nprice']=$activity['jieti']['price'][$key];
                        $activity['njieti']=$key;
                        break;
                    }
                }else{
                    if($records_count>=intval($activity['jieti']['num'][$key])&&$records_count<intval($activity['jieti']['num'][$key+1])){
                        $activity['nprice']=$activity['jieti']['price'][$key];
                        break;
                    }
                }
            }
        }
    }else{
        $activity['nprice']=$activity['oprice'];
        $activity['njieti']=-1;
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
    if((intval($activity['gnum'])<=count($records))){
        $activity['status2']=2;
        youmi_result(1, '参团人数已满');
    }
//    $goods_id = intval($_GPC['goods_id']);
    $cut = pdo_get(YOUMI_NAME . '_cut', ['mid' => $this->mid, 'activity_id' => $activity_id, 'status !=' => '4']);
    if ($cut) {
        youmi_result(1, '请勿重复参团',$cut);
    }
//    $goods = pdo_get(YOUMI_NAME . '_goods', ['uniacid' => $uniacid, 'status' => 1, 'activity_id' => $activity_id]);

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
    $cut['mobile'] = trim($_GPC['mobile']);
    $cut['userinfo'] = $_GPC['userinfo'];

    $cut['createtime'] = time();
    pdo_insert(YOUMI_NAME . '_cut', $cut);
    $cut_id = pdo_insertid();
    $data['activity_id'] = $activity_id;
    $data['goods_id'] = $goods_id;
    $data['uniacid'] = $uniacid;
    $data['shop_id'] = $cut['shop_id'];
    $data['cut_id'] = $cut_id;
    $data['mid'] = $this->mid;
    $data['fmid'] = $cut['mid'];
    $data['oprice'] = floatval($cut['oprice']);
    $data['nprice'] = floatval($cut['nprice']);
    $data['price'] = $cut['price'];
    $data['nprice'] = floatval($cut['oprice']) - $price;
    $data['createtime'] = time();
//    pdo_insert(YOUMI_NAME . '_record', $data);

    pdo_update(YOUMI_NAME . '_activity', ['participate +=' => 1], ['id' => $activity_id]);
    pdo_update(UMI_NAME . '_activity', ['participate +=' => 1], ['shop_id' => $activity['shop_id'], 'module' => YOUMI_NAME, 'activity_id' => $activity_id]);

    if (!$this->member['mobile']) {
        pdo_update(YOUMI_NAME . '_member', ['realname' => $cut['realname'], 'mobile' => $cut['mobile']], ['mid' => $this->mid]);
    }
//        $data['status']='1';
//
//        $tid = date('YmdHis') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
//
//        //插入订单数据
//        $order['uniacid'] = $uniacid;
//        $order['mid'] = $this->mid;
//        $order['cut_id'] = $cut_id;
////        $order['goods_id'] = $cut['goods_id'];
//        $order['activity_id'] = $cut['activity_id'];
//        $order['shop_id'] = $cut['shop_id'];
//        $order['title'] = $activity['title'];
//        $order['ordersn'] = $tid;
//        $order['tid'] = $tid;
//        $order['status'] = 2;
//        $order['createtime'] = TIMESTAMP;
//        $status = pdo_insert(YOUMI_NAME . '_' . 'order', $order);
//        $order['id'] = pdo_insertid();

        youmi_result(0, '提交成功', $data);
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
