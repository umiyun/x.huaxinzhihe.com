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
 * 数据日志
 * @param $path     路径
 * @param $args     数据
 * @param $uniacid  公众号
 */
if(!function_exists('youmi_internal_log')) {
    function youmi_internal_log($path, $args, $uniacid)
    {

        global $_W;
        if (!$uniacid) {
            $uniacid = intval($_W['uniacid']);
        }
        //TODO 创建日志
        $path = IA_ROOT . '/addons/' . YOUMI_NAME . '/data/log/' . $uniacid . '/' . $path . '/';
        //首先判断目录存在否
        if (!is_dir($path)) {
            //第3个参数“true”意思是能创建多级目录，iconv防止中文目录乱码
            $res = mkdir(iconv('UTF-8', 'GBK', $path), 0777, true);
        }
        $date = date('Y-m-d', TIMESTAMP);
        file_put_contents($path . $date . '.log', var_export(
                array(
                    'ip' => CLIENT_IP,
                    'uniacid' => $uniacid,
                    'args' => $args, //传入值
                    'time' => date('Y-m-d H:i:s', TIMESTAMP)
                ),
                true) . PHP_EOL, FILE_APPEND);

    }
}

