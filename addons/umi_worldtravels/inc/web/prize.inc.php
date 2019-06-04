<?php

global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

$activity_id = intval($_GPC['activity_id']);

if ($op == 'display') {
    $condition = '';
    $pindex = max(1, intval($_GPC['page']));
    $psize = !empty($_GPC['psize']) ? $_GPC['psize'] : 10;
    $paras[':uniacid'] = $uniacid;

    if ($activity_id) {
        $condition .= ' and activity_id = ' . $activity_id;
    }

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
    $list = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'activity_prize') . ' where uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    foreach ($list as &$item) {

        if ($item['activity_id']) {
            $activity = pdo_get(YOUMI_NAME . '_' . 'activity', ['id' => $item['activity_id']]);
            $item['activitytitle'] = $activity['title'];
        }
        switch ($item['status']) {
            case 0 :
                $item['statusname'] = '未中奖';
                break;
            case 1 :
                $item['statusname'] = '中奖';
                break;
            default :
                $item['statusname'] = '';
                break;
        }

        unset($item);
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(YOUMI_NAME . '_' . 'activity_prize') . ' WHERE uniacid = :uniacid ' . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('prize');
}


if ($op == 'post') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;

    $activitys = pdo_getall(YOUMI_NAME . '_' . 'activity', ['uniacid' => $uniacid, 'status' => 1]);

    if (checksubmit('submit')) {
        $data = $_GPC['data'];
        $data['uniacid'] = $uniacid;

        if (!empty($id)) {

		    pdo_update(YOUMI_NAME . '_' . 'activity_prize', $data, array('id' => $id));
		    $message = '修改';
		} else {
		    $data['createtime'] = TIMESTAMP;
            pdo_insert(YOUMI_NAME . '_' . 'activity_prize', $data);
			$id = pdo_insertid();
            $message = '新增';
		}
		itoast('温馨提示：' . $message . '奖品成功！',$this->createWebUrl('prize') , 'success');
    }
    $item = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_' . 'activity_prize') . ' where id = :id limit 1 ', $paras);
    if (!$item && $activity_id) {
        $item['activity_id'] = $activity_id;
    }
    include $this->template('prize');
}


if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'activity_prize') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：奖品不存在或是已经被删除！', referer(), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'activity_prize', ['status' => -1], array('id' => $id));
    itoast('温馨提示：奖品删除成功！', referer(), 'success');
}
