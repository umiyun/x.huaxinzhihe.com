<?php

global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if ($op == 'display') {
    $condition = '';
    $pindex = max(1, intval($_GPC['page']));
    $psize = !empty($_GPC['psize']) ? $_GPC['psize'] : 10;
    $paras[':uniacid'] = $uniacid;

    $status = intval($_GPC['status']);
    if ($status) {
        $condition .= ' and u.status = ' . $status;
    } else {
        $condition .= ' and u.status > -1 ';
    }
    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= " and (u.realname like '%{$keyword}%' or u.mobile like '%{$keyword}%') ";
    }
    $orderby = ' order by ';

    $orderby .= ' createtime desc ';

    $list = pdo_fetchall('SELECT u.*,f.uid,f.nickname,f.groupid,f.follow,f.followtime,f.unfollowtime,f.tag,f.unionid,m.credit1,m.credit2,m.avatar,m.gender FROM ' . tablename('mc_mapping_fans') . ' AS f LEFT JOIN ' . tablename(YOUMI_NAME . '_member') . ' AS u ON f.openid = u.openid LEFT JOIN ' . tablename('mc_members') . ' AS m ON f.uid = m.uid where u.uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    foreach ($list as &$item) {

        switch ($item['status']) {
            case 1 :
                $item['statusname'] = '正常';
                break;
            case 2 :
                $item['statusname'] = '黑名单';
                break;
            default :
                $item['statusname'] = '';
                break;
        }

        unset($item);
    }
    $total = pdo_fetchcolumn('SELECT count(m.uid) FROM ' . tablename('mc_mapping_fans') . ' AS f LEFT JOIN ' . tablename(YOUMI_NAME . '_member') . ' AS u ON f.openid = u.openid LEFT JOIN ' . tablename('mc_members') . ' AS m ON f.uid = m.uid where u.uniacid = :uniacid ' . $condition . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('member');
}


if ($op == 'search') {
    //选择用户

    $keyword = $_GPC['keyword'];

    $ds = $this->getMemberKeyword($keyword);

    die(json_encode(['status' => $ds ? 1 : 0, 'ds' => $ds]));

}
        