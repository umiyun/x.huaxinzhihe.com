<?php

global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if ($op == 'display') {

    $pindex = max(1, intval($_GPC['page']));
    $psize = !empty($_GPC['psize']) ? $_GPC['psize'] : 10;

    $condition = '';

    $paras[':uniacid'] = $uniacid;

    $activity_id = intval($_GPC['id']);
    if ($activity_id) {
        $condition .= ' and c.activity_id = ' . $activity_id;
    }

    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= " and (a.title like '%{$keyword}%' or c.nickname like '%{$keyword}%' or c.group_id like '%{$keyword}%' ) ";
    }
    $orderby = ' order by ';

    $orderby .= ' createtime desc ';
    $list = pdo_fetchall('SELECT a.title as activitytitle,c.* FROM ' . tablename(YOUMI_NAME . '_' . 'group') . ' c left join ' . tablename(YOUMI_NAME . '_' . 'activity') . ' a on c.activity_id = a.id where c.uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);

    $total = pdo_fetchcolumn('SELECT COUNT(c.id) FROM ' . tablename(YOUMI_NAME . '_' . 'group') . ' c left join ' . tablename(YOUMI_NAME . '_' . 'activity') . ' a on c.activity_id = a.id where c.uniacid = :uniacid ' . $condition, $paras);

    $pager = pagination($total, $pindex, $psize);
    include $this->template('group');
}

if ($op == 'download') {

    $condition = '';
    $activity_id = $_GPC['id'];
    $paras[':uniacid'] = $uniacid;

    $activity_id = intval($_GPC['id']);
    if ($activity_id) {
        $condition .= ' and c.activity_id = ' . $activity_id;
    }
    $status = intval($_GPC['status']);
    if ($status) {
        $condition .= ' and c.status = ' . $status;
    } else {
        $condition .= ' and c.status > -1 ';
    }
    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= " and (a.title like '%{$keyword}%' or c.realname like '%{$keyword}%' or c.mobile like '%{$keyword}%') ";
    }
    $orderby = ' order by ';

    $orderby .= ' createtime desc ';
    $list = pdo_fetchall('SELECT a.title as activitytitle,c.* FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' c left join ' . tablename(YOUMI_NAME . '_' . 'activity') . ' a on c.activity_id = a.id where c.uniacid = :uniacid ' . $condition . $orderby, $paras);

    foreach ($list as &$item){
//        switch ($item['pay_type']){
//            case 1:
//                $item['group_type']='团长';
//            case 2:
//                $item['group_type']='团员';
//            case 3:
//                $item['group_type']='单买';
//        }
        $item['fmember']=$this->getMemberInfo($item['fmid']);
        $item['f_nickname']=$item['fmember']['nickname'];
        if($item['leader']){
            $item['group_type']='团长';
        }else{
            $item['group_type']='团员';
        }
        if ($item['group_id']==0){
            $item['group_type']='单买';
    }
    }
    unset($item);
    $header = [
        '活动',
        '姓名',
        '手机号',
        '团购信息',
        '金额',
        '推荐者',
        '佣金',
        '发起时间',
    ];

    $types = [
        ['activitytitle', 300],
        ['realname', 200],
        ['mobile', 200],
        ['group_type', 200],
        ['price', 200],
        ['f_nickname', 200],
        ['commission', 200],
        ['createtime', 200, 'date'],
    ];

    $this->download('报名记录', $list, $header, $types);

}



if ($op == 'audit') {
    $id = intval($_GPC['id']);
    $status = intval($_GPC['status']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'cut') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：报名人员不存在或是已经被删除！', referer(), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'cut', ['status' => $status], array('id' => $id));
    itoast('温馨提示：操作成功！', referer(), 'success');
}

if ($op == 'search') {
    //选择用户

    $keyword = $_GPC['keyword'];

    $ds = $this->getMemberKeyword($keyword);

    die(json_encode(['status' => $ds ? 1 : 0, 'ds' => $ds]));

}
