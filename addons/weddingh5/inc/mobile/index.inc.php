<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/16 0016
 * Time: 下午 3:54
 */

global $_W, $_GPC;

checkauth();

$uniacid = intval($_W['uniacid']);
$openid = trim($_W['openid']);

$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'display';

$setting = pdo_get(MC_NAME . '_' . 'setting', array('uniacid' => $uniacid));

$_W['page']['title'] = '郭然爱臻婚礼邀请函';

$member = $this->getMemberInfo($openid);

if ($op == 'display') {

    $_share['title'] = '郭然爱臻';
    $_share['imgUrl'] = MC_URL_APP . 'images/share.png';
    $_share['desc'] = '郭然爱臻婚礼邀请函';
    $_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index');

    include $this->template('index');
}

if ($op == 'post_message') {

    $data['uniacid'] = $uniacid;
    $data['openid'] = $openid;
    $member = pdo_fetch('SELECT em.*,m.credit1,m.credit2,m.avatar,m.gender FROM ' . tablename('mc_mapping_fans') . ' AS em left join ' . tablename('mc_members') . ' AS m on em.uid = m.uid where em.uniacid = :uniacid and em.openid = :openid ', array(':uniacid' => $uniacid, ':openid' => $openid));
    $member['avatar'] = str_replace('132132', '132', $member['avatar']);
    $data['avatar'] = $member['avatar'];
    $data['nickname'] = $member['nickname'];;
    $data['content'] = trim($_GPC['content']);
    $data['status'] = 1;
    $data['createtime'] = TIMESTAMP;

    $res = pdo_insert(MC_NAME . '_message', $data);
    die(json_encode(['status' => $res, 'message' => ('温馨提示：' . '留言' . ($res ? '成功' : '失败'))]));
}

if ($op == 'message_list') {

    $res = pdo_getall(MC_NAME . '_message', ['uniacid' => $uniacid]);
    die(json_encode(['status' => 1, 'data' => $res]));
}