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

    $list = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'prize') . ' where uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    foreach ($list as &$item) {
        switch ($item['status']) {
            case 0 :
                $item['statusname'] = '未启用';
                break;
            case 1 :
                $item['statusname'] = '启用';
                break;
            default :
                $item['statusname'] = '';
                break;
        }

        unset($item);
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(YOUMI_NAME . '_' . 'prize') . ' WHERE uniacid = :uniacid ' . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('prize');
} 


if ($op == 'post') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    
    if (checksubmit('submit')) {
        $data = $_GPC['data'];
        $data['starttime']=strtotime($data['starttime']);
        $data['endtime']=strtotime($data['endtime']);
        if (!empty($id)) {
		    
		    pdo_update(YOUMI_NAME . '_' . 'prize', $data, array('id' => $id));
		    $message = '修改';
		} else {
		    $data['createtime'] = TIMESTAMP;
            $data['uniacid'] = $uniacid;
            pdo_insert(YOUMI_NAME . '_' . 'prize', $data);
			$id = pdo_insertid();
            $message = '新增';
		}
		itoast('温馨提示：' . $message . '课程成功！',$this->createWebUrl('prize') , 'success');
    }
    $item = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_' . 'prize') . ' where id = :id limit 1 ', $paras);
    
    include $this->template('prize');
} 


if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'prize') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：课程不存在或是已经被删除！', referer(), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'prize', ['status' => -1], array('id' => $id));
    itoast('温馨提示：课程删除成功！', referer(), 'success');
}
        
if ($op == 'search') {
    //选择用户

    $keyword = $_GPC['keyword'];

    $ds = $this->getMemberKeyword($keyword);

    die(json_encode(['status' => $ds ? 1 : 0, 'ds' => $ds]));

}
        