<?php

global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if ($op == 'display') {
    $condition = '';
    $pindex = max(1, intval($_GPC['page']));
    $psize = !empty($_GPC['psize']) ? $_GPC['psize'] : 10;
    $paras[':uniacid'] = $uniacid;

    $list = pdo_fetchall('SELECT * FROM ' . tablename(MC_NAME . '_' . 'cate') . ' where uniacid = :uniacid and status > -1 ' . $condition . ' order by id desc LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(MC_NAME . '_' . 'cate') . ' WHERE uniacid = :uniacid and status > -1 ' . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('cate');
} 

if ($op == 'post') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;

    if (checksubmit('submit')) {
        $data = $_GPC['data'];
        $data['uniacid'] = $uniacid;
        if (!empty($id)) {
		    
		    pdo_update(MC_NAME . '_' . 'cate', $data, array('id' => $id));
		} else {
		    $data['createtime'] = TIMESTAMP;
            pdo_insert(MC_NAME . '_' . 'cate', $data);
			$id = pdo_insertid();
		}
		itoast('温馨提示：修改分类成功！',$this->createWebUrl('cate') , 'success');
    }
    $item = pdo_fetch('select * from ' . tablename(MC_NAME . '_' . 'cate') . ' where id = :id limit 1 ', $paras);
    include $this->template('cate');
}

if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;

    $item = pdo_fetch('SELECT * FROM ' . tablename(MC_NAME . '_' . 'cate') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：分类不存在或是已经被删除！', referer(), 'error');
    }
    $res = pdo_update(MC_NAME . '_' . 'cate', ['status' => -1], array('id' => $id));
    if ($res) {
        $op = '删除分类';
        $content = '删除分类为【' . $item['title'] . '】ID为【' . $id . '】分类';
        $this->mayi_demo_log($op,$content);
    }
    itoast('温馨提示：分类删除成功！', referer(), 'success');
}