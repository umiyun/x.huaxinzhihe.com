<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

define('MC_DEBUG', TRUE);
define('MC_NAME', 'weddingh5');

!defined('MC_PATH') && define('MC_PATH', IA_ROOT . '/addons/weddingh5/');
!defined('MC_CORE') && define('MC_CORE', MC_PATH . 'core/');
!defined('MC_APP') && define('MC_APP', MC_PATH . 'app/');
!defined('MC_WEB') && define('MC_WEB', MC_PATH . 'web/');
!defined('MC_DATA') && define('MC_DATA', MC_PATH . 'data/');
!defined('MC_CERT') && define('MC_CERT', MC_PATH . 'cert/');

!defined('MC_URL') && define('MC_URL', $_W['siteroot'] . 'addons/weddingh5/');
!defined('MC_URL_APP') && define('MC_URL_APP', MC_URL . 'template/mobile/');
!defined('MC_URL_APP_STATIC') && define('MC_URL_APP_STATIC', MC_URL_APP . 'static/');
!defined('MC_URL_WEB') && define('MC_URL_WEB', MC_URL . 'web/');
