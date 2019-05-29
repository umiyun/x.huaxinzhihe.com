<?php
/**
 * Created by IntelliJ IDEA.
 * User: 7³
 * Date: 2018/7/30 0030
 * Time: 12:18
 */

if (!defined('IN_IA')) {
    exit('Access Denied');
}

define('UMIACP_NAME', 'umiacp_bargain');

/**
 * 获取活动数据
 */
if (!function_exists('youmi_get_activity')) {
    function youmi_get_activity($activity_id)
    {
        $item = pdo_get(YOUMI_NAME . '_activity', ['id' => $activity_id]);
        $activity = pdo_get(UMIACP_NAME . '_activity', ['id' => $item['activity_id']]);
        $activity['desc_imgs'] = unserialize($activity['desc_imgs']);
        $activity['shop_imgs'] = unserialize($activity['shop_imgs']);
        $activity['userinfo'] = unserialize($activity['userinfo']);
        $activity['preferential_val'] = unserialize($activity['preferential_val']);
        $activity['effects_imgs'] = unserialize($activity['effects_imgs']);
        $activity['r_address'] = unserialize($activity['r_address']);
        foreach ($activity['shop_imgs'] as &$shop_img) {
            $shop_img = tomedia($shop_img);
            unset($shop_img);
        }
        foreach ($activity['desc_imgs'] as &$desc_img) {
            $desc_img = tomedia($desc_img);
            unset($desc_img);
        }
        $goods = pdo_getall(UMIACP_NAME . '_goods', ['activity_id' => $item['activity_id'], 'status >' => -1]);
        foreach ($goods as &$good) {
            $good['image'] = tomedia($good['image']);
            unset($good);
        }
        $activity['goods'] = $goods;

        return $activity;
    }
}

/**
 * 保存活动数据
 */
if (!function_exists('youmi_save_activity')) {
    function youmi_save_activity($from,$shop_default,$shop)
    {


        $data['uniacid'] = $from['uniacid'];
        $data['shop_id'] = $from['shop_id'];
        //        $data['effects_id'] = $from['effects_id'];
        $data['effects_imgs'] = serialize($from['effects_imgs']);
        $data['title'] = $from['title'];
        $data['image'] = $from['image'];
        $data['bgimage'] = $from['bgimage'];
        $data['music'] = $from['music'];
        $data['starttime'] = strtotime($from['starttime']);
        $data['endtime'] = strtotime($from['endtime']);
        $data['preferential_title'] = $from['preferential_title'];
        $data['preferential_val'] = serialize($from['preferential_val']);
        $data['desc_title'] = $from['desc_title'];
        $data['desc_val'] = $from['desc_val'];
        $data['desc_imgs'] = serialize($from['desc_imgs']);
        $data['rule_title'] = $from['rule_title'];
        $data['rule_val'] = $from['rule_val'];
        $data['shop_title'] = $from['shop_title'];
        $data['shop_val'] = $from['shop_val'];
        $data['shop_imgs'] = serialize($from['shop_imgs']);
        $data['receive_time'] = $from['receive_time'];
        $data['receive_address'] = $from['receive_address'];
        $data['receive_mobile'] = $from['receive_mobile'];
        if($shop_default==1) {//默认商家信息
            $data['shop_name'] = $shop['realname'];
            $data['shop_mobile'] = $shop['mobile'];
            $data['shop_province'] = $shop['province'];
            $data['shop_city'] = $shop['city'];
            $data['shop_district'] = $shop['district'];
            $data['shop_address'] = $shop['address'];
            $data['shop_code'] = $shop['qrcode'];
        }else{
            $data['shop_name'] = $from['shop_name'];
            $data['shop_mobile'] = $from['shop_mobile'];
            $data['shop_province'] = $from['shop_province'];
            $data['shop_city'] = $from['shop_city'];
            $data['shop_district'] = $from['shop_district'];
            $data['shop_address'] = $from['shop_address'];
            $data['shop_code'] = $from['shop_code'];
        }
        $data['userinfo'] = serialize($from['userinfo']);
        $data['titlebgimg'] = $from['titlebgimg'];
        $data['share_img'] = $from['share_img'];
        $data['share_title'] = $from['share_title'];
        $data['share_desc'] = $from['share_desc'];

        $data['regional'] = $from['regional'];
        $data['ak'] = $from['ak'];
        $data['r_address'] = serialize($from['r_address']);

        if ($from['activity_id']) {
            $item = pdo_get(YOUMI_NAME . '_activity', ['id' => $from['activity_id']]);
            $res = pdo_update(UMIACP_NAME . '_activity', $data, ['id' => $item['activity_id']]);
            $activity_id = $item['activity_id'];
            pdo_update(YOUMI_NAME . '_activity', [
                'title' => $data['title'],
                'logo' => $data['image'],
            ], ['id' => $from['activity_id']]);
            $data['activity_id']=$activity_id;
            $errno = 0;
            $message = '更新成功';
        } else {
            $data['status'] = 1;
            $data['createtime'] = time();
            $res = pdo_insert(UMIACP_NAME . '_activity', $data);
            $activity_id = pdo_insertid();
            $data['activity_id']=$activity_id;
            if ($res) {
                $errno = 0;
                $message = '新增成功';

                pdo_insert(YOUMI_NAME . '_activity', [
                    'uniacid' => $data['uniacid'],
                    'mid' => $from['mid'],
                    'shop_id' => $data['shop_id'],
                    'case_id' => $from['case_id'],
                    'activity_id' => $activity_id,
                    'module' => UMIACP_NAME,
                    'title' => $data['title'],
                    'logo' => $data['image'],
                    'status' => 1,
                    'createtime' => time(),
                ]);
                $a_id = pdo_insertid();
            } else {
                $errno = 1;
                $message = '新增失败';
            }
        }

        foreach ($from['goods'] as &$good) {

            if ($good['id']) {
                $good_id = $good['id'];
                unset($good['id']);
                pdo_update(UMIACP_NAME . '_goods', $good, ['id' => $good_id]);
            } else {
                $good['uniacid'] = $from['uniacid'];
                $good['activity_id'] = $activity_id;
                $good['shop_id'] = $from['shop_id'];;
                $good['status'] = 1;
                $good['createtime'] = time();
                unset($good['id']);
                pdo_insert(UMIACP_NAME . '_goods', $good);
            }
            unset($good);
        }
        $data['id']=($from['activity_id']?$from['activity_id']:$a_id);
        return ['errno' => $errno, 'message' => $message, 'data' => $data];
    }
}

/**
 * 删除商品数据
 */
if (!function_exists('youmi_del_goods')) {
    function youmi_del_goods($activity_id, $good_id)
    {
        $activity = pdo_get(YOUMI_NAME . '_activity', ['id' => $activity_id]);
        if ($activity['status'] == 1) {
            youmi_result(1, '活动上架中，不允许删除');
        }
        $res = pdo_update(UMIACP_NAME . '_goods', ['status' => -1], ['id' => $good_id, 'activity_id' => $activity['activity_id']]);
        youmi_result($res ? 0 : 1, '删除' . ($res ? '成功' : '失败'));

    }
}


