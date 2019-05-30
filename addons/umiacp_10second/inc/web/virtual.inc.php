<?php


global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if ($op == 'post') {


    $activity_id = intval($_GPC['activity_id']);

    $paras[':id'] = $activity_id;
    $item = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_' . 'activity') . ' where id = :id limit 1 ', $paras);
    if (checksubmit('submit')) {
        $data = $_GPC['data'];

        if (!empty($activity_id)) {
            $saveAddNum = $data['participate'];
            $data['participate +='] = $saveAddNum;

            addSecondVirtualNum($item, $uniacid, $saveAddNum,$data['p_good']);

            $message = '修改虚拟人数成功！';

            unset($data['participate']);
            unset($data['p_good']);
            pdo_update(YOUMI_NAME . '_' . 'activity', $data, array('id' => $activity_id));

        } else {

        }
        itoast('温馨提示：' . $message, $this->createWebUrl('activity'), 'success');
    }

    include $this->template('virtual');
}
function checkActivity($activity)
{
    $setting = youmi_setting_get_list();
    if ($activity['status'] != 1 || $activity['endtime'] < time()) {

//        echo '活动已下架';
        return true;
    }
    if ($activity['starttime'] > time()) {

//        echo '活动未开始';
        return true;
    }
    if ($activity['shop_id'] > 0) {
        $shop = pdo_get(UMI_NAME . '_shop', ['id' => $activity['shop_id']]);
        if ($shop['endtime'] < time() && $shop['times'] > $setting['vip_times']) {
            $activity['status'] = 2;

//            echo '活动已下架';
            return true;
        }
    }

    return false;
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

function addSecondVirtualNum($activity, $uniacid, $addNum,$good)
{
    if (checkActivity($activity)) {
        return;
    }

    pdo_update(UMI_NAME . '_activity', ['participate +=' => $addNum], ['shop_id' => $activity['shop_id'], 'module' => YOUMI_NAME, 'activity_id' => $activity['id']]);


    $virtualNums = getVirtual($addNum);

    foreach ($virtualNums as $virtualNum) {

        $reward['uniacid'] = $uniacid;
        $reward['openid'] = 'virtual';
        $reward['use'] = $activity['daynum'];
        $reward['day'] = rand($activity['starttime'], time());
        $reward['status'] = 5;
        $reward['activity_id'] = $activity['id'];

        $reward['mid'] = -1;
        $reward['send_name'] = $activity['send_name'];
        $reward['wishing'] = $activity['wishing'];
        $reward['act_name'] = $activity['act_name'];
        $reward['good'] =floatval($good);
        $reward['diff'] =number_format(abs(10- $reward['good']),4 ) ;

        $reward['nickname'] = $virtualNum['nickname'];
        $reward['avatar'] = $virtualNum['avatar'];

        $reward['createtime'] = rand($activity['starttime'], time());

        pdo_insert(YOUMI_NAME . '_reward', $reward);


    }

}
