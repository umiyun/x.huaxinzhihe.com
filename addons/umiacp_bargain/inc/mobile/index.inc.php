<?php
/**
 * Created by IntelliJ IDEA.
 * User: 7³
 * Date: 2018/7/30 0030
 * Time: 12:18
 */

global $_W, $_GPC;

checkauth();

$uniacid = intval($_W['uniacid']);
$openid = trim($_W['openid']);

$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'display';

$member = $this->member;
$setting = youmi_setting_get_list();
youmi_puv('index');


$setting_activity = pdo_getall(UMI_NAME . '_' . 'setting', array('uniacid' => $uniacid));
foreach ($setting_activity as $key => &$value) {
    $svalue = unserialize($value['svalue']);
    if ($svalue) {
        foreach ($svalue as $k => $v) {
            if (strstr($k, 'image')) {
                if (is_array($v)) {
                    foreach ($v as &$item) {
                        $item = tomedia($item);
                        unset($item);
                    }
                } else {
                    $v = tomedia($v);
                }
            }
            $setting_activity[$k] = $v;
        }
    }
}

if ($op == 'display') {

    $con = '';
    $activity_id = intval($_GPC['activity_id']);
    if (!$activity_id) {
        message('请选择对应活动');
    }
    $fmid = intval($_GPC['fmid']);
    if ($fmid == $this->mid) {
        $fmid = 0;
    }
    $fmember = $this->getMemberInfo($fmid);

    $activity = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_activity') . ' where uniacid = ' . $uniacid . ' and id = ' . $activity_id);

    if ($activity['status'] != 1 || $activity['endtime'] < time()) {
        $activity['status'] = 2;
    }
    if ($activity['shop_id'] > 0) {
        $shop = pdo_get(UMI_NAME . '_shop', ['id' => $activity['shop_id']]);
        if ($setting_activity['vip_days'] > 0) {
            if ($shop['endtime'] < time()) {
                $activity['status2'] = 2;
                pdo_update(UMI_NAME . '_activity', array('status' => 2), ['shop_id' => $activity['shop_id']]);
                pdo_update(YOUMI_NAME . '_activity', array('status' => 2), ['id' => $activity['activity_id']]);
            }
        } else {
            if ($shop['times'] > $setting_activity['vip_times']) {
                $activity['status2'] = 2;
                pdo_update(UMI_NAME . '_activity', array('status' => 2), ['shop_id' => $activity['shop_id']]);
                pdo_update(YOUMI_NAME . '_activity', array('status' => 2), ['id' => $activity['activity_id']]);
            }
        }
        $activity_umi = pdo_get(UMI_NAME . '_activity', ['activity_id' => $activity['id'], 'module' => $_W['current_module']['name']]);
    }
    $_W['page']['title'] = $activity['title'] ? $activity['title'] : '活动宝砍价模块';

    $activity['image'] = tomedia($activity['image']);
    $activity['bgimage'] = tomedia($activity['bgimage']);
    $activity['desc_imgs'] = unserialize($activity['desc_imgs']);
    $activity['preferential_val'] = unserialize($activity['preferential_val']);
    foreach ($activity['desc_imgs'] as &$desc_img) {
        $desc_img = tomedia($desc_img);
        unset($desc_img);
    }
    $activity['shop_imgs'] = unserialize($activity['shop_imgs']);
    $activity['effects_imgs'] = unserialize($activity['effects_imgs']);
    foreach ($activity['shop_imgs'] as &$desc_img) {
        $desc_img = tomedia($desc_img);
        unset($desc_img);
    }
    foreach ($activity['effects_imgs'] as &$desc_img) {
        $desc_img = tomedia($desc_img);
        unset($desc_img);
    }
    $activity['userinfo'] = unserialize($activity['userinfo']);
    $success1 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('umiacp_bargain_order') . ' where uniacid=:uniacid and activity_id=:activity_id and status=2', [':uniacid' => $uniacid, ':activity_id' => $activity['id']]);

    $goods = pdo_getall(YOUMI_NAME . '_goods', ['uniacid' => $uniacid, 'activity_id' => $activity['id'], 'status' => 1]);
    foreach ($goods as &$good) {

        $good['my_cut'] = pdo_get(YOUMI_NAME . '_cut', ['mid' => $this->mid, 'activity_id' => $activity_id, 'goods_id' => $good['id']]);
        if ($good['my_cut']) {
            $good['my_cut']['schedule'] = (1 - number_format((floatval($good['my_cut']['nprice']) - floatval($good['my_cut']['rprice'])) / (floatval($good['my_cut']['oprice']) - floatval($good['my_cut']['rprice'])), 2)) * 100;
        }
        $good['cut'] = pdo_get(YOUMI_NAME . '_cut', ['mid' => $fmid, 'activity_id' => $activity_id, 'goods_id' => $good['id']]);
        if ($good['cut']) {
            $good['cut']['schedule'] = (1 - number_format((floatval($good['cut']['nprice']) - floatval($good['cut']['rprice'])) / (floatval($good['cut']['oprice']) - floatval($good['cut']['rprice'])), 2)) * 100;
        }
        $good['image'] = tomedia($good['image']);

        $good['cuts'] = pdo_getall(YOUMI_NAME . '_cut', ['activity_id' => $activity_id, 'goods_id' => $good['id']], '', '', 'nprice asc');
        foreach ($good['cuts'] as &$item) {
            $strlen = mb_strlen($item['realname'], 'utf-8');
            $firstStr = mb_substr($item['realname'], 0, 1, 'utf-8');
            $lastStr = mb_substr($item['realname'], -1, 1, 'utf-8');

            $item['realname'] = $firstStr . str_repeat('*', 3) . $lastStr;
            unset($item);
        }

        if ($fmid) {
            if ($good['cut']) {
                if ($good['cut']['status'] == 1) {

                    $rec = pdo_get(YOUMI_NAME . '_record', ['cut_id' => $good['cut']['id'], 'activity_id' => $activity_id, 'goods_id' => $good['id'], 'mid' => $this->mid, 'fmid' => $fmid]);
                    if ($good['my_cut'] && $rec) {
                        if ($good['my_cut']['status'] == 1) {

                            if ($activity['cutting_pay'] == 1) {
                                $good['style'] = 3;//允许砍价中付款
                            } else {
                                $good['style'] = 2;     //砍价中   提示分享
                            }
                        } elseif ($good['my_cut']['status'] == 2) {
                            $good['style'] = 3;     //砍到底   提示支付
                        } elseif ($good['my_cut']['status'] == 3) {
                            $good['style'] = 4;     //已支付   支付完成
                        }
                    } elseif ($good['my_cut'] && !$rec) {
                        if ($good['my_cut']['status'] == 1) {
                            if ($activity['cutting_pay'] == 1) {
                                $good['style'] = 8;//允许砍价中付款
                            } else {
                                $good['style'] = 7;     //砍价中   帮砍完   提示分享
                            }
                        } elseif ($good['my_cut']['status'] == 2) {
                            $good['style'] = 8;     //砍到底   帮砍完   提示支付
                        } elseif ($good['my_cut']['status'] == 3) {
                            $good['style'] = 9;     //已支付   帮砍完   支付完成
                        }
                    } else if (!$good['my_cut'] && $rec) {
                        $good['style'] = 6;         //已帮砍   我未报名   我要报名
                    } else if (!$good['my_cut'] && !$rec) {
                        $good['style'] = 5;         //未帮砍   我未报名   我要报名
                    }

                } elseif ($good['cut']['status'] >= 1) {
                    //砍到底   显示我的数据
                    if ($good['my_cut']) {
                        if ($good['my_cut']['status'] == 1) {
                            if ($activity['cutting_pay'] == 1) {
                                $good['style'] = 3;//允许砍价中付款
                            } else {
                                $good['style'] = 2;     //砍价中   提示分享
                            }
                        } elseif ($good['my_cut']['status'] == 2) {
                            $good['style'] = 3;     //砍到底   提示支付
                        } elseif ($good['my_cut']['status'] == 3) {
                            $good['style'] = 4;     //已支付   支付完成
                        }
                    } else {
                        $good['style'] = 1;         //未报名   我要报名
                    }
                }

            } else {
                //未报名   显示我的数据
                if ($good['my_cut']) {
                    if ($good['my_cut']['status'] == 1) {
                        if ($activity['cutting_pay'] == 1) {
                            $good['style'] = 3;//允许砍价中付款
                        } else {
                            $good['style'] = 2;     //砍价中   提示分享
                        }
                    } elseif ($good['my_cut']['status'] == 2) {
                        $good['style'] = 3;     //砍到底   提示支付
                    } elseif ($good['my_cut']['status'] == 3) {
                        $good['style'] = 4;     //已支付   支付完成
                    }
                } else {
                    $good['style'] = 1;         //未报名   我要报名
                }
            }
            $good['datastyle'] = 2;
        } else {
            if ($good['my_cut']) {
                if ($good['my_cut']['status'] == 1) {
                    if ($activity['cutting_pay'] == 1) {
                        $good['style'] = 3;//允许砍价中付款
                    } else {
                        $good['style'] = 2;     //砍价中   提示分享
                    }
                } elseif ($good['my_cut']['status'] == 2) {
                    $good['style'] = 3;     //砍到底   提示支付
                } elseif ($good['my_cut']['status'] == 3) {
                    $good['style'] = 4;     //已支付   支付完成
                }
            } else {
                $good['style'] = 1;         //未报名   我要报名
            }
            $good['datastyle'] = 1;
        }
        unset($good);
    }

    $records = pdo_fetchall('select r.*,c.realname,c.avatar from ' . tablename(YOUMI_NAME . '_record') . ' as r left join ' . tablename(YOUMI_NAME . '_cut') . ' as c on r.cut_id = c.id where c.activity_id = :activity_id order by id DESC limit 20 ', [':activity_id' => $activity_id]);

    foreach ($records as &$record) {
        $strlen = mb_strlen($record['realname'], 'utf-8');
        $firstStr = mb_substr($record['realname'], 0, 1, 'utf-8');
        $lastStr = mb_substr($record['realname'], -1, 1, 'utf-8');
        if ($record['fmid']==-1){

//        $record['avatar'] = $this->getMemberInfo($record['fmid'])['avatar'];
        }else{
        $record['avatar'] = $this->getMemberInfo($record['fmid'])['avatar'];

        }
        $record['realname'] = $firstStr . str_repeat('*', 2) . $lastStr;
//        $record['tips'] = $record['realname'] . '  ' . date('Y-m-d H:i');
        $record['tips'] = $record['realname'];
        switch ($record['status']) {
            case 1 :
                $record['tips'] .= '  砍了' . $record['price'] . '元';
                break;
            case 2 :
                $record['tips'] .= ' 抢到商品';
                break;
        }
        unset($record);
    }

    if ($activity['endtime'] < time()) {
        $update['status'] = 2;
        $activity['status'] = 2;
    }

    $update['pv +='] = 1;
    pdo_update(YOUMI_NAME . '_activity', $update, ['id' => $activity_id]);
    pdo_update(UMI_NAME . '_activity', $update, ['shop_id' => $activity['shop_id'], 'module' => YOUMI_NAME, 'activity_id' => $activity_id]);
    if ($activity['shop_id'] > 0) {
        $update1['times +='] = 1;
        pdo_update(UMI_NAME . '_shop', $update1, ['id' => $activity['shop_id']]);
    }
    $_share['title'] = $activity['share_title'];
    $_share['imgUrl'] = $activity['share_img'];
    $_share['desc'] = $activity['share_desc'];
    if (empty($setting['cannon_fodder'])) {
        $_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index', array('fmid' => $this->mid, 'activity_id' => $activity_id));
    } else {
        $_share['link'] = $setting['cannon_fodder'] . "app/" . $this->createMobileUrl('index', array('fmid' => $this->mid, 'activity_id' => $activity_id));
    }


    include $this->template('index');
    exit();
}

if ($op == 'share') {

    $activity_id = intval($_GPC['activity_id']);
    $update['share +='] = 1;
    pdo_update(YOUMI_NAME . '_activity', $update, ['id' => $activity_id]);
    youmi_result(0, '分享成功');
}


if ($op == 'sign_up') {

    $activity_id = intval($_GPC['activity_id']);
    $activity = pdo_get(YOUMI_NAME . '_activity', ['id' => $activity_id]);

    if ($activity['status'] != 1 || $activity['endtime'] < time()) {
        youmi_result(1, '活动已下架');
    }
    if ($activity['starttime'] > time()) {
        youmi_result(1, '活动未开始');
    }
    if ($activity['shop_id'] > 0) {
        $shop = pdo_get(UMI_NAME . '_shop', ['id' => $activity['shop_id']]);
        if ($shop['endtime'] < time() && $shop['times'] > $setting['vip_times']) {
            $activity['status'] = 2;
            youmi_result(1, '活动已下架');
        }
    }
    $goods_id = intval($_GPC['goods_id']);
    $cut = pdo_get(YOUMI_NAME . '_cut', ['mid' => $this->mid, 'activity_id' => $activity_id, 'goods_id' => $goods_id]);
    if ($cut) {
        youmi_result(1, '请勿重复报名');
    }
    $goods = pdo_get(YOUMI_NAME . '_goods', ['uniacid' => $uniacid, 'status' => 1, 'id' => $goods_id, 'activity_id' => $activity_id]);
    $tprice = floatval($goods['oprice']) - floatval($goods['rprice']);
    $price = $tprice / intval($goods['times']);
    if ($tprice == $price) {
        $cut['status'] = 2;
        $data['status'] = 2;
    } else {
        $arr = array(-1, 1); //初始化数组
        shuffle($arr); //打乱数组顺序
        $price += $price * 0.1 * array_shift($arr);
        $cut['status'] = 1;
        $data['status'] = 1;
    }
    $price = number_format($price, 2);
    $cut['activity_id'] = $activity_id;
    $cut['goods_id'] = $goods_id;
    $cut['shop_id'] = $goods['shop_id'];
    $cut['uniacid'] = $uniacid;
    $cut['mid'] = $this->mid;
    $cut['oprice'] = floatval($goods['oprice']);
    $cut['rprice'] = floatval($goods['rprice']);
    $cut['times'] = intval($goods['times']) - 1;
    $cut['price'] = $price;
    $cut['nprice'] = floatval($goods['oprice']) - $price;

    $cut['realname'] = trim($_GPC['realname']);
    $cut['mobile'] = trim($_GPC['mobile']);
    $cut['userinfo'] = $_GPC['userinfo'];

    $cut['createtime'] = time();
    pdo_insert(YOUMI_NAME . '_cut', $cut);
    $cut_id = pdo_insertid();
    $data['activity_id'] = $activity_id;
    $data['goods_id'] = $goods_id;
    $data['uniacid'] = $uniacid;
    $data['shop_id'] = $cut['shop_id'];
    $data['cut_id'] = $cut_id;
    $data['mid'] = $this->mid;
    $data['fmid'] = $cut['mid'];
    $data['oprice'] = floatval($cut['oprice']);
    $data['nprice'] = floatval($cut['nprice']);
    $data['price'] = $cut['price'];
    $data['nprice'] = floatval($cut['oprice']) - $price;
    $data['createtime'] = time();
    pdo_insert(YOUMI_NAME . '_record', $data);

    pdo_update(YOUMI_NAME . '_activity', ['participate +=' => 1], ['id' => $activity_id]);
    pdo_update(UMI_NAME . '_activity', ['participate +=' => 1], ['shop_id' => $activity['shop_id'], 'module' => YOUMI_NAME, 'activity_id' => $activity_id]);

    if (!$this->member['mobile']) {
        pdo_update(YOUMI_NAME . '_member', ['realname' => $cut['realname'], 'mobile' => $cut['mobile']], ['mid' => $this->mid]);
    }

    $data['schedule'] = (1 - number_format((floatval($cut['nprice']) - floatval($cut['rprice'])) / (floatval($cut['oprice']) - floatval($cut['rprice'])), 2)) * 100;

    youmi_result(0, '报名成功', $data);

}

if ($op == 'cut') {

    $activity_id = intval($_GPC['activity_id']);
    $activity = pdo_get(YOUMI_NAME . '_activity', ['id' => $activity_id]);
    if ($activity['status'] != 1 || $activity['endtime'] < time()) {
        youmi_result(1, '活动已下架');
    }
    if ($activity['shop_id'] > 0) {
        $shop = pdo_get(UMI_NAME . '_shop', ['id' => $activity['shop_id']]);
        if ($shop['endtime'] < time() && $shop['times'] > $setting['vip_times']) {
            $activity['status'] = 2;
            youmi_result(1, '活动已下架');
        }
    }
    $goods_id = intval($_GPC['goods_id']);
    $cut_id = intval($_GPC['cut_id']);
    if (!$cut_id) {
        youmi_result(1, '参数错误');
    }
    $cut = pdo_get(YOUMI_NAME . '_cut', ['id' => $cut_id, 'activity_id' => $activity_id, 'goods_id' => $goods_id, 'status' => 1]);
    if (!$cut) {
        youmi_result(1, '已砍到底');
    }
    $record = pdo_get(YOUMI_NAME . '_record', ['cut_id' => $cut_id, 'activity_id' => $activity_id, 'goods_id' => $goods_id, 'mid' => $this->mid]);
    if ($record || $cut['mid'] == $this->mid) {
        youmi_result(1, '请勿重复砍价');
    }
    if ($cut['times'] == 1) {
        $price = floatval($cut['nprice']) - floatval($cut['rprice']);
        $update['status'] = 2;
        $update['price'] = $price;
        $update['nprice'] = floatval($cut['rprice']);
        $data['status'] = 2;
    } else {
        $tprice = floatval($cut['nprice']) - floatval($cut['rprice']);
        $price = $tprice / floatval($cut['times']);
        $arr = array(-1, 1); //初始化数组
        shuffle($arr); //打乱数组顺序
        $price += $price * 0.1 * array_shift($arr);
        $price = number_format($price, 2);
        $update['price'] = $price;
        $update['nprice'] = floatval($cut['nprice']) - $price;
        $data['status'] = 1;
    }
    $cut['nprice'] = $update['nprice'];
    $update['times -='] = 1;
    pdo_update(YOUMI_NAME . '_cut', $update, ['id' => $cut_id, 'activity_id' => $activity_id, 'goods_id' => $goods_id]);

    $data['activity_id'] = $activity_id;
    $data['goods_id'] = $goods_id;
    $data['shop_id'] = $cut['shop_id'];
    $data['cut_id'] = $cut_id;
    $data['uniacid'] = $uniacid;
    $data['mid'] = $this->mid;
    $data['fmid'] = $cut['mid'];
    $data['oprice'] = floatval($cut['oprice']);
    $data['nprice'] = floatval($update['nprice']);
    $data['price'] = $update['price'];
    $data['createtime'] = time();
    pdo_insert(YOUMI_NAME . '_record', $data);

    $data['schedule'] = (1 - number_format((floatval($cut['nprice']) - floatval($cut['rprice'])) / (floatval($cut['oprice']) - floatval($cut['rprice'])), 2)) * 100;

  $good=  pdo_get(YOUMI_NAME.'_goods',['id'=>$goods_id]);

    //发送砍价通知
    $url=$_W['siteroot'] . "app/" .$this->createMobileUrl('index', array( 'activity_id' => $activity_id));
    $res=     handleCutMsg($_W['openid'],$good['title'],$data['nprice'],$url);

    youmi_result(0, '砍价成功', $data);

}
function handleCutMsg($openid,$k1,$k2,$url){

    $args=[
        'keyword1'=>$k1,
        'keyword2'=>$k2,
        'keyword3'=>date('Y-m-d H:i:s'),
        'keyword4'=>'多商品砍价',
    ];
    return sendCutMsg($openid,$args,$url);
}
function sendCutMsg($openid,$args,$url){

    $setting=  youmi_setting_get_list();
    $data = array(
        'first' => array(
            'value' => $setting['cut_first'],
            'color' => '#ff510'
        ),
        'keyword1' => array(
            'value' => $args['keyword1'],
            'color' => '#ff510'
        ),
        'keyword2' => array(
            'value' => $args['keyword2'],
            'color' => '#ff510'
        ),
        'keyword3' => array(
            'value' => $args['keyword3'],
            'color' => '#ff510'
        ),
        'keyword4' => array(
            'value' => $args['keyword4'],
            'color' => '#ff510'
        ),
        'remark' => array(
            'value' => $setting['cut_remark'],
            'color' => '#ff510'
        ),
    );

    $account_api = WeAccount::create();

    return $account_api->sendTplNotice($openid, $setting['cut_id'], $data,$url);
}
if ($op == 'complain') {

    $activity_id = intval($_GPC['activity_id']);
    $activity = pdo_get(YOUMI_NAME . '_activity', ['id' => $activity_id]);
    if ($activity['status'] != 1 || $activity['endtime'] < time()) {
        youmi_result(1, '活动已下架');
    }
    if ($activity['shop_id'] > 0) {
        $shop = pdo_get(UMI_NAME . '_shop', ['id' => $activity['shop_id']]);
        if ($shop['endtime'] < time() && $shop['times'] > $setting['vip_times']) {
            $activity['status'] = 2;
            youmi_result(1, '活动已下架');
        }
    }
    $complain = pdo_get(YOUMI_NAME . '_complain', ['activity_id' => $activity_id, 'userinfo' => $openid]);
    if ($complain) {
        youmi_result(1, '您已提交投诉信息，请勿重复投诉');
    }

    $data['uniacid'] = $uniacid;
    $data['activity_id'] = $activity_id;
    $data['shop_id'] = $activity['shop_id'];
    $data['title'] = $activity['title'];
    $data['reason'] = $_GPC['reason'];
    $data['desc'] = $_GPC['desc'];
    $data['mobile'] = $_GPC['mobile'];
    $data['userinfo'] = $openid;
    $data['createtime'] = TIMESTAMP;
    $status = pdo_insert(YOUMI_NAME . '_' . 'complain', $data);

    youmi_result(0, '投诉' . ($status ? '成功' : '失败'), $data);
}
//区域限制
if ($op == 'regional') {

    $activity_id = intval($_GPC['activity_id']);
    if (!$activity_id) {
        message('请选择对应活动');
    }
    $activity = pdo_fetch('select * from ' . tablename(YOUMI_NAME . '_activity') . ' where uniacid = ' . $uniacid . ' and id = ' . $activity_id);
    if (empty($activity['ak']) && $activity['regional'] == 2) {
        youmi_result(1, '未填写密钥', []);
    }
    load()->func('communication');
    $lng = $_GPC['lng'];
    $lat = $_GPC['lat'];
    $url = 'http://api.map.baidu.com/geocoder/v2/?location=' . $lat . ',' . $lng . '&output=json&pois=1&ak=' . $activity['ak'];
    $result = ihttp_get($url);
    $result = json_decode($result['content'], true);
    // var_dump($result);exit;
    $arr = array();
//    $arr['uniacid'] = $_W['uniacid'];
//    $arr['openid'] = $_W['openid'];
//    $arr['rid'] = $_GPC['rid'];
    $arr['lng'] = $lng;
    $arr['lat'] = $lat;
//    $arr['time'] = time();
//    $arr['date'] = date('Ymd');
//    $arr['country'] = $result['result']['addressComponent']['country'];
//    $arr['province'] = $result['result']['addressComponent']['province'];
//    $arr['city'] = $result['result']['addressComponent']['city'];
    $arr['district'] = $result['result']['addressComponent']['district'];
    $arr['address'] = $result['result']['formatted_address'];
    $arr['business'] = $result['result']['business'];
    $arr['r_address'] = unserialize($activity['r_address']);

    if (strpos($activity['r_address'], $arr['district']) !== false) {//包含
        youmi_result(0, '区域限制，可以报名', $arr);
    } else {//不包含
        youmi_result(1, '您不在活动区域范围内', $arr);
    }
    // var_dump($arr);exit;
}

