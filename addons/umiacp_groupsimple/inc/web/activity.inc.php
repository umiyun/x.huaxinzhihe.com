<?php

global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

$shop_id = intval($_GPC['shop_id']);

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
    $list = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'activity') . ' where uniacid = :uniacid and shop_id = ' . $shop_id . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    foreach ($list as &$item) {
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
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(YOUMI_NAME . '_' . 'activity') . ' WHERE uniacid = :uniacid and shop_id = ' . $shop_id . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('activity');
}


if ($op == 'post') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;

    if (checksubmit('submit')) {
        $data = $_GPC['data'];

        $data['starttime'] = strtotime($data['starttime']);
        $data['endtime'] = strtotime($data['endtime']);
        $data['share_img'] = tomedia($data['share_img']);
        $data['shop_imgs'] = serialize($data['shop_imgs']);
        $data['effects_imgs'] = serialize($data['effects_imgs']);
        $data['userinfo'] = serialize($data['userinfo']);
        $data['r_address']=serialize($_GPC['re_address']);
        $data['shop_province'] = $_GPC['district']['province'];
        $data['shop_city'] = $_GPC['district']['city'];
        $data['shop_district'] = $_GPC['district']['district'];

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

        itoast('温馨提示：' . $message . '活动成功！', $this->createWebUrl('activity'), 'success');
    }
    $item = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_' . 'activity') . ' where id = :id and shop_id = 0 limit 1 ', $paras);

    $item['shop_imgs'] = unserialize($item['shop_imgs']);
    $item['effects_imgs'] = unserialize($item['effects_imgs']);
    $item['r_address'] = unserialize($item['r_address']);
    $item['userinfo'] = unserialize($item['userinfo']);
    $district['province'] = $item['shop_province'];
    $district['city'] = $item['shop_city'];
    $district['district'] = $item['shop_district'];
    


    include $this->template('activity');
}


if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'activity') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：活动不存在或是已经被删除！', referer(), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'activity', ['status' => -1], array('id' => $id));
    itoast('温馨提示：活动删除成功！', referer(), 'success');
}

if ($op == 'search') {
    //选择用户

    $keyword = $_GPC['keyword'];

    $ds = $this->getMemberKeyword($keyword);

    die(json_encode(['status' => $ds ? 1 : 0, 'ds' => $ds]));

}
