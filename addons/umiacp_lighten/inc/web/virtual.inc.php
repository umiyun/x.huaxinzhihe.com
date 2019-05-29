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
    $voters = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_' . 'cut') . ' where activity_id = :id and mid !=-1', $paras);

    if (checksubmit('submit')) {
        $data = $_GPC['data'];


        if (!empty($activity_id)) {

            $message = '修改虚拟人数成功！';
            $cut_id = $data['voter_id'];

            $data['participate +='] = $data['participate'];

            $light_num = $data['light_num'];
            $now_light = pdo_fetchcolumn("select count(id) from " . tablename(YOUMI_NAME . '_record') . " where cut_id = :cut_id and openid != :openid", [':cut_id' => $cut_id,':openid'=>'']);

            $ava = $item['lnum'] - $now_light;

            if ($ava > 0) {
            $addLight = $ava - $light_num;
                if ($addLight<0){
                    $light_num=$ava;
                }

                pdo_update(YOUMI_NAME . '_' . 'cut', ['dltotal +=' => $light_num], ['id' => $cut_id]);

            } else {
                $message = '助力已满！';
                $light_num=0;
            }


            addLightVirtualNum($item, $data['participate'],$data['participate_num'], $light_num, $uniacid,$cut_id);


            unset($data['voter_id']);
            unset($data['participate']);
            unset($data['light_num']);
            unset($data['participate_num']);
            pdo_update(YOUMI_NAME . '_' . 'activity', $data, array('id' => $activity_id));

        } else {

        }
        itoast('温馨提示：' . $message, $this->createWebUrl('activity'), 'success');
    }


    include $this->template('virtual');
}


function addLightVirtualNum($activity, $addNum,$pNum, $lightNum, $uniacid,$cut_id)
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

    $virtualNums = getVirtual($addNum);


    foreach ($virtualNums as $virtualNum) {

        $cut['activity_id'] = $activity_id;
        $cut['goods_id'] = '';
        $cut['shop_id'] = $activity['shop_id'];
        $cut['uniacid'] = $uniacid;
        $cut['mid'] = -1;
        $cut['oprice'] = '';
        $cut['rprice'] = '';
        $cut['times'] = '';
        $cut['price'] = '';
        $cut['nprice'] = '';
        $cut['status'] = '1';
        $cut['dltotal'] = $pNum;

        $cut['realname'] = $virtualNum['nickname'];
        $cut['avatar'] = $virtualNum['avatar'];

        $cut['createtime'] = rand($activity['starttime'], time());
        pdo_insert(YOUMI_NAME . '_cut', $cut);

    }

    $ligthVirtuals = getVirtual($lightNum);

    foreach ($ligthVirtuals as $virtualNum) {
        $data = array(
            'openid' => 'virtual',
            'mid' => -1,
            'avatar' => $virtualNum['avatar'],
            'nickname' => $virtualNum['nickname'],
            'createtime' => rand($activity['starttime'], time()),
            'cut_id'=>$cut_id,
            'uniacid'=>$uniacid
        );
        $re = pdo_query("UPDATE ".tablename(YOUMI_NAME . '_record')." SET openid = :openid, mid = :mid, avatar = :avatar, createtime = :createtime, nickname = :nickname  WHERE openid is null and cut_id= :cut_id and uniacid= :uniacid limit 1", $data);

     if (!$re){
         break;
     }
    }

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

function getVirtual($addNum)
{
    if ($addNum <= 0) {
        return [];
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

    return $virtualNums;
}




