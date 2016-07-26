<?php
defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

define('_MB_GWREPORTS_BLOCK_TOPIC_ID', 'Sujet');

define('_MB_GWREPORTS_BLOCK_QUICK_REPORT_ID', 'Rapport');
define('_MB_GWREPORTS_REPORT_RUN_BUTTON', 'Ex&eacute;cute');
define('_MB_GWREPORTS_SECTION_EMPTY', '(Aucune donn&eacute;e)');
if (!defined('_MD_GWREPORTS_TITLE')) { // if main isn't loaded, do it
    require_once __DIR__ . '/main.php';
}
