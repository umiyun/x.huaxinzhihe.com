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
    $orderby = ' order by ';
    
    $orderby .= ' createtime desc ';
    $list = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'effects') . ' where uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    foreach ($list as &$item) {

        if ($item['effectscate_id']) {
            $effectscate = pdo_get(YOUMI_NAME . '_' . 'effectscate', ['id' => $item['effectscate_id']]);
            $item['effectscatetitle'] = $effectscate['title'];
            $item['imgs'] = unserialize($item['imgs']);
        }
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
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(YOUMI_NAME . '_' . 'effects') . ' WHERE uniacid = :uniacid ' . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('effects');
} 


if ($op == 'post') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    
    $effectscates = pdo_getall(YOUMI_NAME . '_' . 'effectscate', ['uniacid' => $uniacid, 'status' => 1]);
                
    if (checksubmit('submit')) {
        $data = $_GPC['data'];
        
        
        $data['imgs'] = serialize($data['imgs']);
        $data['createtime'] = strtotime($data['createtime']);

        if (!empty($id)) {
		    
		    pdo_update(YOUMI_NAME . '_' . 'effects', $data, array('id' => $id));
		    $message = '修改';
		} else {
		    $data['createtime'] = TIMESTAMP;
            $data['uniacid'] = $uniacid;
            pdo_insert(YOUMI_NAME . '_' . 'effects', $data);
			$id = pdo_insertid();
            $message = '新增';
		}
		itoast('温馨提示：' . $message . '特效管理成功！',$this->createWebUrl('effects') , 'success');
    }
    $item = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_' . 'effects') . ' where id = :id limit 1 ', $paras);
    $item['imgs'] = unserialize($item['imgs']);
    include $this->template('effects');
} 


if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'effects') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：特效管理不存在或是已经被删除！', $this->createWebUrl('effects'), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'effects', ['status' => -1], array('id' => $id));
    itoast('温馨提示：特效管理删除成功！', $this->createWebUrl('effects'), 'success');
}
        
if ($op == 'search') {
    //选择用户

    $keyword = $_GPC['keyword'];

    $ds = $this->getMemberKeyword($keyword);

    die(json_encode(['status' => $ds ? 1 : 0, 'ds' => $ds]));

}
        