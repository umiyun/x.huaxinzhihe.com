<?php

global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

$modules = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'module') . ' where uniacid = :uniacid ', [':uniacid' => $uniacid]);
$shops = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'shop') . ' where uniacid = :uniacid ', [':uniacid' => $uniacid]);

if ($op == 'display') {
    $condition = '';
    $pindex = max(1, intval($_GPC['page']));
    $psize = !empty($_GPC['psize']) ? $_GPC['psize'] : 10;
    $paras[':uniacid'] = $uniacid;
    $module = trim($_GPC['module']);
    $isdownload = intval($_GPC['isdownload']);
    $status = intval($_GPC['status']);
    $shop_id = intval($_GPC['shop_id']);
    $activity_id = intval($_GPC['activity_id']);
    $activity = pdo_get($module . '_' . 'activity', ['id' => $activity_id]);

    if ($status) {
        $condition .= ' and status = ' . $status;
    } else {
        $condition .= ' and status > -1 ';
    }
    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= " and title like '%{$keyword}%' ";
    }
    $condition .= ' and activity_id = ' . $activity_id;
    $orderby = ' order by ';

    $orderby .= ' createtime desc ';
    if($isdownload==0){
        $limit=' LIMIT ' . ($pindex - 1) * $psize. ',' . $psize;
    }


    if (in_array($module, ['umiacp_10second', 'umiacp_eggfreny'])) {
        $list = pdo_fetchall('SELECT * FROM ' . tablename($module . '_reward').' where uniacid = :uniacid ' . $condition . $orderby . $limit , $paras);
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($module . '_reward') . ' WHERE uniacid = :uniacid ' . $condition, $paras);
    } else if(in_array($module, ['umiacp_groupsimple'])){
        $list = pdo_fetchall('SELECT * FROM ' . tablename($module . '_order').' where uniacid = :uniacid ' . $condition . $orderby . $limit , $paras);
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($module . '_order') . ' WHERE uniacid = :uniacid ' . $condition, $paras);



    }else {
        $list = pdo_fetchall('SELECT * FROM ' . tablename($module . '_cut').' where uniacid = :uniacid ' . $condition . $orderby . $limit , $paras);
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($module . '_cut') . ' WHERE uniacid = :uniacid ' . $condition, $paras);
    }
    foreach ($list as &$item) {

        if ($module == 'umiacp_vote') {
            $item['vote_imgs']=unserialize($item['vote_imgs']);
        }

        if ($item['shop_id']) {
            $shop = pdo_get(YOUMI_NAME . '_' . 'shop', ['id' => $item['shop_id']]);
            $item['shoptitle'] = $shop['title'];
        }
        $item['activitytitle']=$activity['title'];
        $module1 = pdo_get(YOUMI_NAME . '_' . 'module', ['module' => $item['module']]);
        $item['moduletitle'] = $module1['title'];
        switch ($module) {
            case 'umiacp_vote' :
                switch ($item['status']) {
                    case 1 :
                        $item['statusname'] = '审核中';
                        break;
                    case 2 :
                        $item['statusname'] = '通过';
                        break;
                    case 3 :
                        $item['statusname'] = '拒绝';
                        break;
                    default :
                        $item['statusname'] = '';
                        break;
                }
                break;
            case 'umiacp_bargain' :
                switch ($item['status']) {
                    case 1 :
                        $item['statusname'] = '砍价中';
                        break;
                    case 2 :
                        $item['statusname'] = '砍到底';
                        break;
                    case 3 :
                        $item['statusname'] = '已购买';
                        break;
                    default :
                        $item['statusname'] = '';
                        break;
                }
                break;
            case 'umiacp_apply' :
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
                        $item['statusname'] = '已取消';
                        break;
                    default :
                        $item['statusname'] = '';
                        break;
                }
                break;
            case 'umiacp_groupsimple' :
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
                        $item['statusname'] = '已取消';
                        break;
                    default :
                        $item['statusname'] = '';
                        break;
                }
                break;
            case 'umiacp_lighten' :
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
                        $item['statusname'] = '已取消';
                        break;
                    default :
                        $item['statusname'] = '';
                        break;
                }
                break;
            case 'umiacp_10second' :
            case 'umiacp_eggfreny' :
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
                        $item['statusname'] = '已取消';
                        break;
                    default :
                        $item['statusname'] = '';
                        break;
                }
                break;
            default :
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
                        $item['statusname'] = '已取消';
                        break;
                    default :
                        $item['statusname'] = '';
                        break;
                }
                break;
        }
        unset($item);
    }
    $pager = pagination($total, $pindex, $psize);

    if ($isdownload){
        $header = [
            '活动',
            '姓名',
            '手机号',
            '具体用户信息',
            '支付状态',
            '发起时间',
        ];

        $types = [
            ['activitytitle', 300],
            ['realname', 200],
            ['mobile', 200],
            ['userinfo', 500],
            ['statusname', 200],
            ['createtime', 200, 'date'],
        ];

        $this->download('报名列表', $list, $header, $types);
        die();
    }

    if ($module == 'umiacp_vote') {
        include $this->template('cuts_vote');
    } else if ($module == 'umiacp_bargain') {
        include $this->template('cuts_bargain');
    } else if ($module == 'umiacp_apply') {
        include $this->template('cuts_apply');
    } else if ($module == 'umiacp_groupprepay') {
        include $this->template('cuts_groupprepay');
    } else if ($module == 'umiacp_groupsimple') {
        include $this->template('cuts_groupsimple');
    } else if ($module == 'umiacp_lighten') {
        include $this->template('cuts_lighten');
    } else {
        include $this->template('cuts');
    }

}


if ($op == 'audit') {
    $id = intval($_GPC['id']);
    $status = intval($_GPC['status']);
    $module = $_GPC['module'];
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename($module . '_cut') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：报名人员不存在或是已经被删除！', $this->template('cuts'), 'error');
    }
    pdo_update($module . '_' . 'cut', ['status' => $status], array('id' => $id));
    itoast('温馨提示：操作成功！', $this->template('cuts'), 'success');
}


if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'activity') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：活动管理不存在或是已经被删除！', $this->template('cuts'), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'activity', ['status' => -1], array('id' => $id));
    itoast('温馨提示：活动管理删除成功！', $this->template('cuts'), 'success');
}

if ($op == 'search') {
    //选择用户

    $keyword = $_GPC['keyword'];

    $ds = $this->getMemberKeyword($keyword);

    die(json_encode(['status' => $ds ? 1 : 0, 'ds' => $ds]));

}
        