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

$setting = pdo_get(YOUMI_NAME . '_' . 'setting', array('uniacid' => $uniacid));

$_W['page']['title'] = $setting['title'] ? $setting['title'] : '口令红包';

$member = $this->getMemberInfo($openid);
$activity_id = intval($_GPC['activity_id']) ? intval($_GPC['activity_id']) : 2;
$new_log = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_' . 'prize_log') . ' where uniacid = :uniacid  and activity_id = :activity_id and status>0 order by id desc', [':uniacid' => $uniacid, ':activity_id' => $activity_id]);

$act_member=pdo_get('umi_kouling2_activity_member',array('uniacid'=>$_W['uniacid'],'activity_id'=>$activity_id,'act_time'=>date("Y-m-d"),'openid'=>$_W['openid']));
if(empty($act_member))
{
    $data_m=array(
      'uniacid'=>$_W['uniacid'],
      'activity_id'=>$activity_id,
        'act_time'=>date("Y-m-d"),
        'openid'=>$_W['openid'],
        'fopenid'=>$_GPC['fopenid'],
        'nickname'=>$member['nickname'],
        'avatar'=>$member['avatar'],
        'createtime'=>time()
    );
    pdo_insert('umi_kouling2_activity_member',$data_m);
}
$activity = pdo_get(YOUMI_NAME . '_' . 'activity', array('uniacid' => $uniacid, 'id' => $activity_id));
if ($activity['status'] != 1) {
    message('非常抱歉！此活动已关闭');
}
if ($activity['starttime'] > TIMESTAMP) {
    message('非常抱歉！此活动还未开始');
}
if ($activity['endtime'] < TIMESTAMP) {
    message('非常抱歉！此活动已结束');
}

$prizes = pdo_getall(YOUMI_NAME . '_' . 'activity_prize', array('uniacid' => $uniacid, 'activity_id' => $activity_id), '', '', 'createtime desc', '8');
$prize_num = [];
foreach ($prizes as &$prize) {
    if ($prize['num'] > 0 || $prize['num'] == -1) {
        $prize_num[$prize['id']] = $prize['odds'];
    }
    $prize['image'] = tomedia($prize['image']);
    unset($prize);
}

//$log = pdo_get(YOUMI_NAME . '_' . 'prize_log', array('uniacid' => $uniacid, 'activity_id' => $activity_id, 'openid' => $openid, 'status >' => 0));

if ($op == 'display') {

    $log_num2 = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_' . 'prize_log') . ' where uniacid = :uniacid and openid = :openid and activity_id = :activity_id and status>0 order by createtime desc ', [':uniacid' => $uniacid, ':activity_id' => $activity_id, ':openid' => $openid]);
    if(empty($log_num2)){
        $_share['title'] = $_W['fans']['nickname']."在趣时尚“世”精彩活动中，很遗憾没抽到奖品，快来帮Ta一下！";
    }else{
        $_share['title'] = $_W['fans']['nickname']."在趣时尚“世”精彩活动中活动抽取".$log_num2[0]['title']."礼品！快来围观！";
    }

    $_share['imgUrl'] = tomedia($setting['share_image']);
    $_share['desc'] = $setting['share_desc'];
    $_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index', ['activity_id' => $activity_id,'fopenid'=>$_W['openid']]);

    pdo_update(YOUMI_NAME . '_' . 'setting', ['pv +=' => 1], array('uniacid' => $uniacid));

    include $this->template('index');
}
if($op=='mylog'){
    $log_num = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_' . 'prize_log') . ' where uniacid = :uniacid and openid = :openid and activity_id = :activity_id and status>0', [':uniacid' => $uniacid, ':activity_id' => $activity_id, ':openid' => $openid]);
    if($log_num){
        foreach ($log_num as &$item){
            $item['createtime']=date('Y-m-d H:m',$item['createtime']);
            unset($item);
        }
    }
    die(json_encode(['status' => 1, 'message' => $item['message'], 'log' => $log_num]));
}
if($op=='updata_log'){
    pdo_update(YOUMI_NAME . '_' . 'prize_log',array('mobile'=>$_GPC['mobile'],'realname'=>$_GPC['realname']), array('id' => $_GPC['id']));
    die(json_encode(['status' => 1, 'message' => '提交成功！']));
}
if ($op == 'lottery') {

//    if ($log) {
//        die(json_encode(['status' => 1, 'message' => '恭喜您，您已抽中' . $log['title'] . '！', 'log' => $log]));
//    }
    $new = date('Y-m-d');
    $log_num = pdo_fetchcolumn('select count(id) from ' . tablename(YOUMI_NAME . '_' . 'prize_log') . ' where uniacid = :uniacid and openid = :openid and activity_id = :activity_id and act_time = :new and status>0', [':uniacid' => $uniacid, ':activity_id' => $activity_id, ':openid' => $openid, ':new' => $new]);
    $share_members=pdo_getall('umi_kouling2_activity_member',array('uniacid'=>$_W['uniacid'],'activity_id'=>$activity_id,'act_time'=>date("Y-m-d"),'fopenid'=>$_W['openid']));
    $num=3;
    if(count($share_members)>=3){
        $num=6;
    }
    $alllog_num = pdo_fetchcolumn('select count(id) from ' . tablename(YOUMI_NAME . '_' . 'prize_log') . ' where uniacid = :uniacid and openid = :openid and activity_id = :activity_id and act_time = :new ', [':uniacid' => $uniacid, ':activity_id' => $activity_id, ':openid' => $openid, ':new' => $new]);

    if ($log_num >= 3||$alllog_num==0) {
        $item = pdo_get(YOUMI_NAME . '_' . 'activity_prize', array('status' => 0));
//        die(json_encode(['status' => 0, 'message' => '谢谢您参与，今日抽奖机会已用完，好友一起可以获得更多机会喔！']));
    }else{
        $item = probability($prize_num);
    }
    if ($alllog_num >= $num) {
        die(json_encode(['status' => 0, 'message' => '谢谢您参与，今日抽奖机会已用完，邀请好友一起可以获得更多机会喔！']));
    }
//    unset($log);

    $log['uniacid'] = $uniacid;
    $log['openid'] = $openid;
    $log['realname'] = trim($_GPC['realname']);
    $log['mobile'] = trim($_GPC['mobile']);
    $log['activity_id'] = $activity_id;
    $log['prize_id'] = $item['id'];
    $log['title'] = $item['title'];
    $log['image'] = $item['image'];
    $log['status'] = $item['status'];
    $log['act_time'] = date('Y-m-d');
    $log['nickname'] = $member['nickname'];
    $log['createtime'] = TIMESTAMP;
    pdo_insert(YOUMI_NAME . '_' . 'prize_log', $log);
    $log['id']=pdo_insertid();
    if($item['status']==1){
        //发送中奖消息
        $data = array(
            'first' => array(
                'value' => "恭喜您中奖了！",
                'color' => '#ff510'
            ),
            'keyword1' => array(
                'value' => $item['title'],
                'color' => '#ff510'
            ),
            'keyword2' => array(
                'value' => '深圳路与常福路交汇处往南约100米璀璨澜庭售楼处',
                'color' => '#ff510'
            ),
            'keyword3' => array(
                'value' => '2019年6月1日-6月2日  10：00-20:00',
                'color' => '#ff510'
            ),
            'remark' => array(
                'value' => "" ,
                'color' => '#ff510'
            ),
        );
        $account_api = WeAccount::create();
        $result = $account_api->sendTplNotice($openid, $setting['prizetpl_id'], $data);
    }
    die(json_encode(['status' => 1, 'message' => $item['message'], 'log' => $log]));
}

function probability($prize_num) {

    $prize_id = get_rand($prize_num);
    $prize = pdo_get(YOUMI_NAME . '_' . 'activity_prize', array('id' => $prize_id));
    if ($prize['status'] == 0) {
        $prize['message'] = '谢谢您参与，继续加油！';
    } else {
        $prize['message'] =  $prize['title'] ;
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
