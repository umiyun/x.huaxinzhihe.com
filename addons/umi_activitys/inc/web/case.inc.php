<?php

global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op      = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if ($op == 'display') {
    $condition         = '';
    $pindex            = max(1, intval($_GPC['page']));
    $psize             = !empty($_GPC['psize']) ? $_GPC['psize'] : 10;
    $paras[':uniacid'] = $uniacid;

    $status = intval($_GPC['status']);
    if ($status) {
        $condition .= ' and status = ' . $status;
    }
    else {
        $condition .= ' and status > -1 ';
    }
    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= " and title like '%{$keyword}%' ";
    }
    $orderby = ' order by sort desc,createtime desc';

    $list    = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'case') . ' where uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    foreach ($list as &$item) {

        if ($item['cate_id1']) {
            $cate1              = pdo_get(YOUMI_NAME . '_' . 'cate', ['id' => $item['cate_id1']]);
            $item['cate1title'] = $cate1['title'];
        }

        if ($item['cate_id2']) {
            $cate2              = pdo_get(YOUMI_NAME . '_' . 'cate', ['id' => $item['cate_id2']]);
            $item['cate2title'] = $cate2['title'];
        }

        if ($item['cate_id3']) {
            $cate3              = pdo_get(YOUMI_NAME . '_' . 'cate', ['id' => $item['cate_id3']]);
            $item['cate3title'] = $cate3['title'];
        }

        if ($item['cate_id4']) {
            $cate4              = pdo_get(YOUMI_NAME . '_' . 'cate', ['id' => $item['cate_id4']]);
            $item['cate4title'] = $cate4['title'];
        }

        if ($item['module']) {
            $module              = pdo_get(YOUMI_NAME . '_' . 'module', ['module' => $item['module']]);
            $item['moduletitle'] = $module['title'];
        }
        switch ($item['status']) {
            case 0 :
                $item['statusname'] = '否';
                break;
            case 1 :
                $item['statusname'] = '是';
                break;
            default :
                $item['statusname'] = '';
                break;
        }

        unset($item);
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(YOUMI_NAME . '_' . 'case') . ' WHERE uniacid = :uniacid ' . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('case');
}


if ($op == 'post') {
    $id           = intval($_GPC['id']);
    $paras[':id'] = $id;

    $cate1s = pdo_getall(YOUMI_NAME . '_' . 'cate', ['uniacid' => $uniacid, 'status' => 1, 'type' => 1]);

    $cate2s = pdo_getall(YOUMI_NAME . '_' . 'cate', ['uniacid' => $uniacid, 'status' => 1, 'type' => 2]);

    $cate3s = pdo_getall(YOUMI_NAME . '_' . 'cate', ['uniacid' => $uniacid, 'status' => 1, 'type' => 3]);

    $cate4s = pdo_getall(YOUMI_NAME . '_' . 'cate', ['uniacid' => $uniacid, 'status' => 1, 'type' => 4]);

    $templates = pdo_getall(YOUMI_NAME . '_' . 'template', ['uniacid' => $uniacid, 'status' => 1]);

    $module_data = array(
        'umiacp_bargain', 'umiacp_apply','umiacp_groupprepay','umiacp_lighten','umiacp_vote','umiacp_10second','umiacp_eggfreny','umiacp_speeddial','umiacp_roulette','umiacp_leapcliff','umiacp_fission','umiacp_bargainsimple'
    );
    $modules     = array();
    foreach ($module_data as $d) {
        $modu = pdo_get('modules', array('name' => $d));
        $act_modu = pdo_get(YOUMI_NAME . '_' . 'module', array('module' => $d,'uniacid'=>$uniacid));
        if (!empty($modu)){
            $temp = array(
                'title'  => $modu['title'],
                'module' => $modu['name'],
                'uniacid'=>$uniacid
            );
            if(empty($act_modu)){
                pdo_insert(YOUMI_NAME . '_' . 'module',$temp);
            }
            $modules[]=$temp;
        }

    }
//    $modules = pdo_getall(YOUMI_NAME . '_' . 'module', ['uniacid' => $uniacid]);

    if (checksubmit('submit')) {
        $data = $_GPC['data'];


        if (!empty($id)&&empty($_GPC['type'])) {

            pdo_update(YOUMI_NAME . '_' . 'case', $data, array('id' => $id));
            $message = '修改';
        }else {
            $data['createtime'] = TIMESTAMP;
            $data['uniacid']    = $uniacid;
            pdo_insert(YOUMI_NAME . '_' . 'case', $data);
            $id      = pdo_insertid();
            $message = '新增';
        }
        itoast('温馨提示：' . $message . '案例成功！', $this->createWebUrl('case'), 'success');
    }
    $item = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_' . 'case') . ' where id = :id limit 1 ', $paras);

    include $this->template('case');
}


if ($op == 'del') {
    $id           = intval($_GPC['id']);
    $paras[':id'] = $id;
    $item         = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'case') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：案例不存在或是已经被删除！', $this->createWebUrl('case'), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'case', ['status' => -1], array('id' => $id));
    itoast('温馨提示：案例删除成功！',$this->createWebUrl('case'), 'success');
}

if ($op == 'search') {
    //选择用户

    $keyword = $_GPC['keyword'];

    $ds = $this->getMemberKeyword($keyword);

    die(json_encode(['status' => $ds ? 1 : 0, 'ds' => $ds]));

}
        