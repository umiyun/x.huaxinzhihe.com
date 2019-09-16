<?php


global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if ($op == 'display') {
    $condition = '';
    $pindex = max(1, intval($_GPC['page']));
    $psize = !empty($_GPC['psize']) ? $_GPC['psize'] : 10;
    $paras[':uniacid'] = $uniacid;

    $list = pdo_fetchall('SELECT * FROM ' . tablename(MC_NAME . '_' . 'message') . ' where uniacid = :uniacid and status > -1 ' . $condition . ' order by id desc LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(MC_NAME . '_' . 'message') . ' WHERE uniacid = :uniacid and status > -1 ' . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('message');
} 
if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(MC_NAME . '_' . 'message') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：留言不存在或是已经被删除！', referer(), 'error');
    }
    pdo_update(MC_NAME . '_' . 'message', ['status' => -1], array('id' => $id));
    itoast('温馨提示：留言删除成功！', referer(), 'success');
}