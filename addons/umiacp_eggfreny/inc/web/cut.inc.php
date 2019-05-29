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
    $list = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'cut') . ' where uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    foreach ($list as &$item) {
        
        if ($item['activity_id']) {
            $activity = pdo_get(YOUMI_NAME . '_' . 'activity', ['id' => $item['activity_id']]);
            $item['activitytitle'] = $activity['title'];
        }
        switch ($item['status']) {
            case 1 :
                $item['statusname'] = '已报名';
                break;
            case 2 :
                $item['statusname'] = '已支付';
                break;
            case 3 :
                $item['statusname'] = '已购买';
                break;
            case 4 :
                $item['statusname'] = '';
                break;
            default :
                $item['statusname'] = '';
                break;
        }
        
        unset($item);
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(YOUMI_NAME . '_' . 'cut') . ' WHERE uniacid = :uniacid ' . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('cut');
} 


if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'cut') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：报名不存在或是已经被删除！', referer(), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'cut', ['status' => -1], array('id' => $id));
    itoast('温馨提示：报名删除成功！', referer(), 'success');
}
        
if ($op == 'search') {
    //选择用户

    $keyword = $_GPC['keyword'];

    $ds = $this->getMemberKeyword($keyword);

    die(json_encode(['status' => $ds ? 1 : 0, 'ds' => $ds]));

}
        