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
    $list = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'help') . ' where uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    foreach ($list as &$item) {
        
        if ($item['helpcate_id']) {
            $helpcate = pdo_get(YOUMI_NAME . '_' . 'helpcate', ['id' => $item['helpcate_id']]);
            $item['helpcatetitle'] = $helpcate['title'];
        }
        switch ($item['status']) {
            case 1 :
                $item['statusname'] = '启用';
                break;
            case 0 :
                $item['statusname'] = '禁用';
                break;
            default :
                $item['statusname'] = '';
                break;
        }
        
        unset($item);
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(YOUMI_NAME . '_' . 'help') . ' WHERE uniacid = :uniacid ' . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('help');
} 


if ($op == 'post') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    
    $helpcates = pdo_getall(YOUMI_NAME . '_' . 'helpcate', ['uniacid' => $uniacid, 'status' => 1]);
                
    if (checksubmit('submit')) {
        $data = $_GPC['data'];
        
        
        if (!empty($id)) {
		    
		    pdo_update(YOUMI_NAME . '_' . 'help', $data, array('id' => $id));
		    $message = '修改';
		} else {
		    $data['createtime'] = TIMESTAMP;
            $data['uniacid'] = $uniacid;
            pdo_insert(YOUMI_NAME . '_' . 'help', $data);
			$id = pdo_insertid();
            $message = '新增';
		}
		itoast('温馨提示：' . $message . '帮助成功！',$this->createWebUrl('help') , 'success');
    }
    $item = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_' . 'help') . ' where id = :id limit 1 ', $paras);
    
    include $this->template('help');
} 


if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'help') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：帮助不存在或是已经被删除！', $this->createWebUrl('help'), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'help', ['status' => -1], array('id' => $id));
    itoast('温馨提示：帮助删除成功！', $this->createWebUrl('help'), 'success');
}
        
if ($op == 'search') {
    //选择用户

    $keyword = $_GPC['keyword'];

    $ds = $this->getMemberKeyword($keyword);

    die(json_encode(['status' => $ds ? 1 : 0, 'ds' => $ds]));

}
        