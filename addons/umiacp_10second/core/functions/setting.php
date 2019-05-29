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
 * 系统配置参数列表
 * @return array
 */
if(!function_exists('youmi_setting_get_list')) {
    function youmi_setting_get_list()
    {

        global $_W, $_GPC;
        $uniacid = intval($_W['uniacid']);
        $setting = array();

        $cachekey = YOUMI_NAME . '_' . $uniacid . '_settings';
        $cache = cache_load($cachekey);
        if (!empty($cache) && !empty($cache['settings']) && $cache['expire'] > TIMESTAMP) {
            return $cache['settings'];
        }

        $settings = pdo_getall(YOUMI_NAME . '_' . 'setting', array('uniacid' => $uniacid));
        foreach ($settings as $key => &$value) {
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
                    $setting[$k] = $v;
                }
            }
        }

        $record = array();
        $record['settings'] = $setting;
        $record['expire'] = TIMESTAMP + 2 * 60 * 60;
        cache_write($cachekey, $record);
        return $setting;
    }
}

/**
 * 传入单项系统参数  获取值
 * @param string $skey
 * @return bool|mixed
 */
if(!function_exists('youmi_setting_get_by_skey')) {
    function youmi_setting_get_by_skey($skey = '')
    {

        global $_W, $_GPC;
        $uniacid = intval($_W['uniacid']);

        $setting = pdo_get(YOUMI_NAME . '_' . 'setting', array('uniacid' => $uniacid, 'skey' => $skey));
        if ($setting) {
            $set = unserialize($setting['svalue']);
            return $setting;
        } else {
            return FALSE;
        }
    }
}

/**
 * 传入参数键值  判断是否存在 存在更新  不存在新增
 * @param $skey
 * @param $data
 * @return bool
 */
if(!function_exists('youmi_setting_save')) {
    function youmi_setting_save($skey, $data)
    {
        global $_W, $_GPC;
        $uniacid = intval($_W['uniacid']);
        if (empty($skey)) {
            return FALSE;
        }

        $record = array();
        $record['svalue'] = serialize($data);
        $exists = youmi_setting_get_by_skey($skey);
        if ($exists) {
            $return = pdo_update(YOUMI_NAME . '_' . 'setting', $record, array('uniacid' => $uniacid, 'skey' => $skey));
        } else {
            $record['skey'] = $skey;
            $record['uniacid'] = $uniacid;
            $record['createtime'] = TIMESTAMP;
            $return = pdo_insert(YOUMI_NAME . '_' . 'setting', $record);
        }

        $cachekey = YOUMI_NAME . '_' . $uniacid . '_settings';
        cache_delete($cachekey);
        return $return;
    }
}

