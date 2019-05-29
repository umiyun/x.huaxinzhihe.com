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

/**
 * 小程序code换openid
 * @param string $code
 * @return array|mixed|void
 */
if(!function_exists('getOauthInfo')) {
    function getOauthInfo($code = '')
    {
        global $_W, $_GPC;

        $setting = youmi_setting_get_list();
        $appid = trim($setting["wxapp_appid"]);
        $secret = trim($setting["wxapp_secret"]);

        if (!empty($_GPC['code'])) {
            $code = $_GPC['code'];
        }
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$code}&grant_type=authorization_code";
        return $response = requestWxApi($url);
    }
}

/**
 * 获取小程序    access_token
 * @return array|mixed|void
 */
if(!function_exists('getAccessToken')) {
    function getAccessToken()
    {
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $setting = youmi_setting_get_list();

        $cachekey = YOUMI_NAME . '_' . $uniacid . '_wxapp';
        $cache = cache_load($cachekey);
        if (!empty($cache) && !empty($cache['token']) && $cache['expire'] > TIMESTAMP) {
            return $cache['token'];
        }

        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$setting['wxapp_appid']}&secret={$setting['wxapp_secret']}";
        $response = requestWxApi($url);

        $record = array();
        $record['token'] = $response['access_token'];
        $record['expire'] = TIMESTAMP + $response['expires_in'] - 200;

        cache_write($cachekey, $record);
        return $record['token'];
    }
}

/**
 * 获取小程序二维码     限制 100 000
 * @param $path
 * @param string $width
 * @param array $option
 * @return array|mixed|void
 */
if(!function_exists('getCodeLimit')) {
    function getCodeLimit($path, $width = '430', $option = array())
    {
        if (!preg_match("/[0-9a-zA-Z\&\/\:\=\?\-\.\_\~\@]{1,128}/", $path)) {
            return error(1, '路径值不合法');
        }
        $access_token = getAccessToken();
        if (is_error($access_token)) {
            return $access_token;
        }
        $data = array(
            'path' => $path,
            'width' => intval($width),
        );
        if (!empty($option['auto_color'])) {
            $data['auto_color'] = intval($option['auto_color']);
        }
        if (!empty($option['line_color'])) {
            $data['line_color'] = array(
                'r' => $option['line_color']['r'],
                'g' => $option['line_color']['g'],
                'b' => $option['line_color']['b'],
            );
            $data['auto_color'] = false;
        }
        $url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=" . $access_token;
        $response = requestWxApi($url, json_encode($data));
        if (is_error($response)) {
            return $response;
        }
        return $response['content'];
    }
}

/**
 * 获取小程序二维码     无限制
 * @param $scene
 * @param string $path
 * @param string $width
 * @param array $option
 * @return array|mixed|void
 */
if(!function_exists('getCodeUnlimit')) {
    function getCodeUnlimit($scene, $path = '', $width = '430', $option = array())
    {
        if (!preg_match("/[0-9a-zA-Z\!\#\$\&\'\(\)\*\+\,\/\:\;\=\?\@\-\.\_\~]{1,32}/", $scene)) {
            return error(1, '场景值不合法');
        }
        $access_token = getAccessToken();
        if (is_error($access_token)) {
            return $access_token;
        }
        $data = array(
            'scene' => $scene,
            'width' => intval($width)
        );
        if (!empty($path)) {
            $data['page'] = $path;
        }
        if (!empty($option['auto_color'])) {
            $data['auto_color'] = intval($option['auto_color']);
        }

        if (!empty($option['line_color'])) {
            $data['line_color'] = array(
                'r' => $option['line_color']['r'],
                'g' => $option['line_color']['g'],
                'b' => $option['line_color']['b'],
            );
            $data['auto_color'] = false;
        }
        $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . $access_token;
        $response = requestWxApi($url, json_encode($data));
        if (is_error($response)) {
            return $response;
        }
        return $response['content'];
    }
}

if(!function_exists('requestWxApi')) {
    function requestWxApi($url, $post = '')
    {
        $response = ihttp_request($url, $post);
        $result = @json_decode($response['content'], true);
        if (is_error($response)) {
            return error($result['errcode'], "访问公众平台接口失败, 错误详情: " . errorCode($result['errcode']));
        }
        if (empty($result)) {
            return $response;
        } elseif (!empty($result['errcode'])) {
            return error($result['errcode'], "访问公众平台接口失败, 错误: {$result['errmsg']},错误详情：" . errorCode($result['errcode']));
        }
        return $result;
    }
}

if(!function_exists('pkcs7Encode')) {
    function pkcs7Encode($encrypt_data, $iv)
    {
        $key = base64_decode($_SESSION['session_key']);
        $result = aes_pkcs7_decode($encrypt_data, $key, $iv);
        if (is_error($result)) {
            return error(1, '解密失败');
        }
        $result = json_decode($result, true);
        if (empty($result)) {
            return error(1, '解密失败');
        }
        $setting = youmi_setting_get_list();

        if ($result['watermark']['appid'] != $setting['wxapp_appid']) {
            return error(1, '解密失败');
        }
        unset($result['watermark']);
        return $result;
    }
}

if(!function_exists('errorCode')) {
    function errorCode($code, $errmsg = '未知错误')
    {
        $errors = array(
            '-1' => '系统繁忙',
            '0' => '请求成功',
            '40001' => '获取access_token时AppSecret错误，或者access_token无效',
            '40002' => '不合法的凭证类型',
            '40003' => '不合法的OpenID',
            '40004' => '不合法的媒体文件类型',
            '40005' => '不合法的文件类型',
            '40006' => '不合法的文件大小',
            '40007' => '不合法的媒体文件id',
            '40008' => '不合法的消息类型',
            '40009' => '不合法的图片文件大小',
            '40010' => '不合法的语音文件大小',
            '40011' => '不合法的视频文件大小',
            '40012' => '不合法的缩略图文件大小',
            '40013' => '不合法的APPID',
            '40014' => '不合法的access_token',
            '40015' => '不合法的菜单类型',
            '40016' => '不合法的按钮个数',
            '40017' => '不合法的按钮个数',
            '40018' => '不合法的按钮名字长度',
            '40019' => '不合法的按钮KEY长度',
            '40020' => '不合法的按钮URL长度',
            '40021' => '不合法的菜单版本号',
            '40022' => '不合法的子菜单级数',
            '40023' => '不合法的子菜单按钮个数',
            '40024' => '不合法的子菜单按钮类型',
            '40025' => '不合法的子菜单按钮名字长度',
            '40026' => '不合法的子菜单按钮KEY长度',
            '40027' => '不合法的子菜单按钮URL长度',
            '40028' => '不合法的自定义菜单使用用户',
            '40029' => '不合法的oauth_code',
            '40030' => '不合法的refresh_token',
            '40031' => '不合法的openid列表',
            '40032' => '不合法的openid列表长度',
            '40033' => '不合法的请求字符，不能包含\uxxxx格式的字符',
            '40035' => '不合法的参数',
            '40038' => '不合法的请求格式',
            '40039' => '不合法的URL长度',
            '40050' => '不合法的分组id',
            '40051' => '分组名字不合法',
            '41001' => '缺少access_token参数',
            '41002' => '缺少appid参数',
            '41003' => '缺少refresh_token参数',
            '41004' => '缺少secret参数',
            '41005' => '缺少多媒体文件数据',
            '41006' => '缺少media_id参数',
            '41007' => '缺少子菜单数据',
            '41008' => '缺少oauth code',
            '41009' => '缺少openid',
            '42001' => 'access_token超时',
            '42002' => 'refresh_token超时',
            '42003' => 'oauth_code超时',
            '43001' => '需要GET请求',
            '43002' => '需要POST请求',
            '43003' => '需要HTTPS请求',
            '43004' => '需要接收者关注',
            '43005' => '需要好友关系',
            '44001' => '多媒体文件为空',
            '44002' => 'POST的数据包为空',
            '44003' => '图文消息内容为空',
            '44004' => '文本消息内容为空',
            '45001' => '多媒体文件大小超过限制',
            '45002' => '消息内容超过限制',
            '45003' => '标题字段超过限制',
            '45004' => '描述字段超过限制',
            '45005' => '链接字段超过限制',
            '45006' => '图片链接字段超过限制',
            '45007' => '语音播放时间超过限制',
            '45008' => '图文消息超过限制',
            '45009' => '接口调用超过限制',
            '45010' => '创建菜单个数超过限制',
            '45015' => '回复时间超过限制',
            '45016' => '系统分组，不允许修改',
            '45017' => '分组名字过长',
            '45018' => '分组数量超过上限',
            '45056' => '创建的标签数过多，请注意不能超过100个',
            '45057' => '该标签下粉丝数超过10w，不允许直接删除',
            '45058' => '不能修改0/1/2这三个系统默认保留的标签',
            '45059' => '有粉丝身上的标签数已经超过限制',
            '45157' => '标签名非法，请注意不能和其他标签重名',
            '45158' => '标签名长度超过30个字节',
            '45159' => '非法的标签',
            '46001' => '不存在媒体数据',
            '46002' => '不存在的菜单版本',
            '46003' => '不存在的菜单数据',
            '46004' => '不存在的用户',
            '47001' => '解析JSON/XML内容错误',
            '48001' => 'api功能未授权',
            '50001' => '用户未授权该api',
            '40070' => '基本信息baseinfo中填写的库存信息SKU不合法。',
            '41011' => '必填字段不完整或不合法，参考相应接口。',
            '40056' => '无效code，请确认code长度在20个字符以内，且处于非异常状态（转赠、删除）。',
            '43009' => '无自定义SN权限，请参考开发者必读中的流程开通权限。',
            '43010' => '无储值权限,请参考开发者必读中的流程开通权限。',
            '43011' => '无积分权限,请参考开发者必读中的流程开通权限。',
            '40078' => '无效卡券，未通过审核，已被置为失效。',
            '40079' => '基本信息base_info中填写的date_info不合法或核销卡券未到生效时间。',
            '45021' => '文本字段超过长度限制，请参考相应字段说明。',
            '40080' => '卡券扩展信息cardext不合法。',
            '40097' => '基本信息base_info中填写的参数不合法。',
            '49004' => '签名错误。',
            '43012' => '无自定义cell跳转外链权限，请参考开发者必读中的申请流程开通权限。',
            '40099' => '该code已被核销。',
            '61005' => '缺少接入平台关键数据，等待微信开放平台推送数据，请十分钟后再试或是检查“授权事件接收URL”是否写错（index.php?c=account&amp;a=auth&amp;do=ticket地址中的&amp;符号容易被替换成&amp;amp;）',
            '61023' => '请重新授权接入该公众号',
        );
        $code = strval($code);
        if ($errors[$code]) {
            return $errors[$code];
        } else {
            return $errmsg;
        }
    }
}

