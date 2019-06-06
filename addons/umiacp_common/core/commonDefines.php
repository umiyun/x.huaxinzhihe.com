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

define('YOUMI_COMMON_NAME', 'umiacp_common');

define('YOUMI_10SECOND_NAME', 'umiacp_10second');
define('YOUMI_APPLY_NAME', 'umiacp_apply');
define('YOUMI_BARGAIN_NAME', 'umiacp_bargain');
define('YOUMI_BARGAIN_SIMPLE_NAME', 'umiacp_bargainsimple');
define('YOUMI_BARGAIN_SIMPLE2_NAME', 'umiacp_bargainsimple2');
define('YOUMI_EGGFRENY_NAME', 'umiacp_eggfreny');
define('YOUMI_FISSION_NAME', 'umiacp_fission');
define('YOUMI_GROUP_SIMPLE_NAME', 'umiacp_groupprepay');
define('YOUMI_GROUPPREPAY_NAME', 'umiacp_groupsimple');
define('YOUMI_LEAPCLIFF_NAME', 'umiacp_leapcliff');
define('YOUMI_LIGHTEN_NAME', 'umiacp_lighten');
define('YOUMI_ROULETTE_NAME', 'umiacp_roulette');
define('YOUMI_SPEEDDIAL_NAME', 'umiacp_speeddial');
define('YOUMI_VOTE_NAME', 'umiacp_vote');


!defined('YOUMI_COMMON_PATH') && define('YOUMI_COMMON_PATH', IA_ROOT . '/addons/umiacp_common/');
!defined('YOUMI_COMMON_CORE') && define('YOUMI_COMMON_CORE', YOUMI_COMMON_PATH . 'core/');
!defined('YOUMI_COMMON_FUNCTIONS') && define('YOUMI_COMMON_FUNCTIONS', YOUMI_COMMON_CORE . 'functions/');
!defined('YOUMI_COMMON_CERT') && define('YOUMI_COMMON_CERT', YOUMI_COMMON_PATH . 'cert/');
!defined('YOUMI_COMMON_APP') && define('YOUMI_COMMON_APP', YOUMI_COMMON_PATH . 'app/');
!defined('YOUMI_COMMON_WEB') && define('YOUMI_COMMON_WEB', YOUMI_COMMON_PATH . 'web/');
!defined('YOUMI_COMMON_DATA') && define('YOUMI_COMMON_DATA', YOUMI_COMMON_PATH . 'data/');



