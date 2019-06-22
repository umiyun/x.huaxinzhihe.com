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
 * 返回
 * @param  [type] $status  [description]
 * @param  [type] $data    [description]
 * @param  [type] $message [description]
 * @return [type]          [description]
 */
if(!function_exists('youmi_result')) {
    function youmi_result($errno, $message = '', $data = [])
    {
        die(json_encode(['errno' => $errno, 'data' => $data, 'message' => $message ? $message : ''], JSON_UNESCAPED_UNICODE));
    }
}


/**
 * 发送短信验证码
 */
if(!function_exists('youmi_send_sms')) {
    function youmi_send_sms($mobile, $code)
    {

        $setting = youmi_setting_get_list();
        if (intval($setting['sms_type']) == 1) {
            if (!trim($setting['juhe_appkey']) || !trim($setting['juhe_tpl_id'])) {
                return [];
            }
            //获取今天 本手机号 发送次数
            $sendUrl = 'https://v.juhe.cn/sms/send'; //短信接口的URL
            $smsConf = array(
                'key' => trim($setting['juhe_appkey']),             //您申请的APPKEY
                'mobile' => trim($mobile),                          //接受短信的用户手机号码
                'tpl_id' => trim($setting['juhe_tpl_id']),          //聚合短信模板id
                'tpl_value' => '#code#=' . trim($code)              //'#code#='     //您设置的模板变量，根据实际情况修改
            );

            $content = juhecurl($sendUrl, $smsConf, 1); //请求发送短信
            if ($content) {
                $result = json_decode($content, true);
                $error_code = $result['error_code'];
                if ($error_code == 0) {

                }
                return ['errno' => $error_code, 'message' => ('温馨提示：' . $result['reason']), 'result' => $result];
            }
        } else if (trim($setting['sms_type']) == 2) {
            $params = array(
                'code' => trim($code) //code
            );
            $option = [
                'keyid' => trim($setting['ali_accesskey_id']),
                'keysecret' => trim($setting['ali_accesskey_secret']),
                'phonenumbers' => trim($mobile),
                'signname' => trim($setting['ali_sign']),
                'templatecode' => trim($setting['ali_code']),
                'templateparam' => $params
            ];
            youmi_internal_log('sms',$params);
            $result = sendSms($option);
            return ['errno' => $result['Code'] == 'OK' ? 0 : 1, 'message' => ('温馨提示：' . $result['Message']), 'result' => $result];
        }
    }
}

if(!function_exists('youmi_get_weather')) {
    function youmi_get_weather($cityname)
    {
        $setting = youmi_setting_get_list();

        //获取未来7天预报
        $sendUrl = 'http://v.juhe.cn/weather/index';        //天气接口URL
        $smsConf = array(
            'key' => trim($setting['juhe_weather_key']),    //您申请的APPKEY
            'cityname' => urlencode(trim($cityname)),       //城市名或城市ID，如："苏州"，需要utf8 urlencode
            'dtype' => '',                                  //返回数据格式：json或xml,默认json
            'format' => ''                                  //未来7天预报(future)两种返回格式，1或2，默认1
        );

        $content = juhecurl($sendUrl, $smsConf);            //获取未来7天预报

        if ($content) {
            $result = json_decode($content, true);
            $error_code = $result['error_code'];
            youmi_result($error_code, $result['reason'], $result);
        }
        youmi_result(1, $content);
    }
}


if(!function_exists('http_request')) {
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
}

if(!function_exists('umi_xml2array')) {
    function umi_xml2array($xmlString = '')
    {
        $targetArray = array();
        $xmlObject = simplexml_load_string($xmlString);
        $mixArray = (array)$xmlObject;
        foreach ($mixArray as $key => $value) {
            if (is_string($value)) {
                $targetArray[$key] = $value;
            }
            if (is_object($value)) {
                $targetArray[$key] = umi_xml2array($value->asXML());
            }
            if (is_array($value)) {
                foreach ($value as $zkey => $zvalue) {
                    if (is_numeric($zkey)) {
                        $targetArray[$key][] = umi_xml2array($zvalue->asXML());
                    }
                    if (is_string($zkey)) {
                        $targetArray[$key][$zkey] = umi_xml2array($zvalue->asXML());
                    }
                }
            }
        }
        return $targetArray;
    }
}

if(!function_exists('postData')) {
    function postData($post_data, $sendUrl)
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
            return 'Errno' . curl_error($curl);
        }
        curl_close($curl);
        return $result;
    }
}

if(!function_exists('httpGet')) {
    function httpGet($url)
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
}

//二进制转图片image/png
if(!function_exists('data_uri')) {
    function data_uri($contents, $mime)
    {
        $base64 = base64_encode($contents);
        return ('data:' . $mime . ';base64,' . $base64);
    }
}

if(!function_exists('umi_uploadimg')) {
    function umi_uploadimg($file, $type, $dest_dir = '', $nosave = 0)
    {
        global $_GPC, $_W;
        load()->func('file');
        load()->func('communication');

        $type = in_array($type, array('image', 'audio', 'video', 'qrcode')) ? $type : 'image';
        $option = array();
        $option = array_elements(array('uploadtype', 'global', 'dest_dir'), $_POST);
        $option['width'] = intval($option['width']);

        $setting = $_W['setting']['upload'][$type];
        $uniacid = intval($_W['uniacid']);

        $setting['folder'] = "{$type}s/{$uniacid}";
        if (empty($dest_dir)) {
            $setting['folder'] .= '/' . date('Y/m/');
        } else {
            $setting['folder'] .= '/' . $dest_dir . '/';
        }

        if (empty($file['name'])) {
            $result['message'] = '上传失败, 请选择要上传的文件！';
            $result['errno'] = 1;
            return $result;
        }
        if ($file['error'] != 0) {
            $result['message'] = '上传失败, 请重试.';
            $result['errno'] = 1;
            return $result;
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $ext = strtolower($ext);
        $size = intval($file['size']);
        $originname = $file['name'];
        $filename = file_random_name(ATTACHMENT_ROOT . '/' . $setting['folder'], $ext);

        //首先判断目录存在否
        if (!is_dir(ATTACHMENT_ROOT . '/' . $setting['folder'])) {
            //第3个参数“true”意思是能创建多级目录，iconv防止中文目录乱码
            $res = mkdir(iconv('UTF-8', 'GBK', ATTACHMENT_ROOT . '/' . $setting['folder']), 0777, true);
        }

        /**
         * [将Base64图片转换为本地图片并保存]
         */
        if ($file['file']) {
            //匹配出图片的格式
            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $file['file'], $result)) {
//                $filename .= $result[2];
                file_put_contents(ATTACHMENT_ROOT . '/' . $setting['folder'] . $filename, base64_decode(str_replace($result[1], '', $file['file'])));
                $pathname = $setting['folder'] . $filename;
            }
        } else {

            $file = file_upload($file, $type, $setting['folder'] . $filename);
            if (is_error($file)) {
                $result['message'] = $file['message'];
                $result['errno'] = 1;
                return $result;
            }
            $pathname = $file['path'];
        }
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
                    $result['errno'] = 1;
                    return $result;
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
                $result['errno'] = 1;
                return $result;
            } else {
                file_delete($pathname);
                $info['url'] = tomedia($pathname);
            }
        }
        if (!$nosave) {
            pdo_insert('core_attachment', array(
                'uniacid' => $uniacid,
                'uid' => $_W['uid'],
                'filename' => $originname,
                'attachment' => $pathname,
                'type' => $type == 'image' ? 1 : ($type == 'audio' || $type == 'voice' ? 2 : ($type == 'qrcode' ? 4 : 3)),
                'createtime' => TIMESTAMP
            ));
        }
        $result['message'] = tomedia($info['filename']);
        $result['errno'] = 0;
        return $result;
    }
}

if (!function_exists('tpl_form_field_category_3level')) {
    function tpl_form_field_category_3level($name, $parents, $children, $parentid, $childid, $thirdid, $disabled = false)
    {
        $html = "\r\n<script type=\"text/javascript\">\r\n\twindow._" . $name . ' = ' . json_encode($children) . ";\r\n</script>";

        if (!defined('TPL_INIT_CATEGORY_THIRD')) {
            $html .= "\t\r\n<script type=\"text/javascript\">\r\n\t  function renderCategoryThird(obj, name){\r\n\t\tvar index = obj.options[obj.selectedIndex].value;\r\n\t\trequire(['jquery', 'util'], function(\$, u){\r\n\t\t\t\$selectChild = \$('#'+name+'_child');\r\n                                                      \$selectThird = \$('#'+name+'_third');\r\n\t\t\tvar html = '<option value=\"0\">请选择二级分类</option>';\r\n                                                      var html1 = '<option value=\"0\">请选择三级分类</option>';\r\n\t\t\tif (!window['_'+name] || !window['_'+name][index]) {\r\n\t\t\t\t\$selectChild.html(html); \r\n                                                                        \$selectThird.html(html1);\r\n\t\t\t\treturn false;\r\n\t\t\t}\r\n\t\t\tfor(var i=0; i< window['_'+name][index].length; i++){\r\n\t\t\t\thtml += '<option value=\"'+window['_'+name][index][i]['id']+'\">'+window['_'+name][index][i]['name']+'</option>';\r\n\t\t\t}\r\n\t\t\t\$selectChild.html(html);\r\n                                                    \$selectThird.html(html1);\r\n\t\t});\r\n\t}\r\n        function renderCategoryThird1(obj, name){\r\n\t\tvar index = obj.options[obj.selectedIndex].value;\r\n\t\trequire(['jquery', 'util'], function(\$, u){\r\n\t\t\t\$selectChild = \$('#'+name+'_third');\r\n\t\t\tvar html = '<option value=\"0\">请选择三级分类</option>';\r\n\t\t\tif (!window['_'+name] || !window['_'+name][index]) {\r\n\t\t\t\t\$selectChild.html(html);\r\n\t\t\t\treturn false;\r\n\t\t\t}\r\n\t\t\tfor(var i=0; i< window['_'+name][index].length; i++){\r\n\t\t\t\thtml += '<option value=\"'+window['_'+name][index][i]['id']+'\">'+window['_'+name][index][i]['name']+'</option>';\r\n\t\t\t}\r\n\t\t\t\$selectChild.html(html);\r\n\t\t});\r\n\t}\r\n</script>\r\n\t\t\t";
            define('TPL_INIT_CATEGORY_THIRD', true);
        }

        $html .= "<div class=\"row row-fix tpl-category-container\">\r\n\t<div class=\"col-xs-12 col-sm-4 col-md-4 col-lg-4\">\r\n\t\t<select class=\"form-control tpl-category-parent\"" . ($disabled ? ' disabled' : '') . " id=\"" . $name . '_parent" name="' . $name . '[parentid]" onchange="renderCategoryThird(this,\'' . $name . "')\">\r\n\t\t\t<option value=\"0\">请选择一级分类</option>";
        $ops = '';

        foreach ($parents as $row) {
            $html .= "\r\n\t\t\t<option value=\"" . $row['id'] . '" ' . ($row['id'] == $parentid ? 'selected="selected"' : '') . '>' . $row['name'] . '</option>';
        }

        $html .= "\r\n\t\t</select>\r\n\t</div>\r\n\t<div class=\"col-xs-12 col-sm-4 col-md-4 col-lg-4\">\r\n\t\t<select class=\"form-control tpl-category-child\"" . ($disabled ? ' disabled' : '') . " id=\"" . $name . '_child" name="' . $name . '[childid]" onchange="renderCategoryThird1(this,\'' . $name . "')\">\r\n\t\t\t<option value=\"0\">请选择二级分类</option>";
        if (!empty($parentid) && !empty($children[$parentid])) {
            foreach ($children[$parentid] as $row) {
                $html .= "\r\n\t\t\t<option value=\"" . $row['id'] . '"' . ($row['id'] == $childid ? 'selected="selected"' : '') . '>' . $row['name'] . '</option>';
            }
        }

        $html .= "\r\n\t\t</select> \r\n\t</div> \r\n                  <div class=\"col-xs-12 col-sm-4 col-md-4 col-lg-4\">\r\n\t\t<select class=\"form-control tpl-category-child\"" . ($disabled ? ' disabled' : '') . " id=\"" . $name . '_third" name="' . $name . "[thirdid]\">\r\n\t\t\t<option value=\"0\">请选择三级分类</option>";
        if (!empty($childid) && !empty($children[$childid])) {
            foreach ($children[$childid] as $row) {
                $html .= "\r\n\t\t\t<option value=\"" . $row['id'] . '"' . ($row['id'] == $thirdid ? 'selected="selected"' : '') . '>' . $row['name'] . '</option>';
            }
        }

        $html .= "</select>\r\n\t</div>\r\n</div>";
        return $html;
    }
}


