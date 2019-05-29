<?php

global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if ($op == 'display') {
    $condition = ' and shop_id=0 ';
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
    $list = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'complain') . ' where uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);

    foreach ($list as &$item) {
        $item['member'] = $this->getMemberInfo($item['userinfo']);
//        if ($item['activity_id']) {
//            $activity = pdo_get(YOUMI_NAME . '_' . 'activity', ['id' => $item['activity_id']]);
//            $item['activitytitle'] = $activity['title'];
//        }
//
//        if ($item['shop_id']) {
//            $shop = pdo_get(YOUMI_NAME . '_' . 'shop', ['id' => $item['shop_id']]);
//            $item['shoptitle'] = $shop['title'];
//        }
//
        unset($item);
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(YOUMI_NAME . '_' . 'complain') . ' WHERE uniacid = :uniacid ' . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('complain');
} 


if ($op == 'post') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    
    $activitys = pdo_getall(YOUMI_NAME . '_' . 'activity', ['uniacid' => $uniacid, 'status' => 1]);
                
    $shops = pdo_getall(YOUMI_NAME . '_' . 'shop', ['uniacid' => $uniacid, 'status' => 1]);
                
    if (checksubmit('submit')) {
        $data = $_GPC['data'];
        
        
        if (!empty($id)) {
		    
		    pdo_update(YOUMI_NAME . '_' . 'complain', $data, array('id' => $id));
		    $message = '修改';
		} else {
		    $data['createtime'] = TIMESTAMP;
            $data['uniacid'] = $uniacid;
            pdo_insert(YOUMI_NAME . '_' . 'complain', $data);
			$id = pdo_insertid();
            $message = '新增';
		}
		itoast('温馨提示：' . $message . '投诉管理成功！',$this->createWebUrl('complain') , 'success');
    }
    $item = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_' . 'complain') . ' where id = :id limit 1 ', $paras);
    
    include $this->template('complain');
} 


if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'complain') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：投诉管理不存在或是已经被删除！', referer(), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'complain', ['status' => -1], array('id' => $id));
    itoast('温馨提示：投诉管理删除成功！', referer(), 'success');
}
        
if ($op == 'search') {
    //选择用户

    $keyword = $_GPC['keyword'];

    $ds = $this->getMemberKeyword($keyword);

    die(json_encode(['status' => $ds ? 1 : 0, 'ds' => $ds]));

}
        