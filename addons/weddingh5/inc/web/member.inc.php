<?php

global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if ($op == 'display') {
    $condition = '';
    $pindex = max(1, intval($_GPC['page']));
    $psize = !empty($_GPC['psize']) ? $_GPC['psize'] : 10;
    $paras = [':uniacid' => $uniacid];
    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= ' and (nickname like \'%' .$keyword . '%\' or mobile like \'%' .$keyword . '%\')';
    }

    $list = pdo_fetchall('SELECT * FROM ' . tablename('mc_members') . ' where uniacid = :uniacid ' .$condition .' order by createtime desc ' . 'LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);

    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_members') . ' WHERE uniacid = :uniacid ' .$condition , $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('member');
}

if ($op == 'post') {
    $uid = intval($_GPC['uid']);
    $paras = [':uniacid' => $uniacid, ':uid' => $uid];

    $item = pdo_fetch('SELECT em.*,m.credit1,m.credit2,m.avatar,m.gender FROM ' . tablename('mc_mapping_fans') . ' AS em left join ' . tablename('mc_members') . ' AS m on em.uid = m.uid where em.uniacid = :uniacid and em.uid = :uid', $paras);
    include $this->template('edit_member');
}

if ($op == 'update_credit2') {

    $uid = $_GPC['uid'];
    $credit2 = $_GPC['credit2'];

    $member = pdo_get('mc_members' , ['uid' => $uid]);
    if (floatval($member['credit2']) + floatval($credit2) < 0) {
        die(json_encode(['status' => 0, 'message' => '积分不能修改为负数']));
    }

    $res = pdo_update('mc_members', ['credit2 +=' => floatval($credit2)], ['uid' => $uid]);
    if ($res) {
        $op = '后台修改积分';
        $content = $_W['username'] . '将【' . $member['nickname'] . '】的积分从 ' . floatval($member['credit2']) . ' 修改为 ' . (floatval($member['credit2']) + floatval($credit2));
        $this->mayi_demo_log($op,$content);
    }
    die(json_encode(['status' => $res, 'message' => '修改积分' . ($res ? '成功' : '失败')]));

}

