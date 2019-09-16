<?php
/**
 * Created by PhpStorm.
 * User: hc-101
 * Date: 2018/1/16
 * Time: 下午2:52
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}

require IA_ROOT . '/addons/weddingh5/core/defines.php';

class Weddingh5ModuleSite extends WeModuleSite
{
    private $uid;
    private $username;
    private $member;
    private $openid;
    private $user_type;

    public function __construct()
    {
        global $_GPC, $_W;
        $this->uniacid = intval($_W['uniacid']);
        if ($_W['user']) {
            $this->uid = $_W['uid'];
            $this->username = trim($_W['username']);
            $this->user_type = 1;
        } else {
            $this->openid = $_W['openid'] ? $_W['openid'] : ($_W['fans']['openid'] ? $_W['fans']['openid'] : $_GPC['openid']);
            $this->member = $this->getMemberInfo($this->openid);
            $this->uid = $this->member['uid'];
            $this->username = $this->member['nickname'];
            $this->user_type = 2;
        }

    }

    /**
     * 获取会员信息
     * @param $openid
     * @return bool|string
     */
    public function getMemberInfo($openid, $user_type = null)
    {
        $uniacid = $this->uniacid;
        if (!$user_type) {
            $user_type = $this->user_type;
        }
        if ($user_type != 1) {
            if (empty($openid)) {
                $openid = $this->openid;
            }
            $member = pdo_fetch('SELECT em.*,m.credit1,m.credit2,m.avatar,m.gender FROM ' . tablename('mc_mapping_fans') . ' AS em left join ' . tablename('mc_members') . ' AS m on em.uid = m.uid where em.uniacid = :uniacid and em.openid = :openid ', array(':uniacid' => $uniacid, ':openid' => $openid));
        } else {
            $member = pdo_fetch('SELECT em.*,m.credit1,m.credit2,m.avatar,m.gender FROM ' . tablename('mc_mapping_fans') . ' AS em left join ' . tablename('mc_members') . ' AS m on em.uid = m.uid where em.uniacid = :uniacid and em.uid = :openid ', array(':uniacid' => $uniacid, ':openid' => $openid));
        }
        if (empty($member)) {
            return '';
        }

        $member['avatar'] = str_replace('132132', '132', $member['avatar']);
        $member['tag'] = iunserializer(base64_decode($member['tag']));
        return $member;
    }

    /**
     * 获取会员信息
     * @param $openid
     * @return bool|string
     */
    public function getMemberKeyword($keyword)
    {
        $uniacid = $this->uniacid;
        $con = '';
        $pamas['uniacid'] = $uniacid;
        $con .= ' and (m.nickname like \'%' . $keyword . '%\' or m.mobile like \'%' . $keyword . '%\')';

        $member = pdo_fetchall('SELECT em.*,m.credit1,m.credit2,m.avatar,m.gender FROM ' . tablename('mc_mapping_fans') . ' AS em left join ' . tablename('mc_members') . ' AS m on em.uid = m.uid where em.uniacid = :uniacid ' . $con, $pamas);
        foreach ($member as &$item) {
            $item['avatar'] = str_replace('132132', '132', $item['avatar']);
            $item['tag'] = iunserializer(base64_decode($item['tag']));
            unset($item);
        }

        return $member;
    }

    //操作日志
    public function mayi_app_demo_log($op, $content, $user_type = null)
    {
        if (!$user_type) {
            $user_type = $this->user_type;
        }
        $data['uniacid'] = $this->uniacid;
        $data['user_type'] = $user_type;
        $data['uid'] = $this->uid;
        $data['openid'] = $this->openid;
        $data['username'] = $this->username;
        $data['op'] = $op;
        $data['content'] = $content;
        $data['createtime'] = TIMESTAMP;
        $res = pdo_insert(MC_NAME . '_' . 'log', $data);
        $id = pdo_insertid();
        return $id;
    }

    /**
     * 日志
     * @param $path
     * @param $args
     * @param $uniacid
     */
    function internal_log($path, $args, $uniacid)
    {

        global $_W;

        if (!$uniacid) {
            $uniacid = $_W['uniacid'];
        }
        //TODO 创建日志
        $path = IA_ROOT . '/addons/' . MC_NAME . '/data/log/' . $uniacid . '/' . $path . '/';
        //首先判断目录存在否
        if (!is_dir($path)) {
            //第3个参数“true”意思是能创建多级目录，iconv防止中文目录乱码
            $res = mkdir(iconv("UTF-8", "GBK", $path), 0777, true);
        }
        $date = date('Y-m-d', TIMESTAMP);
        file_put_contents($path . $date . ".log", var_export(
                array(
                    'ip' => CLIENT_IP,
                    'uniacid' => $uniacid,
                    'args' => $args, //传入值
                    'time' => date('Y-m-d H:i:s', TIMESTAMP)
                ),
                true) . PHP_EOL, FILE_APPEND);

    }
    /**
     * 经纬转地址
     * 接口一个名为'address'的接口
     * 响应json串.
     */
    public function doPageAddress()
    {
        global $_GPC;
        $address = $this->latlagtoaddress($_GPC['lat'], $_GPC['lng']);
        $this->result(0, '经纬转地址', $address);
    }

    /**
     * 经纬转地址
     * @param $lat , $lng  经纬
     * @return array    返回详细地址
     */
    function latlagtoaddress($lat, $lng)
    {
        $url = 'http://api.map.baidu.com/geocoder?location=' . $lat . "," . $lng . '&output=xml&key=28bcdd84fae25699606ffad27f8da77b';
        if ($result = $this->http_request($url)) {
            if ($result) {
                $res = $this->xml_to_array($result, true);
                return $res['GeocoderSearchResponse']['result']['addressComponent'];
            }
        }
        return '';
    }

    function http_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    function xml_to_array($xml)
    {
        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if (preg_match_all($reg, $xml, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i++) {
                $subxml = $matches[2][$i];
                $key = $matches[1][$i];
                if (preg_match($reg, $subxml)) {
                    $arr[$key] = $this->xml_to_array($subxml);
                } else {
                    $arr[$key] = $subxml;
                }
            }
        }
        return $arr;
    }

    //图片上传
    public function doPageuploadimg()
    {
        global $_GPC, $_W;
        load()->func('file');
        load()->func('communication');

        $type = $_COOKIE['__fileupload_type'];
        $type = in_array($type, array('image', 'audio', 'video')) ? $type : 'image';
        $option = array();
        $option = array_elements(array('uploadtype', 'global', 'dest_dir'), $_POST);
        $option['width'] = intval($option['width']);
        $option['global'] = !empty($_COOKIE['__fileupload_global']);
        if (!empty($option['global']) && empty($_W['isfounder'])) {
            $result['message'] = '没有向 global 文件夹上传文件的权限.';
            die(json_encode($result));
        }

        $dest_dir = $_COOKIE['__fileupload_dest_dir'];
        if (preg_match('/^[a-zA-Z0-9_\/]{0,50}$/', $dest_dir, $out)) {
            $dest_dir = trim($dest_dir, '/');
            $pieces = explode('/', $dest_dir);
            if (count($pieces) > 3) {
                $dest_dir = '';
            }
        } else {
            $dest_dir = '';
        }

        $setting = $_W['setting']['upload'][$type];
        $uniacid = intval($_W['uniacid']);

        if (!empty($option['global'])) {
            $setting['folder'] = "{$type}s/global/";
            if (!empty($dest_dir)) {
                $setting['folder'] .= '/' . $dest_dir . '/';
            }
        } else {
            $setting['folder'] = "{$type}s/{$uniacid}";
            if (empty($dest_dir)) {
                $setting['folder'] .= '/' . date('Y/m/');
            } else {
                $setting['folder'] .= '/' . $dest_dir . '/';
            }
        }
//        die(json_encode($_FILES));
        if (empty($_FILES['file']['name'])) {
            $result['message'] = '上传失败, 请选择要上传的文件！';
            die(json_encode($result));
        }
        if ($_FILES['file']['error'] != 0) {
            $result['message'] = '上传失败, 请重试.';
            die(json_encode($result));
        }

        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $ext = strtolower($ext);
        $size = intval($_FILES['file']['size']);
        $originname = $_FILES['file']['name'];
        $filename = file_random_name(ATTACHMENT_ROOT . '/' . $setting['folder'], $ext);
        $file = file_upload($_FILES['file'], $type, $setting['folder'] . $filename);
        if (is_error($file)) {
            $result['message'] = $file['message'];
            die(json_encode($result));
        }
        $pathname = $file['path'];
        $fullname = ATTACHMENT_ROOT . '/' . $pathname;
        if ($type == 'image') {
            $thumb = empty($setting['thumb']) ? 0 : 1;
            $width = intval($setting['width']);
            if (isset($option['thumb'])) {
                $thumb = empty($option['thumb']) ? 0 : 1;
            }
            if (isset($option['width']) && !empty($option['width'])) {
                $width = intval($option['width']);
            }
            if ($thumb == 1 && $width > 0) {
                $thumbnail = file_image_thumb($fullname, '', $width);
                @unlink($fullname);
                if (is_error($thumbnail)) {
                    $result['message'] = $thumbnail['message'];
                    die(json_encode($result));
                } else {
                    $filename = pathinfo($thumbnail, PATHINFO_BASENAME);
                    $pathname = $thumbnail;
                    $fullname = ATTACHMENT_ROOT . '/' . $pathname;
                }
            }
        }
        $info = array(
            'name' => $originname,
            'ext' => $ext,
            'filename' => $pathname,
            'attachment' => $pathname,
            'url' => tomedia($pathname),
            'is_image' => $type == 'image' ? 1 : 0,
            'filesize' => filesize($fullname),
        );
        if ($type == 'image') {
            $size = getimagesize($fullname);
            $info['width'] = $size[0];
            $info['height'] = $size[1];
        } else {
            $size = filesize($fullname);
            $info['size'] = sizecount($size);
        }
        if (!empty($_W['setting']['remote']['type'])) {
            $remotestatus = file_remote_upload($pathname);
            if (is_error($remotestatus)) {
                $result['message'] = '远程附件上传失败，请检查配置并重新上传';
                file_delete($pathname);
                die(json_encode($result));
            } else {
                file_delete($pathname);
                $info['url'] = tomedia($pathname);
            }
        }
        pdo_insert('core_attachment', array(
            'uniacid' => $uniacid,
            'uid' => $_W['uid'],
            'filename' => $originname,
            'attachment' => $pathname,
            'type' => $type == 'image' ? 1 : ($type == 'audio' || $type == 'voice' ? 2 : 3),
            'createtime' => TIMESTAMP
        ));

        die(json_encode($info['filename']));
    }

    /**
     * 创建充值订单
     * 接口一个名为'recharge'的接口
     * 响应json串.
     */
    public function doPageRecharge()
    {
        global $_W, $_GPC;

        $data['openid'] = $this->openid;
        $data['uid'] = $this->uid;
        $data['integral_id'] = intval($_GPC['integral_id']);
        $data['type'] = $_GPC['type'];
        $integral = pdo_get(MC_NAME . '_' . 'integral', ['id' => $data['integral_id']]);
        if ($data['type'] == 1) {
            $data['parprice'] = $integral['parprice'];
            $data['price'] = $integral['unit_price'];
        } elseif ($data['type'] == 2) {
            $data['parprice'] = $integral['parprice'];
            $data['price'] = $integral['group_price'];
        }
        $data['status'] = 0;
        $data['createtime'] = TIMESTAMP;

        $this->post_table(0, $data, 'recharge', 2, '下单');

    }

    /**
     *  执行支付.
     */
    public function doPagePay()
    {
        //模拟模块数据 支付需要 正式版本无需这行代码
//		$this->module = array('name' => 'we7_wxappsample');
        //构造订单数据
        $orderid = $this->get('orderid', null);
        // 判断权限
        $recharge = $this->hasOrder($orderid);
        if (!$recharge) {
            $this->result(1, '非用户订单');
        }
//		$this->result(1, '非用户订单');
        $order = array(
            'tid' => $orderid, //订单号
            'fee' => floatval($recharge['price']), //支付参数
            'title' => '充值' . $recharge['price'] . '积分', //标题
        );
        $paydata = $this->pay($order);
        if (is_error($paydata)) {
            $this->result($paydata['errno'], $paydata['message']);
        }
        $this->result(0, '', $paydata);
    }

    // 判断当前用户有没这个订单
    private function hasOrder($orderid)
    {
        $recharge = pdo_get(MC_NAME . '_' . 'recharge', ['id' => $orderid]);
        if (!$recharge) {
            return false;
        }
        return $recharge;
    }

    /**
     * 获取支付结果.
     */
    public function doPagePayResult()
    {
        global $_GPC;
        global $_W;
        $orderid = $_GPC['orderid'];
        $order_type = trim($_GPC['order_type']);
        //订单id
        $paylog = pdo_get('core_paylog', array('uniacid' => $this->uniacid, 'module' => MC_NAME, 'tid' => $orderid));
        $status = intval($paylog['status']) === 1;
        if ($status) {
            pdo_update(MC_NAME . '_' . 'recharge', ['status' => 1], ['id' => $orderid]);
            $recharge = pdo_get(MC_NAME . '_' . 'recharge', ['id' => $orderid]);
            pdo_update('mc_members', ['credit2 +=' => floatval($recharge['parprice'])], ['uid' => $this->uid]);
        }
        $this->result($status, $status ? '支付成功' : '支付失败');
    }

    public function postData($post_data, $sendUrl)
    {
        $post_data = json_encode($post_data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $sendUrl);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            return "Errno" . curl_error($curl);
        }
        curl_close($curl);
        return $result;
    }

    public function httpGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }

    function juhecurl($url, $params = false, $ispost = 0)
    {
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }
        $response = curl_exec($ch);
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }

}