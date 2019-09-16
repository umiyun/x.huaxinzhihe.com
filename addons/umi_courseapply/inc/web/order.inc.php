<?php

global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if ($op == 'display') {

    $pindex = max(1, intval($_GPC['page']));
    $psize = !empty($_GPC['psize']) ? $_GPC['psize'] : 10;

    $condition = '';
    $paras[':uniacid'] = $uniacid;

    $status = intval($_GPC['status']);
    if ($status) {
        $condition .= ' and `status` = ' . $status;
    } else {
        $condition .= ' and `status` > -1 ';
    }
    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= " and (title like '%{$keyword}%' or realname like '%{$keyword}%' or mobile like '%{$keyword}%') ";
    }
    $orderby = ' order by ';

    $orderby .= ' createtime desc ';
    $list = pdo_fetchall('SELECT *,
    CASE
		`status`
		WHEN \'1\' THEN
		\'待核销\' 
		WHEN \'2\' THEN
		\'已完成\' 
		WHEN \'3\' THEN
		\'已核销\' 
		WHEN \'6\' THEN
		\'砍价失败\' 
		ELSE \'\' 
	END AS statusname 
    FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' where uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    foreach ($list as &$lv){
        $lv['prize']= pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'prize') . ' WHERE id = :id ', [':id'=>$lv['prize_id']]);
        unset($lv);
    }
    $total = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' where uniacid = :uniacid ' . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('order');
}

if ($op == 'download') {

    $condition = '';
    $paras[':uniacid'] = $uniacid;

    $status = intval($_GPC['status']);
    if ($status) {
        $condition .= ' and status = ' . $status;
    } else {
        $condition .= ' and status > -1 ';
    }
    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= " and (title like '%{$keyword}%' or realname like '%{$keyword}%' or mobile like '%{$keyword}%') ";
    }
    $orderby = ' order by ';

    $orderby .= ' createtime desc ';
    $list = pdo_fetchall('SELECT *,
    CASE
		`status`
		WHEN \'1\' THEN
		\'待核销\' 
		WHEN \'2\' THEN
		\'已完成\' 
		WHEN \'3\' THEN
		\'已核销\' 
		WHEN \'6\' THEN
		\'砍价失败\' 
		ELSE \'\' 
	END AS statusname 
    FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' where uniacid = :uniacid ' . $condition, $paras);

    $header = [
        '用户',
        '姓名',
        '电话',
        '票区',
        '原价',
        '砍价完成时间',
        '核销时间',
        '状态',
        '下单时间',
    ];

    $types = [
        ['nickname', 200],
        ['realname', 200],
        ['mobile', 200],
        ['title', 200],
        ['oprice'],
        ['pay_time', 200, 'date'],
        ['saler_time', 200, 'date'],
        ['statusname'],
        ['createtime', 200, 'date'],
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
        