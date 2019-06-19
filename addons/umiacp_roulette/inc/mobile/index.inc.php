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

$start_time = strtotime(date('Y-m-d')); //当天开始时间
$end_time = strtotime(date('Y-m-d') . ' +1 day');//当天结束时间
if ($op == 'display') {


    $con = '';
    $activity_id = intval($_GPC['activity_id']);
    if (!$activity_id) {
        message('请选择对应活动');
    }


    $reward = pdo_get(YOUMI_NAME . '_reward', ['mid' => $this->mid, 'activity_id' => $activity_id]);
    $activity = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_activity') . ' where uniacid = ' . $uniacid . ' and id = ' . $activity_id);

    if(time()>$reward['day']&&$reward){//每天重置次数
        pdo_update(YOUMI_NAME . '_reward', ['use'=>$activity['daynum'],'day'=>$end_time], ['id' => $reward['id']]);
        $reward['use']=$activity['daynum'];
    }


    if ($activity['status'] != 1 || $activity['endtime'] < time()) {
        $activity['status2'] = 2;
    }
    if ($activity['shop_id'] > 0) {
        $shop = pdo_get(UMI_NAME . '_shop', ['id' => $activity['shop_id']]);
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
    } else {
        message('数据错误');
    }
    $_W['page']['title'] = $activity['title'] ? $activity['title'] : '活动宝大转盘抽奖';

    if($_GPC['fmid']&&empty($_SESSION['helping'])){//助力逻辑
        $record=pdo_get(YOUMI_NAME . '_record', ['fmid' => $this->mid,'mid' => $_GPC['fmid'],'activity_id' => $activity_id,'createtime >' => $start_time]);
        $record_today= pdo_fetchcolumn('select count(*) from ' . tablename(YOUMI_NAME . '_record') . ' where uniacid = ' . $uniacid . ' and fmid = ' . $_GPC['fmid'] .' and createtime>= '.$start_time.' and createtime <='.$end_time );
        if(empty($record)&&$record_today<$activity['sharenum']&&$this->mid!=$_GPC['fmid']){
            pdo_insert(YOUMI_NAME . '_record', [
                'fmid' => $_GPC['fmid'],
                'mid' => $this->mid,
                'activity_id' => $activity_id,
                'createtime' => time(),
                'uniacid' => $uniacid,
                'shop_id' => $activity['shop_id']
            ]);
            pdo_update(YOUMI_NAME . '_reward', ['use +='=>1], ['activity_id' => $activity_id]);
            $_SESSION['helping']=1;
        }
    }



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
    foreach ($activity['shop_imgs'] as &$desc_img) {
        $desc_img = tomedia($desc_img);
        unset($desc_img);
    }
    foreach ($activity['effects_imgs'] as &$desc_img) {
        $desc_img = tomedia($desc_img);
        unset($desc_img);
    }
    $activity['userinfo'] = unserialize($activity['userinfo']);

    $prizes = pdo_getall(YOUMI_NAME . '_' . 'activity_prize', array('uniacid' => $uniacid, 'activity_id' => $activity_id, 'status >' => -1));

    $records = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_prize_log') . ' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id . ' and status = 1 ');
    $records_all = pdo_fetchcolumn('select count(*) from ' . tablename(YOUMI_NAME . '_prize_log') . ' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id );
    $records_member = pdo_fetchcolumn('select count(*) from ' . tablename(YOUMI_NAME . '_reward') . ' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id );
    foreach ($records as &$record) {
        $strlen = mb_strlen($record['realname'], 'utf-8');
        $firstStr = mb_substr($record['realname'], 0, 1, 'utf-8');
        $lastStr = mb_substr($record['realname'], -1, 1, 'utf-8');

        $record['realname'] = $firstStr . str_repeat('*', 3) . $lastStr;

        $record['prize'] = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_activity_prize') . ' where uniacid = ' . $uniacid . ' and id = ' . $record['prize_id']);
        $record['tips'] = $record['realname'] . '  ' . date('Y-m-d H:i',$record['createtime']) .'抽中'.$record['prize']['title'];

        unset($record);
    }

    if ($activity['endtime'] < time()) {
        $update['status'] = 2;
        $activity['status'] = 2;
    }

    $update['pv +='] = 1;
    pdo_update(YOUMI_NAME . '_activity', $update, ['id' => $activity_id]);
    pdo_update(UMI_NAME . '_activity', $update, ['shop_id' => $activity['shop_id'], 'module' => YOUMI_NAME, 'activity_id' => $activity_id]);
    if ($activity['shop_id'] > 0) {
        $update1['times +='] = 1;
        pdo_update(UMI_NAME . '_shop', $update1, ['id' => $activity['shop_id']]);
        $activity_umi=pdo_get(UMI_NAME . '_activity', ['activity_id' => $activity['id'],'module' => $_W['current_module']['name']]);
    }
    $_share['title'] = $activity['share_title'];
    $_share['imgUrl'] = $activity['share_img'];
    $_share['desc'] = $activity['share_desc'];
    if (empty($setting['cannon_fodder'])) {
        $_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index', array('fmid' => $this->mid, 'activity_id' => $activity_id));
    } else {
        $_share['link'] = $setting['cannon_fodder'] . "app/" . $this->createMobileUrl('index', array('fmid' => $this->mid, 'activity_id' => $activity_id));
    }

    include $this->template('index');
    exit();
}

if ($op == 'share') {

    $activity_id = intval($_GPC['activity_id']);
    $update['share +='] = 1;
    pdo_update(YOUMI_NAME . '_activity', $update, ['id' => $activity_id]);

}
if ($op == 'page_complain') {
    $_W['page']['title'] = '投诉';
    $activity_id = intval($_GPC['activity_id']);

    include $this->template('complain');
    exit();
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
    if ($activity['shop_id'] > 0) {
        $shop = pdo_get(UMI_NAME . '_shop', ['id' => $activity['shop_id']]);
        if ($shop['endtime'] < time() && $shop['times'] > $setting['vip_times']) {
            $activity['status'] = 2;
            youmi_result(1, '活动已下架');
        }
    }


    $reward = pdo_get(YOUMI_NAME . '_reward', ['uniacid' => $uniacid, 'openid' => $openid, 'activity_id' => $activity_id]);
    if (!$reward) {
        $reward['realname'] = trim($_GPC['realname']);
        $reward['mobile'] = trim($_GPC['mobile']);
        $reward['userinfo'] = $_GPC['userinfo'];
        $reward['uniacid'] = $uniacid;
        $reward['openid'] = $openid;
        $reward['use'] = $activity['daynum'];
        $reward['day'] = $start_time;
        $reward['status'] = 1;
        $reward['activity_id'] = $activity_id;
        $reward['nickname'] = $member['nickname'];
        $reward['avatar'] = $member['avatar'];
        $reward['mid'] = $this->mid;
        $reward['createtime'] = time();
        pdo_insert(YOUMI_NAME . '_reward', $reward);
        $reward['id'] = pdo_insertid();
        $update2['participate +='] = 1;
        pdo_update(YOUMI_NAME . '_activity', $update2, ['id' => $activity_id]);
    } else {
        youmi_result(1, '请勿重复报名');
//        if($reward['day']<$start_time){
//            pdo_update(YOUMI_NAME . '_reward', ['use'=>$activity['daynum'],'day'=>$start_time,'used'=>0], ['id' => $reward['id']]);
//        }else{
//            if ($reward['use'] <= 0) {
//                youmi_result(1, '今日机会已全部用完，明天再来吧~~~');
//            }
//        }
    }
    youmi_result(0, '报名成功', $reward);

}

if ($op == 'complain') {

    $activity_id = intval($_GPC['activity_id']);
    $activity = pdo_get(YOUMI_NAME . '_activity', ['id' => $activity_id]);
    if ($activity['status'] != 1 || $activity['endtime'] < time()) {
        youmi_result(1, '活动已下架');
    }
    if ($activity['shop_id'] > 0) {
        $shop = pdo_get(UMI_NAME . '_shop', ['id' => $activity['shop_id']]);
        if ($shop['endtime'] < time() && $shop['times'] > $setting['vip_times']) {
            $activity['status'] = 2;
            youmi_result(1, '活动已下架');
        }
    }
    $complain = pdo_get(YOUMI_NAME . '_complain', ['activity_id' => $activity_id, 'userinfo' => $openid]);
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

    youmi_result(0, '投诉' . ($status ? '成功' : '失败'), $data);
}

if ($op == 'games') {

    if (!$_W['ispost'] || !$_W['isajax']) {
        youmi_result(1, '非法请求！');
    }

    $activity_id = intval($_GPC['activity_id']);
    $activity = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_activity') . ' where uniacid = ' . $uniacid . ' and id = ' . $activity_id);

    if ($activity['status'] != 1 || $activity['endtime'] < time()) {
        youmi_result(1, '活动已结束');
    }

    $count = pdo_fetchcolumn('select count(*) from ' . tablename(YOUMI_NAME . '_prize_log') . ' where uniacid = ' . $uniacid . ' and openid = ' . $openid . '  and createtime >= ' . $start_time . ' and createtime <= ' . $end_time);
    if ($count >= $activity['allnum']) {
        youmi_result(1, '今日机会已全部用完，明天再来吧~~~');
    }
    $reward = pdo_get(YOUMI_NAME . '_reward', ['uniacid' => $uniacid, 'openid' => $openid, 'activity_id' => $activity_id]);
    if ($reward['use'] <= 0) {
        youmi_result(1, '您的机会已用完，分享好友可获得更多机会。');
    }

//    $prize_count = pdo_fetchcolumn('select count(*) from ' . tablename(YOUMI_NAME . '_prize_log') . ' where uniacid = ' . $uniacid . ' and openid = ' . $openid . '  and status = 1 ');
    $prize_count = pdo_getall(YOUMI_NAME . '_prize_log', array('uniacid' => $uniacid, 'openid' => $openid,'activity_id' => $activity_id, 'status' => 1));
//    pdo_debug();
//    die(json_encode(count($prize_count)));
    $res = lottery($activity_id, $member, $reward,(count($prize_count)>=$activity['allnum']?true:false));
    die(json_encode($res));

}

function lottery($activity_id, $member, $reward,$prize_allnum)
{

    $prizes = pdo_getall(YOUMI_NAME . '_' . 'activity_prize', array('uniacid' => $member['uniacid'], 'activity_id' => $activity_id, 'status >' => -1), '', '', 'createtime desc', '8');
    $prize_num = [];
    foreach ($prizes as &$prize) {
        if ($prize['num'] > 0 || $prize['num'] == -1) {
            $prize_num[$prize['id']] = $prize['odds'];
        }
        $prize['image'] = tomedia($prize['image']);
        unset($prize);
    }

    $item = probability($prize_num,$prize_allnum);
    $log['uniacid'] = $member['uniacid'];
    $log['openid'] = $member['openid'];
    $log['mid'] = $member['mid'];
    $log['activity_id'] = $activity_id;
    $log['realname'] = trim($reward['realname']);
    $log['mobile'] = trim($reward['mobile']);
    $log['prize_id'] = $item['id'];
    $log['title'] = $item['title'];
    $log['image'] = tomedia($item['image']);
    $log['status'] = $item['status'];
    $log['createtime'] = TIMESTAMP;
    pdo_insert(YOUMI_NAME . '_' . 'prize_log', $log);
    pdo_update(YOUMI_NAME . '_' . 'reward', ['use -=' => 1, 'used +=' => 1], ['id' => $reward['id']]);
    if($item['status']==1) {
        $update3['success +='] = 1;
        pdo_update(YOUMI_NAME . '_activity', $update3, ['id' => $activity_id]);
    }
    return ['errno' => 0, 'message' => $item['message'], 'log' => $log];
}

function probability($prize_num,$prize_allnum)
{

    $prize_id = get_rand($prize_num);
    $prize = pdo_get(YOUMI_NAME . '_' . 'activity_prize', array('id' => $prize_id));
    if ($prize['status'] == 0||$prize_allnum) {
        $prize['message'] = '谢谢您参与，继续加油！';
        $prize = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_activity_prize') . ' where status = 0 and activity_id = ' . $prize['activity_id'].' order by rand() limit 1 ');
    } else {
        $prize['message'] = '恭喜您，抽中了' . $prize['title'] . '！';
    }
    if ($prize['num'] > 0&&!$prize_allnum) {
        pdo_update(YOUMI_NAME . '_' . 'activity_prize', ['num -=' => 1], array('id' => $prize_id));
    }
    return $prize;
}

/**
 * 概率计算
 *
 * @param unknown $proArr
 * @return Ambigous <string, unknown>
 */
function get_rand($proArr)
{
    $result = '';
    // 概率数组的总概率精度
    $proSum = array_sum($proArr);
    ksort($proArr);
    // 概率数组循环
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(1, $proSum); // 抽取随机数
        if ($randNum <= $proCur) {
            $result = $key; // 得出结果
//            die(json_encode(['key'=>$key,'num'=>$randNum,'cur'=>$proCur,'proSum'=>$proSum,'a'=>$proArr]));
            break;
        } else {
            $proSum -= $proCur;
        }
    }
    unset($proArr);
    return $result;
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
