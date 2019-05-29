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
        $condition .= ' and o.status = ' . $status;
    } else {
        $condition .= ' and o.status > -1 ';
    }
    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= " and o.nickname like '%{$keyword}%' ";
    }
    $orderby = ' order by ';

    $orderby .= ' o.diff asc, o.createtime desc ';
    $list = pdo_fetchall('SELECT o.*,a.title AS activity_title FROM ' . tablename(YOUMI_NAME . '_' . 'reward') . ' o left join ' . tablename(YOUMI_NAME . '_' . 'activity') . ' a on a.id = o.activity_id where o.uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);

    $total = pdo_fetchcolumn('SELECT COUNT(o.id) FROM ' . tablename(YOUMI_NAME . '_' . 'reward') . ' o left join ' . tablename(YOUMI_NAME . '_' . 'activity') . ' a on a.id = o.activity_id where o.uniacid = :uniacid ' . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('list');
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
        $condition .= " and o.nickname like '%{$keyword}%' ";
    }
    $orderby = ' order by ';

    $orderby .= ' o.good desc, o.createtime asc ';
    $list = pdo_fetchall('SELECT o.*,a.title AS activity_title FROM ' . tablename(YOUMI_NAME . '_' . 'reward') . ' o left join ' . tablename(YOUMI_NAME . '_' . 'activity') . ' a on a.id = o.activity_id where o.uniacid = :uniacid ' . $condition . $orderby, $paras);


    $header = [
        '用户',
        '助力人数',
        '最好成绩',
        '参与时间',
    ];

    $types = [
        ['nickname', 300],
        ['boost', 200],
        ['good', 200],
        ['createtime', 200, 'date'],
    ];

    $this->download('排行榜记录', $list, $header, $types);

}


if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'reward') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：排行榜不存在或是已经被删除！', referer(), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'reward', ['status' => -1], array('id' => $id));
    itoast('温馨提示：排行榜删除成功！', referer(), 'success');
}
        
if ($op == 'search') {
    //选择用户

    $keyword = $_GPC['keyword'];

    $ds = $this->getMemberKeyword($keyword);

    die(json_encode(['status' => $ds ? 1 : 0, 'ds' => $ds]));

}
        