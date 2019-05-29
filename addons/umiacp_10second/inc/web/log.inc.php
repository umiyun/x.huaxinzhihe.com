<?php

global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if ($op == 'display') {

    $pindex = max(1, intval($_GPC['page']));
    $psize = !empty($_GPC['psize']) ? $_GPC['psize'] : 10;

    $condition = '';
    $paras[':uniacid'] = $uniacid;

    $activity_id = intval($_GPC['activity_id']);
    if ($activity_id) {
        $condition .= ' and o.activity_id = ' . $activity_id;
    }
    $status = intval($_GPC['status']);
    if ($status) {
        if ($status == -1) {
            $condition .= ' and o.status = 0 ';
        } else {
            $condition .= ' and o.status = ' . $status;
        }
    } else {
        $condition .= ' and o.status > -1 ';
    }
    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= " and (a.title like '%{$keyword}%' or m.realname like '%{$keyword}%' or m.mobile like '%{$keyword}%') ";
    }
    $orderby = ' order by ';

    $orderby .= ' o.createtime desc ';
    $list = pdo_fetchall('SELECT
	o.*,
	a.title AS activitytitle,
	m.realname AS realname,
	m.mobile AS mobile,
    CASE
		o.STATUS 
		WHEN \'1\' THEN
		\'已中奖\' 
		WHEN \'2\' THEN
		\'已领奖\' 
		WHEN \'0\' THEN
		\'未中奖\' 
		ELSE \'\' 
	END AS statusname 
FROM ' . tablename(YOUMI_NAME . '_' . 'prize_log') . ' o left join ' . tablename(YOUMI_NAME . '_' . 'member') . ' m on o.mid = m.mid left join ' . tablename('mc_mapping_fans') . ' f on f.openid = m.openid  left join ' . tablename('mc_members') . ' ms on ms.uid = f.uid left join ' . tablename(YOUMI_NAME . '_' . 'activity') . ' a on a.id = o.activity_id where o.uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    $total = pdo_fetchcolumn('SELECT COUNT(o.id) FROM ' . tablename(YOUMI_NAME . '_' . 'prize_log') . ' o left join ' . tablename(YOUMI_NAME . '_' . 'member') . ' m on o.mid = m.mid left join ' . tablename('mc_mapping_fans') . ' f on f.openid = m.openid  left join ' . tablename('mc_members') . ' ms on ms.uid = f.uid left join ' . tablename(YOUMI_NAME . '_' . 'activity') . ' a on a.id = o.activity_id where o.uniacid = :uniacid ' . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('log');
}

if ($op == 'download') {

    $condition = '';
    $activity_id = $_GPC['id'];
    $paras[':uniacid'] = $uniacid;

    $activity_id = intval($_GPC['activity_id']);
    if ($activity_id) {
        $condition .= ' and o.activity_id = ' . $activity_id;
    }
    $status = intval($_GPC['status']);
    if ($status) {
        $condition .= ' and o.status = ' . $status;
        if ($status == -1) {
            $condition .= ' and o.status = 0 ';
        }
    } else {
        $condition .= ' and o.status > -1 ';
    }
    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= " and (a.title like '%{$keyword}%' or m.realname like '%{$keyword}%' or m.mobile like '%{$keyword}%') ";
    }
    $orderby = ' order by ';

    $orderby .= ' o.createtime desc ';
    $list = pdo_fetchall('SELECT
	o.*,
	a.title AS activitytitle,
	m.realname AS realname,
	m.mobile AS mobile,
    CASE
		o.STATUS 
		WHEN \'1\' THEN
		\'已中奖\' 
		WHEN \'2\' THEN
		\'已领奖\' 
		WHEN \'0\' THEN
		\'未中奖\' 
		ELSE \'\' 
	END AS statusname 
FROM ' . tablename(YOUMI_NAME . '_' . 'prize_log') . ' o left join ' . tablename(YOUMI_NAME . '_' . 'member') . ' m on o.mid = m.mid left join ' . tablename('mc_mapping_fans') . ' f on f.openid = m.openid  left join ' . tablename('mc_members') . ' ms on ms.uid = f.uid left join ' . tablename(YOUMI_NAME . '_' . 'activity') . ' a on a.id = o.activity_id where o.uniacid = :uniacid ' . $condition . $orderby, $paras);

    $header = [
        '活动',
        '姓名',
        '手机号',
        '奖品',
        '状态',
        '中奖时间',
    ];

    $types = [
        ['activitytitle', 300],
        ['realname', 200],
        ['mobile', 200],
        ['title', 300],
        ['statusname', 100],
        ['createtime', 200, 'date'],
    ];

    $this->download('中奖记录', $list, $header, $types);

}


if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'prize_log') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：中奖不存在或是已经被删除！', referer(), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'prize_log', ['status' => -1], array('id' => $id));
    itoast('温馨提示：中奖删除成功！', referer(), 'success');
}
        
if ($op == 'search') {
    //选择用户

    $keyword = $_GPC['keyword'];

    $ds = $this->getMemberKeyword($keyword);

    die(json_encode(['status' => $ds ? 1 : 0, 'ds' => $ds]));

}
        