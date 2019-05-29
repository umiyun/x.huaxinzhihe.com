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
 * cut_id     砍价记录
 */
if ($op == 'create_order') {
    $cut_id = intval($_GPC['cut_id']);

    youmi_puv('create_order', $cut_id);

    if ($cut_id <= 0) {
        youmi_result(1, '请选择商品');
    }
    $cut = pdo_fetch("select * from " . tablename(YOUMI_NAME . '_' . 'cut') . " where `uniacid` = {$uniacid} and id = {$cut_id} ");
    $activity = pdo_fetch("select * from " . tablename(YOUMI_NAME . '_' . 'activity') . " where `uniacid` = {$uniacid} and id = {$cut['activity_id']} ");
//    die(json_encode($activity));
    $goods = pdo_fetch("select * from " . tablename(YOUMI_NAME . '_' . 'goods') . " where `uniacid` = {$uniacid} and id = {$cut['goods_id']} ");
    if (!$goods || $goods['status'] == 2) {
        youmi_result(1, '商品已下架');
    }
    if ($goods['status'] == 3 || $goods['success'] >= $goods['gnum']) {
        youmi_result(1, '商品已抢光');
    }

    if ($cut['nprice'] <= 0) {
        youmi_result(1, '金额错误');
    }

    $order = pdo_get(YOUMI_NAME . '_' . 'order', ['cut_id' => $cut_id, 'mid' => $this->mid, 'status' => [2, 3]]);
    if ($order) {
        youmi_result(1, '请勿重复购买');
    }
    $order = pdo_get(YOUMI_NAME . '_' . 'order', ['cut_id' => $cut_id, 'mid' => $this->mid, 'status' => 1]);
    if ($order) {
        $order['price'] = floatval($cut['nprice']);
        pdo_update(YOUMI_NAME . '_' . 'order', ['price' => floatval($order['price'])], ['id' => $order['id']]);
        youmi_result(0, '下单成功', $order);
    }

    $order['price'] = floatval($cut['nprice']);
    $order['mid'] = $this->mid;

//    $shop = pdo_get(YOUMI_NAME . '_' . 'shop', ['id' => $goods['shop_id']]);

    $moduleid = empty($_W['fans']['uid']) ? '000000' : sprintf("%06d", $_W['fans']['uid']);
    $tid = date('YmdHis') . $moduleid . random(8, 1);

    $order['uniacid'] = $uniacid;
    $order['cut_id'] = $cut_id;
    $order['goods_id'] = $cut['goods_id'];
    $order['activity_id'] = $cut['activity_id'];
    $order['shop_id'] = $cut['shop_id'];
    $order['title'] = $activity['title'];
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

    if ($order['goods_type'] != 2) {
        youmi_result(1, '此商品不可购买多份');
    }
    $goods = pdo_fetch("select * from " . tablename(YOUMI_NAME . '_' . 'goods') . " where `uniacid` = {$uniacid} and id = {$order['goods_id']} ");

    if ($goods['status'] == 3 || $goods['stock'] <= 0) {
        youmi_result(1, '商品已抢光');
    }
    if ($goods['stock'] < $num) {
        youmi_result(1, '商品库存不足啦~~~');
    }
    if ($goods['status'] == 2 || $goods['endtime'] <= TIMESTAMP) {
        youmi_result(1, '商品已下架');
    }

    $price = floatval($goods['price']) * $num;
    $res = pdo_update(YOUMI_NAME . '_' . 'order', ['num' => $num, 'price' => $price], ['id' => $order_id]);
    youmi_result(0);

}

/**
 * 订单列表
 * do   :   order   op  :   list
 * status               状态:1,待付款;2,已付款;3,已核销;4,已取消;5,已退款;6,已失效;
 * page                 页码
 * keyword              商品名
 * goods_type           商品类型
 */
if ($op == 'display') {

    youmi_puv('order_list');

    $condition = '';
    $pindex = max(1, intval($_GPC['page']));
    $psize = !empty($_GPC['psize']) ? $_GPC['psize'] : 10;
    $paras[':uniacid'] = $uniacid;

    $status = intval($_GPC['status']);
    if ($status) {
        $condition .= ' and status = ' . $status;
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
        $item['goods'] = pdo_get(YOUMI_NAME . '_' . 'goods', ['id' => $item['goods_id']]);
        $item['goods']['image'] = tomedia($item['goods']['image']);
        unset($item);
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' WHERE uniacid = :uniacid ' . $condition, $paras);
    $data['list'] = $list ? $list : [];
    $data['total'] = intval($total);
    include $this->template('orders');
    exit();

}

/**
 * 商家订单列表
 * do   :   order   op  :   shop_list
 * shop_id              商家id
 * saler_id             核销员id
 * status               状态:1,待付款;2,已付款;3,已核销;4,已取消;5,已退款;6,已失效;
 * page                 页码
 * keyword              商品名
 * goods_type           商品类型
 */
if ($op == 'shop_list') {

    youmi_puv('shop_list');

    $condition = '';
    $pindex = max(1, intval($_GPC['page']));
    $psize = !empty($_GPC['psize']) ? $_GPC['psize'] : 10;
    $paras[':uniacid'] = $uniacid;

    $goods_type = intval($_GPC['goods_type']) ? intval($_GPC['goods_type']) : 1;
    if ($goods_type) {
        $condition .= ' and goods_type = ' . $goods_type;
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
        if ($goods_type != 2) {
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
        $item['goods'] = pdo_get(YOUMI_NAME . '_' . 'goods', ['id' => $item['goods_id']]);
        $item['goods']['image'] = tomedia($item['goods']['image']);
        $item['store'] = pdo_get(YOUMI_NAME . '_' . 'store', ['id' => $item['goods']['store_id']]);

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
    $order['goods'] = pdo_get(YOUMI_NAME . '_' . 'goods', ['id' => $order['goods_id']]);
    $order['goods']['image'] = tomedia($order['goods']['image']);
    $order['goods']['product_package'] = unserialize($order['goods']['product_package']);
    $order['goods']['protection'] = unserialize($order['goods']['protection']);

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
    if ($order['status'] != 2 || $order['goods_type'] != 2) {
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


