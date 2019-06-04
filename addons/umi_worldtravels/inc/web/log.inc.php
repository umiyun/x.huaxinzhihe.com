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
    $list = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'prize_log') . ' where uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    foreach ($list as &$item) {

        $item['member'] = $this->getMemberInfo($item['openid'], 2);
        switch ($item['status']) {
            case 0 :
                $item['statusname'] = '未中奖';
                break;
            case 1 :
                $item['statusname'] = '已中奖';
                break;
            case 2 :
                $item['statusname'] = '已发奖';
                break;
            default :
                $item['statusname'] = '';
                break;
        }

        unset($item);
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(YOUMI_NAME . '_' . 'prize_log') . ' WHERE uniacid = :uniacid ' . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('log');
}

if ($op == 'update_status') {

    $id = $_GPC['id'];
    $status = $_GPC['status'];

    $prize_log = pdo_get(YOUMI_NAME . '_' . 'prize_log' , ['id' => $id]);
    if ($prize_log['status'] == $status) {
        itoast('温馨提示：已发奖，请勿重复操作', referer(), 'error');
    }

    $res = pdo_update(YOUMI_NAME . '_' . 'prize_log', ['status' => $status], ['id' => $id]);
    itoast('温馨提示：发奖' . ($res ? '成功' : '失败'), referer(), $res ? 'success' : 'error');
}

if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'prize_log') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：抽奖记录不存在或是已经被删除！', referer(), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'prize_log', ['status' => -1], array('id' => $id));
    itoast('温馨提示：抽奖记录删除成功！', referer(), 'success');
}

if ($op == 'download') {


    $sql = 'SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'prize_log') . " WHERE uniacid = :uniacid and status > -1 order by id DESC ";
    $params = array(':uniacid' => $uniacid);
    $list = pdo_fetchall($sql, $params);

    foreach ($list as &$item) {

        $item['member'] = $this->getMemberInfo($item['openid'], 2);
        switch ($item['status']) {
            case 0 :
                $item['statusname'] = '未中奖';
                break;
            case 1 :
                $item['statusname'] = '已中奖';
                break;
            case 2 :
                $item['statusname'] = '已发奖';
                break;
            default :
                $item['statusname'] = '';
                break;
        }

        unset($item);
    }


    $header = [
        '姓名',
        '电话',
        '奖品',
        '状态',
        '抽奖时间',
    ];

    $types = [
        ['realname', 300],
        ['mobile', 200],
        ['title', 500],
        ['statusname', 100],
        ['createtime', 200, 'date'],
    ];

    $this->download('报名记录', $list, $header, $types);
}
