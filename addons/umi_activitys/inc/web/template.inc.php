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

    $modules = pdo_getall(YOUMI_NAME . '_' . 'module', array('uniacid'=>$uniacid));


    $list = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'template') . ' where uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    foreach ($list as &$item) {
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
        $modules_new=$modules;
        $modules_all=pdo_getall(YOUMI_NAME . '_' . 'template_module', array('template_id' =>$item['id']), array('module'));
        $modules_all_new=array();
        foreach ($modules_all as $mad){
            $modules_all_new[]=$mad['module'];
            unset($mad);
        }
        foreach ($modules_new as &$mv){
            $isin = in_array($mv['module'],$modules_all_new);
            !!$isin?$mv['checked']=1:$mv['checked']=0;
            unset($isin);
            unset($mv);
        }
        $item['module']=$modules_new;

        unset($modules_new);
        unset($modules_all);
        unset($modules_all_new);
        unset($item);
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(YOUMI_NAME . '_' . 'template') . ' WHERE uniacid = :uniacid ' . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('template');
} 


if ($op == 'post') {
    $id = intval($_GPC['id']);

    $paras[':id'] = $id;
    $modules = pdo_getall(YOUMI_NAME . '_' . 'module', array('uniacid'=>$uniacid));

    $item = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_' . 'template') . ' where id = :id limit 1 ', $paras);
    $modules_all=pdo_getall(YOUMI_NAME . '_' . 'template_module', array('template_id' =>$id), array('module'));
    $modules_all_new=array();
    foreach ($modules_all as $mad){
        $modules_all_new[]=$mad['module'];
        unset($mad);
    }
    foreach ($modules as &$mv){
        $isin = in_array($mv['module'],$modules_all_new);
        if($id){
            !!$isin?$mv['checked']=1:$mv['checked']=0;
        }else{
            $mv['checked']=0;
        }

        unset($isin);
        unset($mv);
    }

    if (checksubmit('submit')) {
        $data = $_GPC['data'];
        $modules_checked = $_GPC['module'];
        if (!empty($id)) {
            pdo_delete(YOUMI_NAME . '_' . 'template_module', array('template_id' =>$id));
		    pdo_update(YOUMI_NAME . '_' . 'template', $data, array('id' => $id));
		    $message = '修改';
		} else {
		    $data['createtime'] = TIMESTAMP;
            $data['uniacid'] = $uniacid;
            pdo_insert(YOUMI_NAME . '_' . 'template', $data);
			$id_new = pdo_insertid();
            $message = '新增';
		}
        if($modules_checked){
           foreach ($modules_checked as &$mv){
               if($id||$id_new) {
                   $data = array(
                       'uniacid' => $uniacid,
                       'template_id' => ($id ? $id : $id_new),
                       'module' => $mv,
                       'createtime' => TIMESTAMP
                   );
                   pdo_insert(YOUMI_NAME . '_' . 'template_module', $data);
                   unset($mv);
               }
           }
        }

        itoast('温馨提示：' . $message . '模版成功！',$this->createWebUrl('template') , 'success');
    }

    include $this->template('template');
} 


if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'template') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：模版不存在或是已经被删除！', $this->createWebUrl('template'), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'template', ['status' => -1], array('id' => $id));
    itoast('温馨提示：模版删除成功！', $this->createWebUrl('template'), 'success');
}
        
if ($op == 'search') {
    //选择用户

    $keyword = $_GPC['keyword'];

    $ds = $this->getMemberKeyword($keyword);

    die(json_encode(['status' => $ds ? 1 : 0, 'ds' => $ds]));

}
        