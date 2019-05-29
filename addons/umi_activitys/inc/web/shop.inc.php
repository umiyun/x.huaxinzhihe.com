<?php

global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$setting = youmi_setting_get_list();
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
    $list = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'shop') . ' where uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    foreach ($list as &$item) {

        $item['member'] = $this->getMemberInfo($item['mid']);

        if ($item['industry_id']) {
            $industry = pdo_get(YOUMI_NAME . '_' . 'industry', ['id' => $item['industry_id']]);
            $item['industrytitle'] = $industry['title'];
        }
        switch ($item['status']) {
            case 1 :
                $item['statusname'] = '待审核';
                break;
            case 2 :
                $item['statusname'] = '审核通过';
                break;
            case 3 :
                $item['statusname'] = '拒绝入驻';
                break;
            default :
                $item['statusname'] = '';
                break;
        }

        unset($item);
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(YOUMI_NAME . '_' . 'shop') . ' WHERE uniacid = :uniacid ' . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('shop');
}


if ($op == 'post') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;

    $industrys = pdo_getall(YOUMI_NAME . '_' . 'industry', ['uniacid' => $uniacid, 'status' => 1]);

    if (checksubmit('submit')) {
        $data = $_GPC['data'];


        $map = $_GPC['map'];
        $district = $_GPC['district'];
        $data['lat'] = $map['lat'];
        $data['lng'] = $map['lng'];
        $data['province'] = $district['province'];
        $data['city'] = $district['city'];
        $data['district'] = $district['district'];
        if (!empty($id)) {
            $dbStatus = pdo_getcolumn(YOUMI_NAME . '_' . 'shop', array('id' => $id), 'status', 1);
            youmi_internal_log('777',array($dbStatus,$data['status']) );
            if ($dbStatus == 1) {
                youmi_internal_log('777', '未审核');
                if ($data['status'] == 2) {
                    youmi_internal_log('777', '已经审核');
                    if (intval($setting['vip_days'])  > 0) {
                        youmi_internal_log('777', '会员天数');
                        $curTime = time();
                        $data['starttime'] = $curTime;
                        $data['endtime'] = $curTime + 3600 * 24 * $setting['vip_days'];
                    }
                }
            }
            youmi_internal_log('777', $data);
            pdo_update(YOUMI_NAME . '_' . 'shop', $data, array('id' => $id));
            $message = '修改';
        } else {
            $data['createtime'] = TIMESTAMP;
            $data['uniacid'] = $uniacid;
            pdo_insert(YOUMI_NAME . '_' . 'shop', $data);
            $id = pdo_insertid();
            $message = '新增';
        }
        itoast('温馨提示：' . $message . '商家成功！', $this->createWebUrl('shop'), 'success');
    }
    $item = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_' . 'shop') . ' where id = :id limit 1 ', $paras);

    $district['province'] = $item['province'];
    $district['city'] = $item['city'];
    $district['district'] = $item['district'];

    include $this->template('shop');
}


if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'shop') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：商家不存在或是已经被删除！', $this->createWebUrl('shop'), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'shop', ['status' => -1], array('id' => $id));
    itoast('温馨提示：商家拉黑成功！', $this->createWebUrl('shop'), 'success');
}

if ($op == 'search') {
    //选择用户

    $keyword = $_GPC['keyword'];

    $ds = $this->getMemberKeyword($keyword);

    die(json_encode(['status' => $ds ? 1 : 0, 'ds' => $ds]));

}
if ($op == 'renew') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    if (checksubmit('submit')) {
        $data = $_GPC['data'];
        if (!empty($id)) {
            $shop =pdo_get(YOUMI_NAME . '_' . 'shop', array('id' => $id));
            if(!$shop['endtime']){
                $shop['endtime']=time();
            }
            if ($shop['status'] == 2) {
                $endtime_new= strtotime($data['days']."day",$shop['endtime']);
                pdo_update(YOUMI_NAME . '_' . 'shop', ['endtime'=>$endtime_new], array('id' => $id));
                pdo_insert(YOUMI_NAME . '_' . 'order', array(
                    'uniacid'=>$uniacid,
                    'mid'=>$shop['mid'],
                    'openid'=>$shop['openid'],
                    'meal_id'=>-1,
                    'title'=>'手动续期',
                    'status'=>'2',
                    'ordersn'=>'',
                    'tid'=>'',
                    'createtime'=>time(),
                    'days'=>$data['days']
                ));
                itoast('温馨提示：商户续期成功！', $this->createWebUrl('shop'), 'success');
            }
        }
    }
    include $this->template('shop');
}