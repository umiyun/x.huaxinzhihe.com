<?php

global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

$shop_id = intval($_GPC['shop_id']);

if ($op == 'display') {

    $pindex = max(1, intval($_GPC['page']));
    $psize = !empty($_GPC['psize']) ? $_GPC['psize'] : 10;

    $condition = '';
    $paras[':uniacid'] = $uniacid;

    $status = intval($_GPC['status']);
    if ($status) {
        $condition .= ' and o.status = ' . $status;
    } else {
        $condition .= ' and o.status > -1 ';
    }
    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= " and (o.title like '%{$keyword}%' or o.tid like '%{$keyword}%' or o.transid like '%{$keyword}%') ";
    }
    $orderby = ' order by ';

    $orderby .= ' o.createtime desc ';
    $list = pdo_fetchall('SELECT
	o.*,
	a.title AS activitytitle,
	ms.nickname AS nickname,
	ms.avatar AS avatar,
	m.realname AS realname,
	m.mobile AS mobile,
	f.openid AS openid,
    CASE
		o.STATUS 
		WHEN \'1\' THEN
		\'待支付\' 
		WHEN \'2\' THEN
		\'已支付\' 
		WHEN \'3\' THEN
		\'已核销\' 
		WHEN \'4\' THEN
		\'已取消\' 
        WHEN \'7\' THEN
		\'待退款\' 
		ELSE \'\' 
	END AS statusname 
    FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' o left join ' . tablename(YOUMI_NAME . '_' . 'member') . ' m on o.mid = m.mid left join ' . tablename('mc_mapping_fans') . ' f on f.openid = m.openid  left join ' . tablename('mc_members') . ' ms on ms.uid = f.uid left join ' . tablename(YOUMI_NAME . '_' . 'activity') . ' a on a.id = o.activity_id where o.uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);

    $total = pdo_fetchcolumn('SELECT COUNT(o.id) FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' o left join ' . tablename(YOUMI_NAME . '_' . 'member') . ' m on o.mid = m.mid left join ' . tablename('mc_mapping_fans') . ' f on f.openid = m.openid  left join ' . tablename('mc_members') . ' ms on ms.uid = f.uid left join ' . tablename(YOUMI_NAME . '_' . 'activity') . ' a on a.id = o.activity_id where o.uniacid = :uniacid ' . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('order');
}


if ($op == 'download') {

    $condition = '';
    $paras[':uniacid'] = $uniacid;

    $status = intval($_GPC['status']);
    if ($status) {
        $condition .= ' and o.status = ' . $status;
    } else {
        $condition .= ' and o.status > -1 ';
    }
    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= " and (o.title like '%{$keyword}%' and o.tid like '%{$keyword}%' or o.transid like '%{$keyword}%') ";
    }
    $orderby = ' order by ';

    $orderby .= ' o.createtime desc ';
    $list = pdo_fetchall('SELECT
	o.*,
	a.title AS activitytitle,
	ms.nickname AS nickname,
	ms.avatar AS avatar,
	m.realname AS realname,
	m.mobile AS mobile,
	f.openid AS openid,
    CASE
		o.STATUS 
		WHEN \'1\' THEN
		\'待支付\' 
		WHEN \'2\' THEN
		\'已支付\' 
		WHEN \'3\' THEN
		\'已核销\' 
		WHEN \'4\' THEN
		\'已取消\' 
        WHEN \'7\' THEN
		\'待退款\' 
		ELSE \'\' 
	END AS statusname 
    FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' o left join ' . tablename(YOUMI_NAME . '_' . 'member') . ' m on o.mid = m.mid left join ' . tablename('mc_mapping_fans') . ' f on f.openid = m.openid  left join ' . tablename('mc_members') . ' ms on ms.uid = f.uid left join ' . tablename(YOUMI_NAME . '_' . 'activity') . ' a on a.id = o.activity_id where o.uniacid = :uniacid ' . $condition . $orderby, $paras);

    $header = [
        '用户',
        '姓名',
        '电话',
        '活动',
        '商品名',
        '商户订单号',
        '微信支付单号',
        '金额',
        '状态',
        '下单时间',
        '支付时间',
    ];

    $types = [
        ['nickname', 200],
        ['realname', 200],
        ['mobile', 200],
        ['activitytitle', 300],
        ['title', 200],
        ['tid', 300],
        ['transid', 300],
        ['price'],
        ['statusname'],
        ['createtime', 200, 'date'],
        ['pay_time', 200, 'date'],
    ];

    $this->download('订单记录', $list, $header, $types);

}

if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：订单不存在或是已经被删除！', referer(), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'order', ['status' => -1], array('id' => $id));
    itoast('温馨提示：订单删除成功！', referer(), 'success');
}

if ($op == 'search') {
    //选择用户

    $keyword = $_GPC['keyword'];

    $ds = $this->getMemberKeyword($keyword);

    die(json_encode(['status' => $ds ? 1 : 0, 'ds' => $ds]));

}
