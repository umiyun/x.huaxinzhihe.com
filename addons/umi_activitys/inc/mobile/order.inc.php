<?php
/**
 * Created by IntelliJ IDEA.
 * User: 7³
 * Date: 2018/7/30 0030
 * Time: 12:18
 */

global $_W, $_GPC;

$uniacid = intval($this->uniacid);

if (!$this->mid && $this->user_type != 3) {
    youmi_result(1, '请先登录');
}


$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'display';

/**
 * 下单
 * do   :   order   op  :   create_order
 * meal_id     套餐
 * num          购买数量
 */
if ($op == 'create_order') {
    $meal_id = intval($_GPC['meal_id']);

    youmi_puv('create_order', $meal_id);

    if ($meal_id <= 0) {
        youmi_result(1, '请选择套餐');
    }
    $meal = pdo_fetch("select * from " . tablename(YOUMI_NAME . '_' . 'meal') . " where `uniacid` = {$uniacid} and id = {$meal_id} ");

    if ($meal['status'] == -1) {
        youmi_result(1, '套餐已下架');
    }

    if ($meal['price'] <= 0) {
        youmi_result(1, '金额错误');
    }

//    $order = pdo_get(YOUMI_NAME . '_' . 'order', ['meal_id' => $meal_id, 'mid' => $this->mid, 'status' => 1]);
//    if ($order) {
//        $order['price'] = floatval($meal['price']);
//        pdo_update(YOUMI_NAME . '_' . 'order', ['price' => $order['price']], ['id' => $order['id']]);
//        youmi_result(0, '下单成功', $order);
//    }

    $order['price'] = floatval($meal['price']);

    $moduleid = empty($_W['fans']['uid']) ? '000000' : sprintf("%06d", $_W['fans']['uid']);
    $tid = date('YmdHis') . $moduleid . random(8, 1);

    $order['uniacid'] = $uniacid;
    $order['mid'] = $this->mid;
    $order['openid'] = $this->openid;
    $order['meal_id'] = $meal_id;
    $order['title'] = $meal['title'] ? $meal['title'] : $meal['buy'] . '月套餐';
    $order['buy'] = $meal['buy'];
    $order['gift'] = $meal['gift'];
    $order['ordersn'] = $tid;
    $order['tid'] = $tid;
    $order['status'] = 1;
    $order['createtime'] = TIMESTAMP;
    $status = pdo_insert(YOUMI_NAME . '_' . 'order', $order);
    $order['id'] = pdo_insertid();

    $errno = $status ? 0 : 1;

    youmi_result($errno, '下单' . ($status ? '成功' : '失败'), $order);
}


/**
 * 用户更新订单
 * do   :   order   op  :   update
 * order_id     订单id
 * num          购买数量
 */
if ($op == 'update') {

    $order_id = intval($_GPC['order_id']);
    $num = intval($_GPC['num']);
    if (!$num) {
        youmi_result(1, '购买数量错误');
    }
    youmi_puv('order_update', $order_id, $num);

    $order = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' where uniacid = :uniacid and id = ' . $order_id, [':uniacid' => $this->uniacid]);

    if ($order['meal_type'] != 2) {
        youmi_result(1, '此商品不可购买多份');
    }
    $meal = pdo_fetch("select * from " . tablename(YOUMI_NAME . '_' . 'meal') . " where `uniacid` = {$uniacid} and id = {$order['meal_id']} ");

    if ($meal['status'] == 3 || $meal['stock'] <= 0) {
        youmi_result(1, '商品已抢光');
    }
    if ($meal['stock'] < $num) {
        youmi_result(1, '商品库存不足啦~~~');
    }
    if ($meal['status'] == 2 || $meal['endtime'] <= TIMESTAMP) {
        youmi_result(1, '商品已下架');
    }

    $price = floatval($meal['price']) * $num;
    $res = pdo_update(YOUMI_NAME . '_' . 'order', ['num' => $num, 'price' => $price], ['id' => $order_id]);
    youmi_result(0);

}

/**
 * 订单列表
 * do   :   order   op  :   list
 * status               状态:1,待付款;2,已付款;3,已核销;4,已取消;5,已退款;6,已失效;
 * page                 页码
 * keyword              商品名
 * meal_type           商品类型
 */
if ($op == 'list') {

    youmi_puv('order_list');

    $condition = '';
    $pindex = max(1, intval($_GPC['page']));
    $psize = !empty($_GPC['psize']) ? $_GPC['psize'] : 10;
    $paras[':uniacid'] = $uniacid;

    $meal_type = intval($_GPC['meal_type']) ? intval($_GPC['meal_type']) : 1;
    if ($meal_type) {
        $condition .= ' and meal_type = ' . $meal_type;
    }
    $status = intval($_GPC['status']);
    if ($status) {
        $condition .= ' and status = ' . $status;
    } else {
        if ($meal_type != 2) {
            $condition .= ' and status in (2,3) ';
        }
    }
    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= " and title like '%{$keyword}%' ";
    }
    if ($this->mid) {
        $condition .= ' and mid = ' . $this->mid;
    }
    $orderby = ' order by ';

    $orderby .= ' createtime desc ';
    $list = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' where uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    foreach ($list as &$item) {
        switch ($item['status']) {
            case 1 :
                $item['statusname'] = '待付款';
                break;
            case 2 :
                $item['statusname'] = '已付款';
                break;
            case 3 :
                $item['statusname'] = '已核销';
                break;
            case 4 :
                $item['statusname'] = '已取消';
                break;
            case 5 :
                $item['statusname'] = '已退款';
                break;
            case 6 :
                $item['statusname'] = '已失效';
                break;
            default :
                $item['statusname'] = '';
                break;
        }
        $item['saler_qrurl'] = $_W['siteroot'] . 'app/' . $this->createMobileUrl('saler', ['user_type' => 2, 'order_id' => $item['id']]);
        $item['meal'] = pdo_get(YOUMI_NAME . '_' . 'meal', ['id' => $item['meal_id']]);
        $item['meal']['image'] = tomedia($item['meal']['image']);
        $item['store'] = pdo_get(YOUMI_NAME . '_' . 'store', ['id' => $item['meal']['store_id']]);
        $item['comment'] = pdo_get(YOUMI_NAME . '_' . 'comment', ['order_id' => $item['id']]);
        unset($item);
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' WHERE uniacid = :uniacid ' . $condition, $paras);
    $data['list'] = $list ? $list : [];
    $data['total'] = intval($total);

    youmi_result(0, '订单列表', $data);

}

/**
 * 商家订单列表
 * do   :   order   op  :   shop_list
 * shop_id              商家id
 * saler_id             核销员id
 * status               状态:1,待付款;2,已付款;3,已核销;4,已取消;5,已退款;6,已失效;
 * page                 页码
 * keyword              商品名
 * meal_type           商品类型
 */
if ($op == 'shop_list') {

    youmi_puv('shop_list');

    $condition = '';
    $pindex = max(1, intval($_GPC['page']));
    $psize = !empty($_GPC['psize']) ? $_GPC['psize'] : 10;
    $paras[':uniacid'] = $uniacid;

    $meal_type = intval($_GPC['meal_type']) ? intval($_GPC['meal_type']) : 1;
    if ($meal_type) {
        $condition .= ' and meal_type = ' . $meal_type;
    }

    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= " and title like '%{$keyword}%' ";
    }
    $shop_id = intval($_GPC['shop_id']);
    if ($shop_id) {
        $condition .= ' and shop_id = ' . $shop_id;
    }
    $saler_id = intval($_GPC['saler_id']);
    if ($saler_id) {
        $condition .= ' and saler_id = ' . $saler_id;
    }
    $starttime = trim($_GPC['starttime']);
    $endtime = trim($_GPC['endtime']);
    if ($starttime) {
        $starttime = strtotime($starttime);
        $endtime = strtotime($endtime . ' 23:59:59');
        $condition .= ' and createtime <= ' . $endtime . ' and createtime >= ' . $starttime;
    }

    $status = intval($_GPC['status']);
    if ($status) {
        $condition .= ' and status = ' . $status;
        $condition2 .= $condition . ' and status = 3 ';
    } else {
        if ($meal_type != 2) {
            $condition .= ' and status in (2,3) ';
        }
        $condition2 .= $condition . ' and status = 3 ';
    }
    $orderby = ' order by ';

    $orderby .= ' createtime desc ';
    $list = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' where uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    foreach ($list as &$item) {
        switch ($item['status']) {
            case 1 :
                $item['statusname'] = '待付款';
                break;
            case 2 :
                $item['statusname'] = '已付款';
                break;
            case 3 :
                $item['statusname'] = '已核销';
                break;
            case 4 :
                $item['statusname'] = '已取消';
                break;
            case 5 :
                $item['statusname'] = '已退款';
                break;
            case 6 :
                $item['statusname'] = '已失效';
                break;
            default :
                $item['statusname'] = '';
                break;
        }
        $item['saler_qrurl'] = $_W['siteroot'] . 'app/' . $this->createMobileUrl('saler', ['user_type' => 2, 'order_id' => $item['id']]);
        $item['meal'] = pdo_get(YOUMI_NAME . '_' . 'meal', ['id' => $item['meal_id']]);
        $item['meal']['image'] = tomedia($item['meal']['image']);
        $item['store'] = pdo_get(YOUMI_NAME . '_' . 'store', ['id' => $item['meal']['store_id']]);

        if ($item['mid']) {
            $member = pdo_get(YOUMI_NAME . '_' . 'member', ['id' => $item['mid']]);
            if ($member['wxopenid']) {
                $mc_member = $this->getMemberInfo($member['wxopenid'], 3);
                $realname = $mc_member['nickname'];
                $avatar = $mc_member['avatar'];
            } elseif ($member['openid']) {
                $mc_member = $this->getMemberInfo($member['openid'], 2);
                $realname = $mc_member['nickname'];
                $avatar = $mc_member['avatar'];
            } else {
                $realname = $member['mobile'];
                $avatar = '';
            }

            $member['nickname'] = $realname;
            $member['avatar'] = $avatar;
            unset($member['salt']);
            unset($member['password']);
            unset($member['mobile']);
            $item['member'] = $member;
        }
        unset($item);
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' WHERE uniacid = :uniacid ' . $condition, $paras);
    $total_price = pdo_fetchcolumn('SELECT SUM(price) FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' WHERE uniacid = :uniacid ' . $condition2, $paras);


    $data['list'] = $list ? $list : [];
    $data['total'] = intval($total);
    $data['total_price'] = floatval($total_price);

    youmi_result(0, '商家订单列表', $data);

}

/**
 * 订单详情
 * do   :   order   op  :   detail
 * order_id     订单
 */
if ($op == 'detail') {

    $order_id = intval($_GPC['order_id']);
    youmi_puv('order_detail', $order_id);
    $order = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' where uniacid = :uniacid and id = ' . $order_id, [':uniacid' => $uniacid]);
    switch ($order['status']) {
        case 2 :
            $order['statusname'] = '已付款';
            break;
        case 3 :
            $order['statusname'] = '已核销';
            break;
        default :
            $order['statusname'] = '';
            break;
    }
    if ($order['mid']) {
        $member = pdo_get(YOUMI_NAME . '_' . 'member', ['id' => $order['mid']]);
        if ($member['wxopenid']) {
            $mc_member = $this->getMemberInfo($member['wxopenid'], 3);
            $realname = $mc_member['nickname'];
            $avatar = $mc_member['avatar'];
        } elseif ($member['openid']) {
            $mc_member = $this->getMemberInfo($member['openid'], 2);
            $realname = $mc_member['nickname'];
            $avatar = $mc_member['avatar'];
        } else {
            $realname = $member['mobile'];
            $avatar = '';
        }

        $member['nickname'] = $realname;
        $member['avatar'] = $avatar;
        unset($member['salt']);
        unset($member['password']);
        unset($member['mobile']);
        $order['member'] = $member;
    }
    $order['meal'] = pdo_get(YOUMI_NAME . '_' . 'meal', ['id' => $order['meal_id']]);
    $order['meal']['image'] = tomedia($order['meal']['image']);
    $order['meal']['product_package'] = unserialize($order['meal']['product_package']);
    $order['meal']['protection'] = unserialize($order['meal']['protection']);

    $order['store'] = pdo_get(YOUMI_NAME . '_' . 'store', ['id' => $order['store_id']]);
    $order['store']['outer_image'] = tomedia($order['store']['outer_image']);

    $order['saler_qrurl'] = $_W['siteroot'] . 'app/' . $this->createMobileUrl('saler', ['user_type' => 2, 'order_id' => $order_id]);
    $data['order'] = $order;

    youmi_result(0, '订单详情', $data);

}

/**
 * 取消订单
 * do   :   order   op  :   cancel
 * order_id     订单
 */
if ($op == 'cancel') {

    $order_id = intval($_GPC['order_id']);
    youmi_puv('order_cancel', $order_id);
    $order = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' where uniacid = :uniacid and id = ' . $order_id, [':uniacid' => $this->uniacid]);

    if ($order['status'] == 4) {
        youmi_result(0, '订单已取消');
    }
    if ($order['status'] != 1) {
        youmi_result(1, '订单无法取消');
    }
    $res = pdo_update(YOUMI_NAME . '_' . 'order', ['status' => 4], ['id' => $order_id]);
    $errno = $res ? 0 : 1;

    youmi_result($errno, '订单取消' . ($res ? '成功' : '失败'));
}

/**
 * 小程序订单核销码
 * do   :   order   op  :   hxqrcode
 * order_id     订单
 */
if ($op == 'hxqrcode') {

    $order_id = intval($_GPC['order_id']);
    youmi_puv('order_hxqrcode', $order_id);
    $order = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' where uniacid = :uniacid and id = ' . $order_id, [':uniacid' => $this->uniacid]);

    if ($order['status'] == 4) {
        youmi_result(0, '订单已取消');
    }
    if ($order['status'] == 3) {
        youmi_result(1, '订单已核销');
    }
    if ($order['status'] != 2) {
        youmi_result(1, '订单状态不对');
    }
    if (!$order['hxqrcode']) {

        $path = "pages/verification/index";
        $res = getCodeUnlimit($order_id, $path);
        $res = data_uri($res, 'image/png');

        $files['file'] = $res;
        $files['name'] = 'order_id_' . $id;
        $files['size'] = 0;
        $files['type'] = 'image/png';
        $files['error'] = 0;
        $res = umi_uploadimg($files, 'qrcode', 'hxqrcode');
        if (!$res['errno']) {
            $order['hxqrcode'] = $res['message'];
            pdo_update(YOUMI_NAME . '_' . 'order', ['hxqrcode' => $res['message']], array('id' => $id));
        }
    }

    youmi_result(0, tomedia($order['hxqrcode']));
}

/**
 * 用户退款订单
 * do   :   order   op  :   refund
 * order_id     订单id
 * reason       退款原因
 */
if ($op == 'refund') {

    $order_id = intval($_GPC['order_id']);
    if (!$order_id) {
        youmi_result(1, '订单不存在');
    }
    $reason = trim($_GPC['reason']);
    youmi_puv('order_refund', $order_id, $reason);

    $order = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' where uniacid = :uniacid and id = ' . $order_id, [':uniacid' => $this->uniacid]);
    if (!$order) {
        youmi_result(1, '订单不存在');
    }
    if ($order['status'] == 4) {
        youmi_result(0, '订单已取消');
    }
    if ($order['status'] == 5) {
        youmi_result(0, '订单已退款');
    }
    if ($order['status'] != 2 || $order['meal_type'] != 2) {
        youmi_result(1, '订单无法退款');
    }
    $res = youmi_refund($order_id);
    if ($res['errno'] === 0) {
        pdo_update(YOUMI_NAME . '_' . 'order', ['reason' => $reason], ['id' => $order_id]);
        youmi_result(0, '订单退款成功！');
    } else {
        youmi_result(1, $res['err_code_des']);
    }

}


