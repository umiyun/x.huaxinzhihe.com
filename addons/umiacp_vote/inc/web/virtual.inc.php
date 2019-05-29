<?php


global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
function debugLog($msg)
{
    youmi_internal_log('777', $msg);
}

if ($op == 'post') {


    $activity_id = intval($_GPC['activity_id']);

    $paras[':id'] = $activity_id;
    $item = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_' . 'activity') . ' where id = :id limit 1 ', $paras);
    $voters = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_' . 'cut') . ' where activity_id = :id ', $paras);

    if (checksubmit('submit')) {
        $data = $_GPC['data'];


        if (!empty($activity_id)) {


            //增加人数
            $addNum = intval($data['add_num']);

            $message = '修改虚拟人数成功！';


            $data['voter +='] = $addNum;

            $data['pv +='] = $data['pv'];
            addVoteVirtualNum($item, $data['voter_id'], $addNum,$data['pv'],$data['share'],$uniacid);

            unset($data['add_num']);
            unset($data['voter_id']);
            unset($data['pv']);
            unset($data['share']);
            pdo_update(YOUMI_NAME . '_' . 'activity', $data, array('id' => $activity_id));

        } else {

        }
        itoast('温馨提示：' . $message, $this->createWebUrl('activity'), 'success');
    }


    include $this->template('virtual');
}


function addVoteVirtualNum($activity, $cut_id, $addNum,$pv,$share,$uniacid)
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

 $cut_mid=   pdo_getcolumn(YOUMI_NAME . '_' . 'cut',['id'=>$cut_id],'mid');

    foreach ($virtualNums as $virtualNum) {

        $data['uniacid'] =$uniacid;
        $data['avatar'] = $virtualNum['avatar'];
        $data['fmid'] =$cut_mid;
        $data['mid'] = -1;
        $data['cut_id'] = $cut_id;
        $data['activity_id'] = $activity_id;
        $data['status'] = 1;
        $data['createtime'] = time();
        pdo_insert(YOUMI_NAME . '_record', $data);

    }


    pdo_update(YOUMI_NAME . '_' . 'cut', ['times +=' => $addNum,'pv +='=>$pv,'share'=>$share], ['id'=>$cut_id]);

}

function batchInsert($table, $data)
{

    if (count($data) === 0) {
        return 0;
    }

    $keys = [];
    $values = [];
    foreach ($data[0] as $k => $v) {
        $keys[] = $k;
    }
    foreach ($data as $item) {
        $strFields = '';
        foreach ($item as $k => $v) {
            if (is_string($v)) {
                $strFields .= '\'' . $v . '\',';
            } else {
                $strFields .= $v . ',';
            }
        }
        $strFields = substr($strFields, 0, strlen($strFields) - 1);
        array_push($values, "($strFields)");
    }
    $keySql = implode(',', $keys);
    $sql = "insert into $table ($keySql) values ";
    $sql .= implode(',', $values);

    return pdo_query($sql);
}




