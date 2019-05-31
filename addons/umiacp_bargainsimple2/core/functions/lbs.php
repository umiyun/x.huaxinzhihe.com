<?php
/**
 * Created by IntelliJ IDEA.
 * User: 7?
 * Date: 2018/7/30 0030
 * Time: 12:18
 */

if (!defined('IN_IA')) {
    exit('Access Denied');
}


/**
 * 经纬转地址    百度
 * @param $lat , $lng  经纬
 * @return array    返回详细地址
 */
if(!function_exists('latlagtoaddress')) {
    function latlagtoaddress($lat, $lng)
    {
        $setting = youmi_setting_get_list();//28bcdd84fae25699606ffad27f8da77b
        $url = 'http://api.map.baidu.com/geocoder?location=' . $lat . ',' . $lng . '&output=xml&key=' . $setting['baidu_ak'];
        if ($result = http_request($url)) {
            if ($result) {
                $res = umi_xml2array($result, true);

                $address = $res['result']['addressComponent'];
                $address['business'] = $res['result']['business'];
                $address['cityCode'] = $res['result']['cityCode'];
                $address['formatted_address'] = $res['result']['formatted_address'];
                $address['location'] = $res['result']['location'];
                $address['errno'] = 0;
                return $address;
            }
        }
        return ['errno' => 1];
    }
}

/**
 * 地址转经纬    百度
 * @param $address  详细地址
 * @return array    返回经纬数组
 */
if(!function_exists('addresstolatlag')) {
    function addresstolatlag($address)
    {
        $setting = youmi_setting_get_list();//FWveGItlcOaaMSjRf6Vo4tiOkwCxYsQ4
        $url = 'http://api.map.baidu.com/geocoder/v2/?address=' . $address . '&output=json&ak=' . $setting['baidu_ak'];
        $result = http_request($url);

        if ($result) {
            if ($result) {
                $res = json_decode($result, true);
                $data['errno'] = $res['status'];
                $data['location'] = $res['result']['location'];
                $data['result'] = $res['result'];
                return $data;
            }
        }
        $res['errno'] = 1;
        return $res;
    }
}

/**
 * 两点经纬算距离      默认超过2000m显示2km
 * @param $lat1
 * @param $lng1
 * @param $lat2
 * @param $lng2
 * @param int $type
 * @return float|int|string
 */
if(!function_exists('getDistance')) {
    function getDistance($lat1, $lng1, $lat2, $lng2, $type = 1)
    {

        $earthRadius = 6367000; //approximate radius of earth in meters
        $lat1 = ($lat1 * pi()) / 180;
        $lng1 = ($lng1 * pi()) / 180;
        $lat2 = ($lat2 * pi()) / 180;
        $lng2 = ($lng2 * pi()) / 180;
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        if ($type == 1) {
            if (round($calculatedDistance, 5) > 2000) {
                $calculatedDistance = round($calculatedDistance / 1000, 2) . 'km';
            } else {
                $calculatedDistance = round($calculatedDistance, 0) . 'm';
            }
        }
        return $calculatedDistance;
    }
}

/**
 * 火星坐标系 (GCJ-02) 与百度坐标系 (BD-09) 的转换算法 将 GCJ-02 坐标转换成 BD-09 坐标
 *
 * @param gg_lat
 * @param gg_lon
 * @return
 */
if(!function_exists('gcj02_To_Bd09')) {
    function gcj02_To_Bd09($gg_lon, $gg_lat)
    {
        $x = $gg_lon;
        $y = $gg_lat;
        $z = Math . sqrt($x * $x + $y * $y) + 0.00002 * Math . sin($y * pi());
        $theta = Math . atan2($y, $x) + 0.000003 * Math . cos($x * pi());
        $bd_lon = $z * Math . cos($theta) + 0.0065;
        $bd_lat = $z * Math . sin($theta) + 0.006;
        return array($bd_lon, $bd_lat);
    }
}

/**
 * 火星坐标系 (GCJ-02) 与百度坐标系 (BD-09) 的转换算法   将 BD-09 坐标转换成GCJ-02 坐标
 *
 * @param bd_lon
 * @param bd_lat
 * @return
 */
if(!function_exists('bd09_To_Gcj02')) {
    function bd09_To_Gcj02($bd_lon, $bd_lat)
    {
        $x = $bd_lon - 0.0065;
        $y = $bd_lat - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * pi());
        $theta = atan2($y, $x) - 0.000003 * cos($x * pi());
        $gg_lon = $z * cos($theta);
        $gg_lat = $z * sin($theta);
        return array($gg_lon, $gg_lat);
    }
}

/**
 * 利用百度地图坐标转换API实现gcj02转bd09
 * @param $address  详细地址
 * @return array    返回经纬数组
 */
if (!function_exists('g02Tb09')) {
    function g02Tb09($coords)
    {
        $setting = youmi_setting_get_list();//FWveGItlcOaaMSjRf6Vo4tiOkwCxYsQ4
        $url = 'http://api.map.baidu.com/geoconv/v1/?coords=' . $coords . '&ak=' . $setting['baidu_ak'];
        $result = http_request($url);

        if ($result) {
            if ($result) {
                $res = json_decode($result, true);
                $data['errno'] = $res['status'];
                $data['location'] = $res['result'][0];
                $data['result'] = $res['result'];
                return $data;
            }
        }
        $res['errno'] = 1;
        return $res;
    }
}


/**
 * 二维数组根据首字母分组排序
 * @param  array $data 二维数组
 * @param  string $targetKey 首字母的键名
 * @return array             根据首字母关联的二维数组
 */
if(!function_exists('groupByInitials')) {
    function groupByInitials(array $data, $targetKey = 'name')
    {
        $data = array_map(function ($item) use ($targetKey) {
            return array_merge($item, [
                'initials' => getfirstchar($item[$targetKey]),
            ]);
        }, $data);
        $data = sortInitials($data);
        return $data;
    }
}

/**
 * 按字母排序
 * @param  array $data
 * @return array
 */
if(!function_exists('sortInitials')) {
    function sortInitials(array $data)
    {
        $sortData = [];
        foreach ($data as $key => $value) {
            $sortData[$value['initials']][] = $value;
        }
        ksort($sortData);
        return $sortData;
    }
}

/**
 * @name: getfirstchar
 * @description: 获取汉子首字母
 * @param: string
 * @return: mixed
 * @author:
 * @create: 2014-09-17 21:46:52
 **/
if(!function_exists('getfirstchar')) {
    function getfirstchar($s0)
    {
        $fchar = ord($s0{0});
        if ($fchar >= ord("A") and $fchar <= ord("z")) return strtoupper($s0{0});
        //$s1 = iconv("UTF-8","gb2312//IGNORE", $s0);
        // $s2 = iconv("gb2312","UTF-8//IGNORE", $s1);
        $s1 = get_encoding($s0, 'GB2312');
        $s2 = get_encoding($s1, 'UTF-8');
        if ($s2 == $s0) {
            $s = $s1;
        } else {
            $s = $s0;
        }
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 and $asc <= -20284) return "A";
        if ($asc >= -20283 and $asc <= -19776) return "B";
        if ($asc >= -19775 and $asc <= -19219) return "C";
        if ($asc >= -19218 and $asc <= -18711) return "D";
        if ($asc >= -18710 and $asc <= -18527) return "E";
        if ($asc >= -18526 and $asc <= -18240) return "F";
        if ($asc >= -18239 and $asc <= -17923) return "G";
        if ($asc >= -17922 and $asc <= -17418) return "I";
        if ($asc >= -17417 and $asc <= -16475) return "J";
        if ($asc >= -16474 and $asc <= -16213) return "K";
        if ($asc >= -16212 and $asc <= -15641) return "L";
        if ($asc >= -15640 and $asc <= -15166) return "M";
        if ($asc >= -15165 and $asc <= -14923) return "N";
        if ($asc >= -14922 and $asc <= -14915) return "O";
        if ($asc >= -14914 and $asc <= -14631) return "P";
        if ($asc >= -14630 and $asc <= -14150) return "Q";
        if ($asc >= -14149 and $asc <= -14091) return "R";
        if ($asc >= -14090 and $asc <= -13319) return "S";
        if ($asc >= -13318 and $asc <= -12839) return "T";
        if ($asc >= -12838 and $asc <= -12557) return "W";
        if ($asc >= -12556 and $asc <= -11848) return "X";
        if ($asc >= -11847 and $asc <= -11056) return "Y";
        if ($asc >= -11055 and $asc <= -10247) return "Z";
        return null;
    }
}

/**
 * @name: get_encoding
 * @description: 自动检测内容编码进行转换
 * @param: string data
 * @param: string to  目标编码
 * @return: string
 **/
if(!function_exists('get_encoding')) {
    function get_encoding($data, $to)
    {
        $encode_arr = array('UTF-8', 'ASCII', 'GBK', 'GB2312', 'BIG5', 'JIS', 'eucjp-win', 'sjis-win', 'EUC-JP');
        $encoded = mb_detect_encoding($data, $encode_arr);
        $data = mb_convert_encoding($data, $to, $encoded);
        return $data;
    }
}

/**
 * 获取首字母
 * @param  string $str 汉字字符串
 * @return string 首字母
 */
if(!function_exists('getInitials')) {
    function getInitials($str)
    {
        if (empty($str)) {
            return '';
        }
        $fchar = ord($str{0});
        if ($fchar >= ord('A') && $fchar <= ord('z')) {
            return strtoupper($str{0});
        }

        $s1 = iconv('UTF-8', 'gb2312', $str);
        $s2 = iconv('gb2312', 'UTF-8', $s1);
        $s = $s2 == $str ? $s1 : $str;

        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;

        if ($asc >= -20319 && $asc <= -20284) {
            return 'A';
        }

        if ($asc >= -20283 && $asc <= -19776) {
            return 'B';
        }

        if ($asc >= -19775 && $asc <= -19219) {
            return 'C';
        }

        if ($asc >= -19218 && $asc <= -18711) {
            return 'D';
        }

        if ($asc >= -18710 && $asc <= -18527) {
            return 'E';
        }

        if ($asc >= -18526 && $asc <= -18240) {
            return 'F';
        }

        if ($asc >= -18239 && $asc <= -17923) {
            return 'G';
        }

        if ($asc >= -17922 && $asc <= -17418) {
            return 'H';
        }

        if ($asc >= -17417 && $asc <= -16475) {
            return 'J';
        }

        if ($asc >= -16474 && $asc <= -16213) {
            return 'K';
        }

        if ($asc >= -16212 && $asc <= -15641) {
            return 'L';
        }

        if ($asc >= -15640 && $asc <= -15166) {
            return 'M';
        }

        if ($asc >= -15165 && $asc <= -14923) {
            return 'N';
        }

        if ($asc >= -14922 && $asc <= -14915) {
            return 'O';
        }

        if ($asc >= -14914 && $asc <= -14631) {
            return 'P';
        }

        if ($asc >= -14630 && $asc <= -14150) {
            return 'Q';
        }

        if ($asc >= -14149 && $asc <= -14091) {
            return 'R';
        }

        if ($asc >= -14090 && $asc <= -13319) {
            return 'S';
        }

        if ($asc >= -13318 && $asc <= -12839) {
            return 'T';
        }

        if ($asc >= -12838 && $asc <= -12557) {
            return 'W';
        }

        if ($asc >= -12556 && $asc <= -11848) {
            return 'X';
        }

        if ($asc >= -11847 && $asc <= -11056) {
            return 'Y';
        }

        if ($asc >= -11055 && $asc <= -10247) {
            return 'Z';
        }

        return null;
    }
}

