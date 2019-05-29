<?php


global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
function debugLog($msg){
    youmi_internal_log('777',$msg);
}
if ($op == 'post') {


    $activity_id = intval($_GPC['activity_id']);

    $paras[':id'] = $activity_id;
    $item = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_' . 'activity') . ' where id = :id limit 1 ', $paras);

    if (checksubmit('submit')) {
        $data = $_GPC['data'];


        if (!empty($activity_id)) {

            //增加人数
            $addNum = intval($data['add_num']);
            $singnum = intval($item['signnum']);
            $saveAddNum = $addNum;
            $avaNum = $singnum - $data['participate'];

            $message = '修改虚拟人数成功！';
            if ($item['signstatus']==2){

//限制人数
                if ($avaNum > 0) {
                    $resultNum = $avaNum - $addNum;
                    if ($resultNum < 0) {
                        $saveAddNum = intval($avaNum);
                        $resultNum = 0;
                    }

                    $data['participate +='] = $saveAddNum;
                    addFissionVirtualNum($item, $uniacid, $saveAddNum);

                }else{
                    if ($addNum>0){
                        unset($data['participate']);
                        $message = ' 报名人数已满！';
                    }
                }

            }else{

                $data['participate +='] = $saveAddNum;
                addFissionVirtualNum($item, $uniacid, $saveAddNum);
            }



//            if ($avaNum > 0) {
//                $resultNum = $avaNum - $addNum;
//                if ($resultNum < 0) {
//                    $saveAddNum = intval($avaNum);
//                    $resultNum = 0;
//                }
//
//                $data['participate +='] = $saveAddNum;
//                addFissionVirtualNum($activity_id, $uniacid, $saveAddNum);
//
//                $message = '修改虚拟人数成功！';
//            } else {
//                if ($item['signstatus']==2&&$addNum > 0){
//                    //有添加报名人数才提示报名人数已满
//
//                        $message = ' 报名人数已满！';
//
//                }else{
//                    $message = '修改虚拟人数成功！';
//                }
//
//                unset($data['participate']);
//
//            }
            unset($data['add_num']);
            pdo_update(YOUMI_NAME . '_' . 'activity', $data, array('id' => $activity_id));

        } else {

        }
        itoast('温馨提示：' . $message, $this->createWebUrl('activity'), 'success');
    }


    include $this->template('virtual');
}


function addFissionVirtualNum($activity, $uniacid, $addNum)
{

    $setting = youmi_setting_get_list();
    $activity_id = $activity['id'];
    if ($activity['status'] != 1 || $activity['endtime'] < time()) {
        // '活动已下架');

        return;
    }
    if ($activity['starttime'] > time()) {
        // youmi_result(1, '活动未开始');

        return;
    }
    if ($activity['shop_id'] > 0) {
        $shop = pdo_get(UMI_NAME . '_shop', ['id' => $activity['shop_id']]);
        if ($shop['endtime'] < time() && $shop['times'] > $setting['vip_times']) {
            $activity['status'] = 2;

            return;//   youmi_result(1, '活动已下架');
        }
    }
//    $records = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_cut') . ' where uniacid = ' . $uniacid . ' and activity_id = ' . $activity_id . ' and status = "3" ');
//    if ($activity['signstatus'] == '2' && (intval($activity['signnum']) == count($records))) {
//        $activity['status'] = 2;
//        return; //  youmi_result(1, '报名人数已满');
//    }


//    die('asd');
    pdo_update(UMI_NAME . '_activity', ['participate +=' => $addNum], ['shop_id' => $activity['shop_id'], 'module' => YOUMI_NAME, 'activity_id' => $activity_id]);


    $count = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename(UMI_NAME . '_virtual_member'));

    $oft = $count - $addNum;
    $overNum = 0;
    if ($oft < 0) {
        $oft = 0;
        $overNum = $addNum - $count;
    } else {
        $oft = rand(0, $oft);
    }
    $sql = 'select * from ims_' . UMI_NAME . '_virtual_member limit ' . $addNum . ' offset ' . $oft;
    $virtualNums = pdo_fetchall($sql);

    if ($overNum > 0) {
        $index = 0;
        for ($i = 0; $i < $overNum; $i++) {
            if ($index > count($virtualNums) - 1) {
                $index = 0;
            }
            array_push($virtualNums, $virtualNums[$index]);
            $index++;
        }
    }

    $cuts = [];
    foreach ($virtualNums as $virtualNum) {

        $cut['activity_id'] = $activity_id;
        $cut['goods_id'] = '';
        $cut['shop_id'] = $activity['shop_id'];
        $cut['uniacid'] = $uniacid;
        $cut['mid'] = -1;
//        $cut['fmid'] = $_GPC['fmid'];
        $cut['oprice'] = '';
        $cut['rprice'] = '';
        $cut['times'] = '';
        $cut['price'] = '';
        $cut['nprice'] = '';
        $cut['status'] = '1';

        $cut['realname'] = $virtualNum['nickname'];
        $cut['avatar'] = $virtualNum['avatar'];

        $cut['createtime'] = rand($activity['starttime'], time());

//        pdo_insert(YOUMI_NAME . '_cut', $cut);
//
//        if ($_GPC['fmid'] && $_GPC['fmid'] != $this->mid) {
//            pdo_update(YOUMI_NAME . '_cut', ['share_num +=' => 1], ['activity_id' => $activity_id, 'mid' => $cut['fmid']]);
//        }


        $cuts[] = $cut;
    }

    batchInsert(tablename(YOUMI_NAME . '_cut'), $cuts);

}




