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
        $condition .= ' and status = ' . $status;
    } else {
        $condition .= ' and status > -1 ';
    }
    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= " and title like '%{$keyword}%' ";
    }
    $orderby = ' order by sort desc,createtime desc';

    $list = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'notice') . ' where uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    foreach ($list as &$item) {
        switch ($item['status']) {
            case 1 :
                $item['statusname'] = '启用';
                break;
            case 2 :
                $item['statusname'] = '禁用';
                break;
            default :
                $item['statusname'] = '';
                break;
        }
        
        unset($item);
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(YOUMI_NAME . '_' . 'notice') . ' WHERE uniacid = :uniacid ' . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('notice');
} 


if ($op == 'post') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    
    if (checksubmit('submit')) {
        $data = $_GPC['data'];
        $data['uniacid'] = $uniacid;
        
        $data['createtime'] = strtotime($data['createtime']);
                
        if (!empty($id)) {
		    
		    pdo_update(YOUMI_NAME . '_' . 'notice', $data, array('id' => $id));
		    $message = '修改';
		} else {
		    $data['createtime'] = TIMESTAMP;
            pdo_insert(YOUMI_NAME . '_' . 'notice', $data);
			$id = pdo_insertid();
            $message = '新增';
		}
		itoast('温馨提示：' . $message . '公告成功！',$this->createWebUrl('notice') , 'success');
    }
    $item = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_' . 'notice') . ' where id = :id limit 1 ', $paras);
     
    include $this->template('notice');
} 


if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'notice') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：公告不存在或是已经被删除！', $this->createWebUrl('notice'), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'notice', ['status' => -1], array('id' => $id));
    itoast('温馨提示：公告删除成功！', $this->createWebUrl('notice'), 'success');
}