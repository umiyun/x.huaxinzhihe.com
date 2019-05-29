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
    $list = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'member') . ' where uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    foreach ($list as &$item) {

        if ($item['openid']) {
            $user_type = 2;
        } else if ($item['wxopenid']) {
            $user_type = 3;
        }
        $item['member'] = $this->getMemberInfo($item['mid'], $user_type);

        switch ($item['status']) {
            case 1 :
                $item['statusname'] = '正常';
                break;
            case 2 :
                $item['statusname'] = '黑名单';
                break;
            default :
                $item['statusname'] = '';
                break;
        }
        
        unset($item);
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(YOUMI_NAME . '_' . 'member') . ' WHERE uniacid = :uniacid ' . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('member');
}

if ($op == 'post') {
    $mid = intval($_GPC['mid']);
    $paras[':mid'] = $mid;
    
    if (checksubmit('submit')) {
        $data = $_GPC['data'];
        
        
        if (!empty($mid)) {
		    
		    pdo_update(YOUMI_NAME . '_' . 'member', $data, array('mid' => $mid));
		    $message = '修改';
		} else {
		    $data['createtime'] = TIMESTAMP;
            $data['uniacid'] = $uniacid;
            pdo_insert(YOUMI_NAME . '_' . 'member', $data);
			$mid = pdo_insertid();
            $message = '新增';
		}
		itoast('温馨提示：' . $message . '用户成功！',$this->createWebUrl('member') , 'success');
    }
    $item = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_' . 'member') . ' where mid = :mid limit 1 ', $paras);
    
    include $this->template('member');
} 


if ($op == 'update_black') {

    $mid = intval($_GPC['mid']);
    $paras[':mid'] = $mid;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'member') . ' WHERE mid = :mid limit 1 ', $paras);
    $status = $item['status'] == 1 ? 2 : 1;
    pdo_update(YOUMI_NAME . '_' . 'member', ['status' => $status], array('mid' => $mid));
    itoast('温馨提示：用户操作成功！', $this->createWebUrl('member'), 'success');
}


if ($op == 'del') {
    $mid = intval($_GPC['mid']);
    $paras[':mid'] = $mid;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'member') . ' WHERE mid = :mid limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：用户不存在或是已经被删除！', $this->createWebUrl('member'), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'member', ['status' => -1], array('mid' => $mid));
    itoast('温馨提示：用户删除成功！', $this->createWebUrl('member'), 'success');
}
        
if ($op == 'search') {
    //选择用户

    $keyword = $_GPC['keyword'];

    $ds = $this->getMemberKeyword($keyword);

    die(json_encode(['status' => $ds ? 1 : 0, 'ds' => $ds]));

}
        