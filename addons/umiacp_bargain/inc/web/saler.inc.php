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
    $paras[':activity_id'] = $_GPC['activity_id'];
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
    $list = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'saler') . ' where uniacid = :uniacid and activity_id=:activity_id and shop_id = ' . $shop_id . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    foreach ($list as &$item) {

        $item['member'] = $this->getMemberInfo($item['mid']);
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
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(YOUMI_NAME . '_' . 'saler') . ' WHERE uniacid = :uniacid and activity_id=:activity_id and shop_id = ' . $shop_id . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('saler');
}


if ($op == 'post') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;

    if (checksubmit('submit')) {
        $data = $_GPC['data'];

        if (!empty($id)) {

            pdo_update(YOUMI_NAME . '_' . 'saler', $data, array('id' => $id));
            $message = '修改';
        } else {
            $data['createtime'] = TIMESTAMP;
            $data['uniacid'] = $uniacid;
            pdo_insert(YOUMI_NAME . '_' . 'saler', $data);
            $id = pdo_insertid();
            $message = '新增';
        }
        itoast('温馨提示：' . $message . '核销员成功！',$this->createWebUrl('saler',['activity_id'=>$data['activity_id']]) , 'success');
    }

    $item = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_' . 'saler') . ' where id = :id limit 1 ', $paras);

    $saler = $this->getMemberInfo($item['mid']);

    include $this->template('saler');
}


if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'saler') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：核销员不存在或是已经被删除！', referer(), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'saler', ['status' => -1], array('id' => $id));
    itoast('温馨提示：核销员删除成功！', referer(), 'success');
}

if ($op == 'search') {
    //选择用户

    $keyword = $_GPC['keyword'];

    $ds = $this->getMemberKeyword($keyword);

    die(json_encode(['status' => $ds ? 1 : 0, 'ds' => $ds]));

}
