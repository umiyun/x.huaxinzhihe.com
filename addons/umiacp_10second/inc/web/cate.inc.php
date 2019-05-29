<?php
/**
 * Created by IntelliJ IDEA.
 * User: 7³
 * Date: 2018/7/30 0030
 * Time: 12:18
 */
global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$cate_id = intval($_GPC['cate_id']);

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if ($op == 'display') {
    $condition = '';
    $pindex = max(1, intval($_GPC['page']));
    $psize = !empty($_GPC['psize']) ? $_GPC['psize'] : 10;
    $paras[':uniacid'] = $uniacid;

    if ($cate_id) {
        $condition .= ' and cate_id = ' . $cate_id;
    }

    $children = array();
    $list = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'cate') . ' where uniacid = :uniacid and status > -1 ' . $condition . ' order by rank desc ', $paras);

    foreach ($list as $index => $row) {
        if (!empty($row['cate_id'])) {
            $children[$row['cate_id']][] = $row;
            unset($list[$index]);
        }
    }

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
		    
		    pdo_update(YOUMI_NAME . '_' . 'cate', $data, array('id' => $id));
		} else {
		    $data['createtime'] = TIMESTAMP;
            pdo_insert(YOUMI_NAME . '_' . 'cate', $data);
			$id = pdo_insertid();
		}
		itoast('温馨提示：修改分类成功！',$this->createWebUrl('cate') , 'success');
    }
    $item = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_' . 'cate') . ' where id = :id limit 1 ', $paras);
    include $this->template('cate');
}

if ($op == 'update_status') {
    $id = intval($_GPC['id']);
    $status = intval($_GPC['status']);
    $paras[':id'] = $id;

    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'cate') . ' WHERE id = :id limit 1 ', $paras);

    $res = pdo_update(YOUMI_NAME . '_' . 'cate', ['status' => $status], array('id' => $id));
    $errno = $res ? 0 : 1;
    youmi_result($errno);
}

if ($op == 'update_rank') {
    $id = intval($_GPC['id']);
    $rank = intval($_GPC['rank']);
    $paras[':id'] = $id;

    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'cate') . ' WHERE id = :id limit 1 ', $paras);

    $res = pdo_update(YOUMI_NAME . '_' . 'cate', ['rank' => $rank], array('id' => $id));
    $errno = $res ? 0 : 1;
    youmi_result($errno);
}

if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;

    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'cate') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：分类不存在或是已经被删除！', referer(), 'error');
    }
    $res = pdo_update(YOUMI_NAME . '_' . 'cate', ['status' => -1], array('id' => $id));
    if ($res) {
        $op = '删除分类';
        $content = '删除分类为【' . $item['title'] . '】ID为【' . $id . '】分类';
        $this->youmi_operate_log($op,$content);
    }
    itoast('温馨提示：分类删除成功！', referer(), 'success');
}

