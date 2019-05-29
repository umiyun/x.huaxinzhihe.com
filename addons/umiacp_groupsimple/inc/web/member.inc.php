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

function endsWith($haystack, $needle){
    return $needle === '' || substr_compare($haystack, $needle, -strlen($needle)) === 0;
}
//if ($op='aaa'){
////$paras=[':uniacid'=>5,':follow'=>1];
////    $nicknames = pdo_fetchall('SELECT m.nickname,m.avatar FROM ' . tablename('mc_mapping_fans') . ' f left join ' . tablename('mc_members') . ' m on f.uid = m.uid where f.uniacid = :uniacid and follow= :follow limit 1000 offset 0', $paras);
////    foreach ($nicknames as $nickname) {
////        if (endsWith($nickname['avatar'],'132132') ){
////            $nickname['avatar']=   substr($nickname['avatar'],0,strlen($nickname['avatar'])-3);
////        }
////        pdo_insert('umi_activitys_virtual_member',$nickname,true);
////  }
////    echo '插入成功';
//
//  $mems=  pdo_getall('umi_activitys_virtual_member');
//  $names=[];
//  $avas=[];
//    foreach ($mems as $mem) {
//        $names[]=$mem['nickname'];
//        $avas[]=$mem['avatar'];
//  }
//    echo json_encode($names).'<br>';
//    echo json_encode($avas).'<br>';
//    $datas=[];
//    for ($i=0;$i<20;$i++){
//            $datas[$i]['nickname']= $names[rand(0,130)];
//            $datas[$i]['avatar']= $avas[rand(0,130)];
//    }
//
//
//    foreach ($datas as $data) {
//     $re=   pdo_insert('umi_activitys_virtual_member',$data,true);
//     echo $re.'<br>';
//     if ($re){
//         break;
//     }
//    }
//    echo '插入成功';
//}

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
    itoast('温馨提示：用户操作成功！', referer(), 'success');
}


if ($op == 'del') {
    $mid = intval($_GPC['mid']);
    $paras[':mid'] = $mid;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'member') . ' WHERE mid = :mid limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：用户不存在或是已经被删除！', referer(), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'member', ['status' => -1], array('mid' => $mid));
    itoast('温馨提示：用户删除成功！', referer(), 'success');
}

if ($op == 'search') {
    //选择用户

    $keyword = $_GPC['keyword'];

    $ds = $this->getMemberKeyword($keyword);

    die(json_encode(['status' => $ds ? 1 : 0, 'ds' => $ds]));

}
