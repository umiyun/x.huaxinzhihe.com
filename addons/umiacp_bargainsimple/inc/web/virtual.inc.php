<?php


global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if ($op == 'post') {


    $activity_id = intval($_GPC['activity_id']);

    $paras[':id'] = $activity_id;
    if (checksubmit('submit')) {
        $data = $_GPC['data'];

        if (!empty($activity_id)) {

            $good = pdo_fetch("SELECT * FROM " . tablename(YOUMI_NAME . '_' . 'goods') . " WHERE id = :id and status = :status and activity_id = :activity_id LIMIT 1", array(':id' => $data['good_id'], 'status' => 1, 'activity_id' => $activity_id));
//       $participate= intval( pdo_getcolumn(YOUMI_NAME . '_' . 'activity',array('id' => $activity_id),'participate'));
            //增加人数
            $addNum = intval($data['add_num']);

            $saveAddNum = $addNum;
            if ($good['gnum'] > 0) {
                $resultNum = $good['gnum'] - $addNum;
                if ($resultNum < 0) {
                    $saveAddNum = intval($good['gnum']);
                    $resultNum = 0;
                }
                pdo_update(YOUMI_NAME . '_' . 'goods', ['gnum' => $resultNum], ['id' => $data['good_id']]);


                $data['participate +='] = $saveAddNum;
                addVirtualNum($activity_id, $good, $uniacid, $saveAddNum);

                $message = '修改虚拟人数成功！';
            } else {
                if ($addNum > 0) {
                    $message = $good['title'] . ' 库存不足！';
                } else {
                    $message = '修改虚拟人数成功！';
                }
                unset($data['participate']);

            }
            unset($data['good_id']);
            unset($data['add_num']);
            pdo_update(YOUMI_NAME . '_' . 'activity', $data, array('id' => $activity_id));

        } else {

        }
        itoast('温馨提示：' . $message, $this->createWebUrl('activity'), 'success');
    }
    $item = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_' . 'activity') . ' where id = :id limit 1 ', $paras);
    $goods = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_' . 'goods') . ' where activity_id = :id ', $paras);


    include $this->template('virtual');
}


function addVirtualNum($activity_id, $goods, $uniacid, $addNum)
{
    $setting = youmi_setting_get_list();

    $activity = pdo_get(YOUMI_NAME . '_activity', ['id' => $activity_id]);
    $goods_id = $goods['id'];
    if ($activity['status'] != 1 || $activity['endtime'] < time()) {
        //活动已下架
        return;
    }
    if ($activity['starttime'] > time()) {
        //'活动未开始'
        return;
    }
    if ($activity['shop_id'] > 0) {
        $shop = pdo_get(UMI_NAME . '_shop', ['id' => $activity['shop_id']]);
        if ($shop['endtime'] < time() && $shop['times'] > $setting['vip_times']) {
            $activity['status'] = 2;
            //活动已下架
            return;
        }
    }
    pdo_update(UMI_NAME . '_activity', ['participate +=' => $addNum], ['shop_id' => $activity['shop_id'], 'module' => YOUMI_NAME, 'activity_id' => $activity_id]);
    $count = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename( UMI_NAME . '_virtual_member'));

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


//    var_dump($virtualNums);
//    die();

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


    foreach ($virtualNums as $virtualNum) {

//砍价等于商品原价减去底价
        $price = floatval($goods['oprice']) - floatval($goods['rprice']);
        $cut['status'] = 2;
        $data['status'] = 2;
        $price = number_format($price, 2);
        $cut['activity_id'] = $activity_id;
        $cut['goods_id'] = $goods_id;
        $cut['shop_id'] = $goods['shop_id'];
        $cut['uniacid'] = $uniacid;
        $cut['mid'] = -1;
        $cut['oprice'] = floatval($goods['oprice']);
        $cut['rprice'] = floatval($goods['rprice']);
        $cut['times'] = intval($goods['times']) - 1;
        $cut['price'] = $price;
        //现价等于底部价
        $cut['nprice'] = floatval($goods['rprice']);
        $cut['realname'] = $virtualNum['nickname'];
        $cut['avatar'] = $virtualNum['avatar'];
        $cut['createtime'] = rand($activity['starttime'],time());
        pdo_insert(YOUMI_NAME . '_cut', $cut);

        $cut_id = pdo_insertid();
        $data['activity_id'] = $activity_id;
        $data['goods_id'] = $goods_id;
        $data['uniacid'] = $uniacid;
        $data['shop_id'] = $cut['shop_id'];
        $data['cut_id'] = $cut_id;
        $data['mid'] = -1;
        $data['fmid'] = $cut['mid'];
        $data['oprice'] = floatval($cut['oprice']);
        $data['nprice'] = floatval($cut['nprice']);
        $data['price'] = $cut['price'];
        $data['nprice'] = floatval($cut['oprice']) - $price;
        $data['createtime'] = $cut['createtime'];
        pdo_insert(YOUMI_NAME . '_record', $data);


    }


//    $data['schedule'] = (1 - number_format((floatval($cut['nprice']) - floatval($cut['rprice'])) / (floatval($cut['oprice']) - floatval($cut['rprice'])), 2)) * 100;

}
