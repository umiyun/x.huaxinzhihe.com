<?php

global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

$modules= pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'module') . ' where uniacid = :uniacid ' , [':uniacid'=>$uniacid]);
$shops= pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'shop') . ' where uniacid = :uniacid ' , [':uniacid'=>$uniacid]);

if ($op == 'display') {
    $condition = '';
    $pindex = max(1, intval($_GPC['page']));
    $psize = !empty($_GPC['psize']) ? $_GPC['psize'] : 10;
    $paras[':uniacid'] = $uniacid;

    $status = intval($_GPC['status']);
    $shop_id=intval($_GPC['shop_id']);
    $module=trim($_GPC['module']);
    if ($status) {
        $condition .= ' and status = ' . $status;
    } else {
        $condition .= ' and status > -1 ';
    }
    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= " and title like '%{$keyword}%' ";
    }
   if($shop_id){
       $condition .= ' and shop_id = ' . $shop_id;
   }else{
       $condition .= ' and shop_id >0 ';
   }
   if($module){
       $condition .= ' and module = "' . $module.'"';
   }
    $orderby = ' order by ';
    
    $orderby .= ' createtime desc ';
    $list = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'activity') . ' where uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    foreach ($list as &$item) {
        
        if ($item['shop_id']) {
            $shop = pdo_get(YOUMI_NAME . '_' . 'shop', ['id' => $item['shop_id']]);
            $item['shoptitle'] = $shop['title'];
        }
        
        if ($item['activity_id']) {
            $activity = pdo_get(YOUMI_NAME . '_' . 'activity', ['id' => $item['activity_id']]);
            $item['activitytitle'] = $activity['title'];
        }

            $module1 = pdo_get(YOUMI_NAME . '_' . 'module', ['module' => $item['module']]);
            $item['moduletitle'] = $module1['title'];

        switch ($item['status']) {
            case 1 :
                $item['statusname'] = '上架';
                break;
            case 2 :
                $item['statusname'] = '下架';
                break;
            case 3 :
                $item['statusname'] = '未开始';
                break;
            default :
                $item['statusname'] = '';
                break;
        }
        
        unset($item);
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(YOUMI_NAME . '_' . 'activity') . ' WHERE uniacid = :uniacid ' . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('activitys');
} 


if ($op == 'post') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    
    $shops = pdo_getall(YOUMI_NAME . '_' . 'shop', ['uniacid' => $uniacid, 'status' => 1]);
                
    $cases = pdo_getall(YOUMI_NAME . '_' . 'case', ['uniacid' => $uniacid, 'status' => 1]);
                
    $activitys = pdo_getall(YOUMI_NAME . '_' . 'activity', ['uniacid' => $uniacid, 'status' => 1]);
                
    if (checksubmit('submit')) {
        $data = $_GPC['data'];
        
        
        $data['createtime'] = strtotime($data['createtime']);
                
        if (!empty($id)) {
		    
		    pdo_update(YOUMI_NAME . '_' . 'activity', $data, array('id' => $id));
		    $message = '修改';
		} else {
		    $data['createtime'] = TIMESTAMP;
            $data['uniacid'] = $uniacid;
            pdo_insert(YOUMI_NAME . '_' . 'activity', $data);
			$id = pdo_insertid();
            $message = '新增';
		}
		itoast('温馨提示：' . $message . '活动管理成功！',$this->createWebUrl('activitys') , 'success');
    }
    $item = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_' . 'activity') . ' where id = :id limit 1 ', $paras);
    
    include $this->template('activitys');
} 


if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'activity') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：活动管理不存在或是已经被删除！', $this->createWebUrl('activitys'), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'activity', ['status' => -1], array('id' => $id));
    itoast('温馨提示：活动管理删除成功！', $this->createWebUrl('activitys'), 'success');
}
        
if ($op == 'search') {
    //选择用户

    $keyword = $_GPC['keyword'];

    $ds = $this->getMemberKeyword($keyword);

    die(json_encode(['status' => $ds ? 1 : 0, 'ds' => $ds]));

}
        