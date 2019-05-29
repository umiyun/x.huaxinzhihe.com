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

define('YOUMI_DEBUG', TRUE);
define('YOUMI_NAME', 'umiacp_leapcliff');
define('UMI_NAME', 'umi_activitys');

!defined('YOUMI_PATH') && define('YOUMI_PATH', IA_ROOT . '/addons/umiacp_leapcliff/');
!defined('YOUMI_CORE') && define('YOUMI_CORE', YOUMI_PATH . 'core/');
!defined('YOUMI_CERT') && define('YOUMI_CERT', YOUMI_PATH . 'cert/');
!defined('YOUMI_APP') && define('YOUMI_APP', YOUMI_PATH . 'app/');
!defined('YOUMI_WEB') && define('YOUMI_WEB', YOUMI_PATH . 'web/');
!defined('YOUMI_DATA') && define('YOUMI_DATA', YOUMI_PATH . 'data/');
!defined('YOUMI_CERT') && define('YOUMI_CERT', YOUMI_PATH . 'cert/');

!defined('YOUMI_URL') && define('YOUMI_URL', $_W['siteroot'] . 'addons/umiacp_leapcliff/');
!defined('YOUMI_URL_APP') && define('YOUMI_URL_APP', YOUMI_URL . 'template/mobile/');
!defined('YOUMI_URL_APP_STATIC') && define('YOUMI_URL_APP_STATIC', YOUMI_URL_APP . 'static/');
!defined('YOUMI_URL_APP_JS') && define('YOUMI_URL_APP_JS', YOUMI_URL_APP_STATIC . 'js/');
!defined('YOUMI_URL_APP_CSS') && define('YOUMI_URL_APP_CSS', YOUMI_URL_APP_STATIC . 'css/');
!defined('YOUMI_URL_APP_IMG') && define('YOUMI_URL_APP_IMG', YOUMI_URL_APP_STATIC . 'img/');
!defined('YOUMI_URL_WEB') && define('YOUMI_URL_WEB', YOUMI_URL . 'web/');
