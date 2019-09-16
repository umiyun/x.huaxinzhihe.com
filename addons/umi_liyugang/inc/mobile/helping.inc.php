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
$_W['page']['title'] = $setting['title'] ? $setting['title'] : '李玉刚门票';

$member = $this->member;
youmi_puv('index');


$cut_my= pdo_get(YOUMI_NAME . '_' . 'cut', ['uniacid' => $uniacid, 'mid' => $this->mid]);
//参与人数
$cuts_succ=pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_cut') . ' where uniacid = ' . $uniacid . '  and status = 3 or status = 4');
//剩余票数
$ticket_surplus=$setting['num']-count($cuts_succ);


if ($op == 'display') {
    $cut_new= pdo_get(YOUMI_NAME . '_' . 'cut', ['uniacid' => $uniacid, 'id' => $_GPC['cut_id']]);
    $_share['title'] = $cut_new['nickname'].'换上了昭君妆，快来看看美不美！';
    $_share['imgUrl'] = tomedia($setting['share_image']);
    $_share['desc'] = $setting['share_desc'];
    $_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('helping', array('cut_id' => $_GPC['cut_id']));

    include $this->template('helping');
    exit();
}
if($op == 'cheating'){
    $cut_new= pdo_get(YOUMI_NAME . '_' . 'cut', ['uniacid' => $uniacid, 'id' => $_GPC['cut_id']]);
    if ($cut_new['openid'] == $member['openid']) {
        youmi_result(1, '邀请好友赞美');
    }
    if($cut_new['status']>=3){
        youmi_result(1, '好友已抢到门票');
    }
    $isHelping=pdo_get(YOUMI_NAME . '_' . 'record', ['uniacid' => $uniacid,'mid' => $this->mid, 'fmid' => $cut_new['mid']]);
    if(!$isHelping) {
        $data = [
            'uniacid' => $uniacid,
            'mid' => $this->mid,
            'fmid' => $cut_new['mid'],
            'cut_id' => $cut_new['id'],
            'nickname' => $member['nickname'],
            'avatar' => $member['avatar'],
            'status' => 1,
            'createtime' => time(),
        ];
        pdo_insert(YOUMI_NAME . '_' . 'record', $data);
        $data['id'] = pdo_insertid();
        pdo_update(YOUMI_NAME . '_cut', ['times +=' => 1], ['id' => $cut_new['id']]);


        if($cut_new['times']>8){
            $log['uniacid'] = $uniacid;
            $log['openid'] = $cut_new['openid'];
            $typename = $setting['title'];
            $log['status'] = 1;
            $log['createtime'] = time();
            $log['url'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index');
            if ($ticket_surplus > 0) {
                pdo_update(YOUMI_NAME . '_cut', ['status' => 3], ['id' => $cut_new['id']]);

                $log['temp_id'] = trim($setting['success_temp_id']);
                //发送中奖消息
                $send = array(
                    'first' => array(
                        'value' => "恭喜您完成妆容赞美！快来领取您的兑换券吧！",
                        'color' => '#ff510'
                    ),
                    'keyword1' => array(
                        'value' => '李玉刚·昭君出塞 兑换券一张',
                        'color' => '#ff510'
                    ),
                    'keyword2' => array(
                        'value' => '深圳路与常福路交汇处往南约100m璀璨澜庭售楼处。',
                        'color' => '#ff510'
                    ),
                    'keyword3' => array(
                        'value' => '7月20日至7月31日（9:00-17:00）',
                        'color' => '#ff510'
                    ),
                    'remark' => array(
                        'value' => "请您于规定时间内前往指定地点领取兑换券！如有疑问，可拨打客服电话：0512-5585 6666",
                        'color' => '#ff510'
                    ),
                );
                $log['send'] = json_encode($send, 256);
            } else {
                pdo_update(YOUMI_NAME . '_cut', ['status' => 5], ['id' => $cut_new['id']]);

            }
            pdo_insert(YOUMI_NAME . '_message', $log);
        }elseif ($cut_new['times']==8){
            $log['uniacid'] = $uniacid;
            $log['openid'] = $cut_new['openid'];
            $typename = $setting['title'];
            $log['status'] = 1;
            $log['createtime'] = time();
            $log['url'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index');
            $log['temp_id'] = trim($setting['progress_temp_id']);
            //发送失败消息 提示只剩一票即可领取门票
            $send = array(
                'first' => array(
                    'value' => "李玉刚·昭君出塞 礼品兑换券太抢手啦！仅差一位好友赞美即可抢到！快邀请您的好友来赞美你吧~",
                    'color' => '#ff510'
                ),
                'keyword1' => array(
                    'value' => "{$cut_new['nickname']}",
                    'color' => '#ff510'
                ),
                'keyword2' => array(
                    'value' => '9/10',
                    'color' => '#ff510'
                ),
                'remark' => array(
                    'value' => "如有疑问，可拨打客服电话：0512-5585 6666",
                    'color' => '#ff510'
                ),
            );
            $log['send'] = json_encode($send, 256);
            pdo_insert(YOUMI_NAME . '_message', $log);
        }
        youmi_result(0, '赞美成功', $data);
    }else{
        youmi_result(1, '您已经赞美过了', $data);
    }
}

if($op == 'mess'){
//    $cut_new= pdo_getall(YOUMI_NAME . '_' . 'cut', [ 'uniacid' => $_W['uniacid']]);
    $cut_news=pdo_fetchall('select * from '.tablename(YOUMI_NAME . '_' . 'cut').' where times<27');
//die(json_encode($cut_new));
      foreach ($cut_news as $cut_new){

            $log['uniacid'] = $cut_new['uniacid'];
            $log['openid'] = $cut_new['openid'];
            $typename = $setting['title'];
            $log['status'] = 1;
            $log['createtime'] = time();
            $log['url'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index');
            $log['temp_id'] = trim($setting['progress_temp_id']);
            //发送失败消息 提示只剩一票即可领取门票
            $send = array(
                'first' => array(
                    'value' => "盛邀大人物，親鑒全新“昭君妆”，即有机会获取李玉刚诗意歌舞剧《昭君出塞》门票喔",
                    'color' => '#ff510'
                ),
                'keyword1' => array(
                    'value' => "{$cut_new['nickname']}",
                    'color' => '#ff510'
                ),
                'keyword2' => array(
                    'value' => '27/28',
                    'color' => '#ff510'
                ),
                'remark' => array(
                    'value' => "如有疑问，可拨打客服电话：0512-5585 6666",
                    'color' => '#ff510'
                ),
            );
            $log['send'] = json_encode($send, 256);
            pdo_insert(YOUMI_NAME . '_message', $log);
        }
        youmi_result(0, '消息添加', $cut_news);

}
