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

$today=time();
$t = time();
$start_time = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t)); //当天开始时间
$end_time = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));//当天结束时间
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
    $fmember = $this->getMemberInfo($fmid);
    $cut = pdo_get(YOUMI_NAME . '_cut', ['mid' => $this->mid, 'activity_id' => $activity_id,'status !='=>'4']);
    $activity = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_activity') . ' where uniacid = ' . $uniacid . ' and id = ' . $activity_id);

    if($activity['shop_id']>0){
        $shop=pdo_get(UMI_NAME . '_shop', ['id' => $activity['shop_id']]);
        if($shop['endtime']<time()&&$shop['times']>$setting['vip_times']){
            $activity['status2']=2;
            pdo_update(UMI_NAME . '_activity',array('status'=>2),['shop_id' => $activity['shop_id']]);
            pdo_update(YOUMI_NAME . '_activity',array('status'=>2),['id' => $activity['activity_id']]);
        }
        $activity_umi=pdo_get(UMI_NAME . '_activity', ['activity_id' => $activity['id'],'module' => $_W['current_module']['name']]);
    }

    $_W['page']['title'] = $activity['title'] ? $activity['title'] : '活动宝10秒挑战';

    $activity['logo'] = tomedia($activity['logo']);
    $activity['titleimg'] = tomedia($activity['titleimg']);
    $activity['bgimage'] = tomedia($activity['bgimage']);
    $activity['bgimage2'] = tomedia($activity['bgimage2']);
    $activity['title2'] = tomedia($activity['title2']);
    $activity['desc2'] = tomedia($activity['desc2']);

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
        $_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index', array('fopenid' => $openid,'op' => 'helping', 'activity_id' => $activity_id));
    }else{
        $_share['link'] = $setting['cannon_fodder'] . "app/" . $this->createMobileUrl('index', array('fopenid' => $openid,'op' => 'helping', 'activity_id' => $activity_id));
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
    $cut = pdo_get(YOUMI_NAME . '_cut', ['mid' => $this->mid, 'activity_id' => $activity_id]);
    if ($cut) {
        youmi_result(1, '请勿重复报名');
    }
    $goods = pdo_get(YOUMI_NAME . '_goods', ['uniacid' => $uniacid, 'status' => 1, 'activity_id' => $activity_id]);

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
    $data['cut_id'] = $cut_id;

    pdo_update(YOUMI_NAME . '_activity', ['participate +=' => 1], ['id' => $activity_id]);
    pdo_update(UMI_NAME . '_activity', ['participate +=' => 1], ['shop_id' => $activity['shop_id'], 'module' => YOUMI_NAME, 'activity_id' => $activity_id]);

    if (!$this->member['mobile']) {
        pdo_update(YOUMI_NAME . '_member', ['realname' => $cut['realname'], 'mobile' => $cut['mobile']], ['mid' => $this->mid]);
    }
    $data['status']='2';
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
if ($op == 'game') {
    $activity_id = intval($_GPC['activity_id']);
    $activity = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_activity') . ' where uniacid = ' . $uniacid . ' and id = ' . $activity_id);
    $_W['page']['title'] = $activity['title'] ? $activity['title'] : '活动宝10秒挑战';
    $_share['title'] = $activity['share_title'];
    $_share['imgUrl'] = $activity['share_img'];
    $_share['desc'] = $activity['share_desc'];
    if(empty($setting['cannon_fodder'])){
        $_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index', array('fopenid' => $openid,'op' => 'helping', 'activity_id' => $activity_id));
    }else{
        $_share['link'] = $setting['cannon_fodder'] . "app/" . $this->createMobileUrl('index', array('fopenid' => $openid,'op' => 'helping', 'activity_id' => $activity_id));
    }

    $activity['rule3'] = tomedia($activity['rule3']);
    $activity['bgimage3'] = tomedia($activity['bgimage3']);
    $activity['bgimage_my'] = tomedia($activity['bgimage_my']);
    $activity['title3'] = tomedia($activity['title3']);
    $activity['desc3'] = tomedia($activity['desc3']);
    $activity['userinfo'] = unserialize($activity['userinfo']);

    include $this->template('game');
    exit();

}

if ($op == 'list') {
    $activity_id = intval($_GPC['activity_id']);
    $activity = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_activity') . ' where uniacid = ' . $uniacid . ' and id = ' . $activity_id);
    $_W['page']['title'] = $activity['title'] ? $activity['title'] : '活动宝10秒挑战';
    $activity['bgimage4']=tomedia($activity['bgimage4']);
    $activity['bgimage_my']=tomedia($activity['bgimage_my']);

    if($activity['prize_status']==2){
        $prize_logs= pdo_getall(YOUMI_NAME . '_prize_log', ['uniacid' => $uniacid, 'openid' => $openid, 'activity_id' => $activity_id, 'status >' => 0]);

    }

    $count = pdo_fetchcolumn('select count(openid) from ' . tablename(YOUMI_NAME . '_reward') . ' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id);
    $list = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_reward') . ' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id . ' and diff > -1 order by diff asc, createtime asc limit 8 ');

    $no = pdo_fetchcolumn('select count(openid) from ' . tablename(YOUMI_NAME . '_reward') . ' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id . ' and diff > -1 and diff < ' . $reward['diff']);

    $ch = '';
    if ($reward) {
        if ($reward['diff'] >= 0 && $reward['diff'] < 0.02) {
            $ch = '魔都时间超能者';
        } elseif ($reward['diff'] < 0.1) {
            $ch = '控时异能者';
        } elseif ($reward['diff'] < 0.2) {
            $ch = '时间达人';
        } else {
            $word = ['光阴的故事', '时间煮雨', '时间飞船', '时间美术馆'];
            $rand = mt_rand(0, 3);
            $ch = $word[$rand];
        }
    } else {
        $word = ['光阴的故事', '时间煮雨', '时间飞船', '时间美术馆'];
        $rand = mt_rand(0, 3);
        $ch = $word[$rand];
    }
    $_share['title'] = '不服来战，' . $_W['fans']['nickname'] . '在“10秒挑战赛”游戏中获得“' . $ch . '”称号，排名第' . ($no + 1) . '名！';


    include $this->template('list');
    exit();

}
if ($op == 'envelope') {
    $day = intval($_GPC['day']);
    $reward = pdo_get(YOUMI_NAME . '_reward', ['uniacid' => $uniacid, 'openid' => $openid, 'day' => $day]);
    include $this->template('envelope');
    exit();
}
if ($op == 'helping') {
    $activity_id = intval($_GPC['activity_id']);
    $activity = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_activity') . ' where uniacid = ' . $uniacid . ' and id = ' . $activity_id);
    $_W['page']['title'] = $activity['title'] ? $activity['title'] : '活动宝10秒挑战';
    $activity['title'] = $activity['title'];
    $activity['logo'] = tomedia($activity['logo']);
    $activity['s_image'] = tomedia($activity['s_image']);
    $activity['bgimage_share'] = tomedia($activity['bgimage_share']);
    $activity['desc'] = $activity['desc_val'];
    if(empty($setting['cannon_fodder'])){
        $_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index', array('fopenid' => $openid,'op' => 'helping', 'activity_id' => $activity_id));
    }else{
        $_share['link'] = $setting['cannon_fodder'] . "app/" . $this->createMobileUrl('index', array('fopenid' => $openid,'op' => 'helping', 'activity_id' => $activity_id));
    }
    include $this->template('helping');
    exit();
}
/**
 * 开始游戏
 * do   index   op  start_game
 */
if ($op == 'start_game') {
    $activity_id = intval($_GPC['activity_id']);
    $activity = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_activity') . ' where uniacid = ' . $uniacid . ' and id = ' . $activity_id);

    if ($activity['status'] != 1 || $activity['endtime'] < time()) {
        youmi_result(1, '活动已结束');
    }

    $member_a= pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_cut') . ' where uniacid = ' . $uniacid.' and activity_id = '.$activity_id);
    if(!$member_a){
        youmi_result(2, '请填写用户信息');
    }

    $count = pdo_fetchcolumn('select count(*) from ' . tablename(YOUMI_NAME . '_recording') . ' where uniacid = ' . $uniacid . ' and openid = ' . $openid .'  and createtime >= ' . $start_time.' and createtime <= '.$end_time);
    if ($count >= $activity['allnum']) {
        youmi_result(1, '今日机会已全部用完，明天再来吧~~~');
    }
    $reward = pdo_get(YOUMI_NAME . '_reward', ['uniacid' => $uniacid, 'openid' => $openid, 'activity_id' => $activity_id]);
    if (!$reward) {
        $reward['uniacid'] = $uniacid;
        $reward['openid'] = $openid;
        $reward['use'] = $activity['daynum'];
        $reward['day'] = $today;
        $reward['status'] = 1;
        $reward['activity_id'] = $activity_id;
        $reward['nickname'] = $member['nickname'];
        $reward['avatar'] = $member['avatar'];
        $reward['mid'] = $this->mid;
        $reward['send_name'] = $activity['send_name'];
        $reward['wishing'] = $activity['wishing'];
        $reward['act_name'] = $activity['act_name'];
        $reward['createtime'] = time();
        pdo_insert(YOUMI_NAME . '_reward', $reward);
        $reward['id'] = pdo_insertid();
    }else{
        if($reward['day']<$start_time){
            pdo_update(YOUMI_NAME . '_reward', ['use'=>$activity['daynum'],'day'=>$today,'used'=>0], ['id' => $reward['id']]);
        }else{
            if ($reward['use'] <= 0) {
                youmi_result(1, '您的机会已用完，分享好友可获得更多机会。');
            }
        }
    }

    youmi_result(0);
}

/**
 * 游戏结果
 * do   index   op  games
 */
if ($op == 'games') {

    if (!$_W['ispost'] || !$_W['isajax']) {
        youmi_result(1, '非法请求！');
    }

    $activity_id = intval($_GPC['activity_id']);
    $activity = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_activity') . ' where uniacid = ' . $uniacid . ' and id = ' . $activity_id);


    $_share['title'] = $activity['share_title'];
    $_share['imgUrl'] = $activity['share_img'];
    $_share['desc'] = $activity['share_desc'];
    if (empty($setting['cannon_fodder'])) {
        $_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index', array('fopenid' => $openid, 'op' => 'helping', 'activity_id' => $activity_id));
    } else {
        $_share['link'] = $setting['cannon_fodder'] . "app/" . $this->createMobileUrl('index', array('fopenid' => $openid, 'op' => 'helping', 'activity_id' => $activity_id));
    }


    if ($activity['status'] != 1 || $activity['endtime'] < time()) {
        youmi_result(1, '活动已结束');
    }

    $count = pdo_fetchcolumn('select count(*) from ' . tablename(YOUMI_NAME . '_recording') . ' where uniacid = ' . $uniacid . ' and openid = ' . $openid . '  and activity_id = ' . $activity_id);
    if ($count >= $activity['allnum']) {
        youmi_result(1, '今日机会已全部用完，明天再来吧~~~');
    }
    $reward = pdo_get(YOUMI_NAME . '_reward', ['uniacid' => $uniacid, 'openid' => $openid, 'activity_id' => $activity_id]);
    if ($reward['use'] <= 0) {
        youmi_result(1, '您的机会已用完，分享好友可获得更多机会。');
    }


        $money = pdo_fetchcolumn('select sum(reward_money) from ' . tablename(YOUMI_NAME . '_reward') . ' where activity_id = ' . $activity_id);
        $reward_type = pdo_fetchcolumn('select count(*) from ' . tablename(YOUMI_NAME . '_reward') . ' where reward_type = 2 ');

        $recording = [];
        $recording['uniacid'] = $uniacid;
        $recording['openid'] = $openid;
        $recording['sj'] = floatval(base64_decode(trim($_GPC['gtalk'])));
        $recording['diff'] = abs(10 - $recording['sj']);
        $recording['activity_id'] = $activity_id;
        $recording['createtime'] = time();
        $recording['status'] = 0;
//    die(json_encode(['$money'=>$money,'$reward_type'=>$reward_type,'$activity'=>$activity,'$recording'=>$recording,'$umi_member'=>$umi_member]));
        youmi_internal_log('1', ['$money' => $money, '$reward_type' => $reward_type, '$activity' => $activity, '$recording' => $recording, '$umi_member' => $umi_member], 3);

    if ($activity['prize_status'] == 1) {
        $ran = Math . ceil(Math . random() * 100);
        if ($money >= 1000 && $reward_type >= 10) {
            $recording['status'] = 1;
        } elseif (floatval($recording['diff']) < floatval($activity['diff']) && intval($reward['reward_type']) == 0 && $ran <= $activity['random']) {
            $recording['status'] = 2;
            $res = lottery($uniacid, $openid, $activity_id, $setting);
        } else if ($reward['reward_type'] == 0) {
            $recording['status'] = 0;
        }

        pdo_insert(YOUMI_NAME . '_recording', $recording);
        if (floatval($reward['diff']) == -1 || floatval($reward['diff']) > $recording['diff']) {
            $update['diff'] = $recording['diff'];
            $update['good'] = $recording['sj'];
        }
        $reward['use'] = $update['use -='] = 1;
        $reward['used'] = $update['used +='] = 1;
        pdo_update(YOUMI_NAME . '_reward', $update, ['id' => $reward['id']]);

        $reward = pdo_get(YOUMI_NAME . '_reward', ['uniacid' => $uniacid, 'openid' => $openid, 'activity_id' => $activity_id]);

        $count = pdo_fetchcolumn('select count(openid) from ' . tablename(YOUMI_NAME . '_reward') . ' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id);
        $no = pdo_fetchcolumn('select count(openid) from ' . tablename(YOUMI_NAME . '_reward') . ' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id . ' and diff > -1 and diff < ' . $reward['diff']);

        $reward['sj'] = $reward['good'];
        $reward['no'] = $no + 1;
        $reward['scale'] = number_format(($count - $no) / $count * 100, 2);

        $data['recording'] = $reward;
        $data['record'] = $recording;
        $data['prize_status'] = 1;
        $data['res'] = $res;

        if ($recording['status'] != 2 || $reward['reward_type'] == 0) {
            youmi_result(0, '很遗憾您未中奖' . ($reward['used'] < $activity['allnum'] ? ($reward['use'] > 0 ? '，您还有' . $reward['use'] . '次机会，分享好友可获得更多机会。' : '您的机会已用完，分享好友可获得更多机会。') : '，今日机会已全部用完。'), $data);
        }

        youmi_result(0, $reward['used'] < $activity['allnum'] ? ($reward['use'] > 0 ? '您还有' . $reward['use'] . '次机会，分享好友可获得更多机会。' : '您的机会已用完，分享好友可获得更多机会。') : '今日机会已全部用完。', $data);

    }else{

        if (floatval($reward['diff']) == -1 || floatval($reward['diff']) > $recording['diff']) {
            $update['diff'] = $recording['diff'];
            $update['good'] = $recording['sj'];
        }
        $reward['use'] = $update['use -='] = 1;
        $reward['used'] = $update['used +='] = 1;
        pdo_update(YOUMI_NAME . '_reward', $update, ['id' => $reward['id']]);

        $reward = pdo_get(YOUMI_NAME . '_reward', ['uniacid' => $uniacid, 'openid' => $openid, 'activity_id' => $activity_id]);

        $count = pdo_fetchcolumn('select count(openid) from ' . tablename(YOUMI_NAME . '_reward') . ' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id);
        $no = pdo_fetchcolumn('select count(openid) from ' . tablename(YOUMI_NAME . '_reward') . ' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id . ' and diff > -1 and diff < ' . $reward['diff']);

        $reward['sj'] = $reward['good'];
        $reward['no'] = $no + 1;
        $reward['scale'] = number_format(($count - $no) / $count * 100, 2);

        $data['recording'] = $reward;
        $data['record'] = $recording;
        $data['prize_status'] = 2;
//        $data['res'] = $res;
        if(floatval($recording['diff']) > floatval($activity['diff'])){
            $recording['status'] = 1;
            pdo_insert(YOUMI_NAME . '_recording', $recording);
            youmi_result(0, '很遗憾您的成绩未达标，继续加油',$data);
        }else {
            $res = lottery2($uniacid, $openid, $activity_id, $this->mid, $data);
            $recording['status'] = 2;
            pdo_insert(YOUMI_NAME . '_recording', $recording);
            die(json_encode($res));
        }

    }
}

/**
 * 最好成绩
 * do   index   op  goods
 */
if ($op == 'goods') {
    $activity_id = intval($_GPC['activity_id']);
    $activity = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_activity') . ' where uniacid = ' . $uniacid . ' and id = ' . $activity_id);


    $reward = pdo_get(YOUMI_NAME . '_reward', ['uniacid' => $uniacid, 'openid' => $openid, 'activity_id' => $activity_id]);

    $count = pdo_fetchcolumn('select count(openid) from ' . tablename(YOUMI_NAME . '_reward') . ' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id);
    $no = pdo_fetchcolumn('select count(openid) from ' . tablename(YOUMI_NAME . '_reward') . ' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id . ' and diff > -1 and diff < ' . $reward['diff']);

    $reward['sj'] = $reward['good'];
    $reward['no'] = $no + 1;
    $reward['scale'] = number_format(($count - $no) / $count * 100, 2);

    youmi_result(0, '', $reward);
}


/**
 * 助力
 * do   index   op  boost
 */
if ($op == 'boost') {
    $activity_id = intval($_GPC['activity_id']);
    $fopenid = trim($_GPC['fopenid']);
    $activity = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_activity') . ' where uniacid = ' . $uniacid . ' and id = ' . $activity_id);

    if ($fopenid &&$fopenid!=$openid&& $activity_id) {

        $boost = pdo_getall(YOUMI_NAME . '_boost', ['uniacid' => $uniacid, 'openid' => $openid, 'fopenid' => $fopenid, 'activity_id' => $activity_id, 'createtime >=' => $start_time]);

        if(empty($boost)){
            $boost_all = pdo_getall(YOUMI_NAME . '_boost', ['uniacid' => $uniacid, 'fopenid' => $fopenid, 'activity_id' => $activity_id, 'createtime >=' => $start_time]);
            if (count($boost_all)<$activity['daynum']) {
                $boost['uniacid'] = $uniacid;
                $boost['openid'] = $openid;
                $boost['fopenid'] = $fopenid;
                $boost['day'] = $today;
                $boost['activity_id'] = $activity_id;
                $boost['status'] = 1;
                $boost['createtime'] = time();
                pdo_insert(YOUMI_NAME . '_boost', $boost);

                $reward = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_reward') . ' where uniacid = :uniacid and openid = :openid and activity_id = :activity_id ', [':uniacid' => $uniacid, ':openid' => $fopenid, ':activity_id' => $activity_id]);
                if (intval(count($boost_all)) < $activity['allnum']) {
                    $data['use'] = $reward['use'] + 1;
                    $data['boost'] = $reward['boost'] + 1;
                    $res = pdo_update(YOUMI_NAME . '_reward', $data, ['uniacid' => $uniacid, 'openid' => $fopenid, 'activity_id' => $activity_id]);
                }
            }
        }
    }
    youmi_result($res ? 0 : 1, $res ? '助力成功' : '您已帮过人家了~~',$boost);
}

/**
 * 获奖情况  红包
 * do   index   op  lottery
 * $reward  直接取
 */
function lottery($uniacid, $openid, $activity_id, $setting)
{

    global $_W;
    $activity = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_activity') . ' where uniacid = ' . $uniacid . ' and id = ' . $activity_id);
    $reward = pdo_get(YOUMI_NAME . '_reward', ['uniacid' => $uniacid, 'openid' => $openid, 'activity_id' => $activity_id]);
    if ($activity_id && intval($reward['reward_type']) == 0) {

        $money = pdo_fetchcolumn('select sum(reward_money) from ' . tablename(YOUMI_NAME . '_reward') . ' where activity_id = ' . $activity_id);
        $reward_type = pdo_fetchcolumn('select count(*) from ' . tablename(YOUMI_NAME . '_reward') . ' where reward_type = 2 ');
//        $rand = mt_rand(0, 100);
        $prize['used'] = $reward['used'] + 1;
//        die(json_encode(['$rand'=>$rand,'$reward_type'=>$reward_type]));
//        youmi_internal_log('aaa',['$rand'=>$rand,'$reward_type'=>$reward_type],3);
        if (floatval($money) < 100) {

            $min_redpack = floatval($activity['min_redpack']) * 100;
            $max_redpack = floatval($activity['max_redpack']) * 100;
            $redpack = mt_rand($min_redpack, $max_redpack) / 100;

            $reward['reward_type'] = $prize['reward_type'] = 1;
            $reward['reward_name'] = $prize['reward_name'] = '现金红包';
            $reward['reward_money'] = $prize['reward_money'] = $redpack;
            $reward['status'] = $prize['status'] = 1;
            $title = '恭喜您完成了10S挑战赛，获得了现金红包一个';
            $address = '点击领取';
            $date = date('Y-m-d');
            pdo_update(YOUMI_NAME . '_reward', $prize, ['id' => $reward['id']]);
        }

        $send = array(
            'first' => array(
                'value' => $title,
//            'color' => '#ff510'
            ),
            'keyword1' => array(
                'value' => $prize['reward_name'],
//            'color' => '#ff510'
            ),
            'keyword2' => array(
                'value' => $address,
//            'color' => '#ff510'
            ),
            'keyword3' => array(
                'value' => $date,
//            'color' => '#ff510'
            ),
            'remark' => array(
                'value' => '奖品需在领取时间内领取，过期作废，再次感谢您的参与，本活动解释权归开发商所有',
//            'color' => '#ff510'
            ),
        );

        $url = $_W['siteroot'] . 'app/index.php?i=' . $uniacid . '&c=entry&do=index&op=envelope&activity_id=' . $activity_id . '&m=' . YOUMI_NAME;
        $message['uniacid'] = $uniacid;
        $message['openid'] = $openid;
        $message['temp_id'] = $setting['prize_temp_id'];
        $message['send'] = serialize($send);
        $message['url'] = $url;
        $message['status'] = 1;
        $message['createtime'] = time();
        pdo_insert(YOUMI_NAME . '_message', $message);

    }
    return $reward;
}

/**
 * 用户信息提交
 * do   index   op  submit
 * realname     姓名      mobile      电话
 */
if ($op == 'submit') {

    $data['realname'] = trim($_GPC['realname']);
    $data['mobile'] = trim($_GPC['mobile']);
    $res = pdo_update(YOUMI_NAME . '_member', $data, ['id' => $this->umi_member['id']]);
    $errno = $res ? 0 : 1;
    youmi_result($errno, '提交' . ($res ? '成功' : '失败'));
}


function lottery2($uniacid,$openid,$activity_id,$mid,$data) {

    $prizes = pdo_getall(YOUMI_NAME . '_' . 'activity_prize', array('uniacid' => $uniacid, 'activity_id' => $activity_id, 'status >' => -1), '', '', 'createtime desc', '8');
    $prize_num = [];
    foreach ($prizes as &$prize) {
        if ($prize['num'] > 0 || $prize['num'] == -1) {
            $prize_num[$prize['id']] = $prize['odds'];
        }
        $prize['image'] = tomedia($prize['image']);
        unset($prize);
    }
//    if ($log) {
//        die(json_encode(['status' => 1, 'message' => '恭喜您，您已抽中' . $log['title'] . '！', 'log' => $log]));
//    }
    $new = strtotime(date('Y-m-d'));
    $log_num = pdo_fetchcolumn('select count(id) from ' . tablename(YOUMI_NAME . '_' . 'prize_log') . ' where uniacid = :uniacid and openid = :openid and activity_id = :activity_id and createtime >= :new ', [':uniacid' => $uniacid, ':activity_id' => $activity_id, ':openid' => $openid, ':new' => $new]);
//    if ($log_num >= 3) {
//        die(json_encode(['status' => 0, 'message' => '谢谢您参与，今日3次机会已用完，期待明天继续！']));
//    }
    unset($log);
    $item = probability($prize_num);
    $log['uniacid'] = $uniacid;
    $log['openid'] = $openid;
    $log['mid'] = $mid;
//    $log['realname'] = trim($_GPC['realname']);
//    $log['mobile'] = trim($_GPC['mobile']);
    $log['activity_id'] = $activity_id;
    $log['prize_id'] = $item['id'];
    $log['title'] = $item['title'];
    $log['image'] = tomedia($item['image']);
    $log['status'] = $item['status'];
    $log['createtime'] = TIMESTAMP;
    pdo_insert(YOUMI_NAME . '_' . 'prize_log', $log);
//    if($item['status']==1){
//        //发送中奖消息
//        $data = array(
//            'first' => array(
//                'value' => "恭喜您中奖了！",
//                'color' => '#ff510'
//            ),
//            'keyword1' => array(
//                'value' => $item['title'],
//                'color' => '#ff510'
//            ),
//            'keyword2' => array(
//                'value' => '常熟万达广场1楼中庭【璀璨澜庭】展位处',
//                'color' => '#ff510'
//            ),
//            'keyword3' => array(
//                'value' => '10月3日、4日、5日',
//                'color' => '#ff510'
//            ),
//            'remark' => array(
//                'value' => "" ,
//                'color' => '#ff510'
//            ),
//        );
//        $account_api = WeAccount::create();
//        $result = $account_api->sendTplNotice($openid, $setting['prizetpl_id'], $data);
//    }
    return ['errno' => 0, 'message' => $item['message'], 'log' => $log,'data'=>$data];
}

function probability($prize_num) {

    $prize_id = get_rand($prize_num);
    $prize = pdo_get(YOUMI_NAME . '_' . 'activity_prize', array('id' => $prize_id));
    if ($prize['status'] == 0) {
        $prize['message'] = '谢谢您参与，继续加油！';
    } else {
        $prize['message'] = '恭喜您，抽中了' . $prize['title'] . '！';
    }
    if ($prize['num'] > 0) {
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
