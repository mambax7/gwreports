<?php
/**
 * index.php - admin page for about and configuration messages
 *
 * This file is part of gwreports - geekwright Reports
 *
 * @copyright  Copyright © 2011-2013 geekwright, LLC. All rights reserved.
 * @license    gwreports/docs/license.txt  GNU General Public License (GPL)
 * @author     Richard Griffith <richard@geekwright.com>
 * @package    gwreports
 */

include __DIR__ . '/header.php';

echo $moduleAdmin->addNavigation(basename(__FILE__));
$welcome = _AD_GW_ADMENU_WELCOME;
$moduleAdmin->addInfoBox($welcome);
$moduleAdmin->addInfoBoxLine($welcome, _AD_GW_ADMENU_MESSAGE, '', '', 'information');

// build todo list
$todo    = array();
$todocnt = 0;

$op = '';

if (defined('_MI_GWREPORTS_AD_LIMITED')) {
    $pathname = XOOPS_TRUST_PATH . '/modules/gwreports/import/';
    $moduleAdmin->addConfigBoxLine('<img src="../images/admin/warn.png" alt="!" />' . _AD_GWREPORTS_LIMITED_MODE, 'default');
    $moduleAdmin->addConfigBoxLine($pathname, 'folder');

}

// check mysql version
$mysqlversion_required = '4.1.0';

$sql    = 'select version()';
$result = $xoopsDB->queryF($sql);
if ($result) {
    while ($myrow = $xoopsDB->fetchRow($result)) {
        $mysqlversion = $myrow[0];
    }
    if (version_compare($mysqlversion, $mysqlversion_required) < 0) {
        $message = sprintf(_AD_GWREPORTS_AD_TODO_MYSQL, $mysqlversion_required, $mysqlversion);
        $moduleAdmin->addConfigBoxLine('<span style="color:orange"><img src="../images/admin/warn.png" alt="!" />' . $message . '</span>', 'default');
    }
}

// check for InnoDB support in mysql. We should have bombed out in install, but ...

$have_innodb = false;

$sql    = 'show ENGINES';
$result = $xoopsDB->queryF($sql);
if ($result) {
    while ($myrow = $xoopsDB->fetchArray($result)) {
        if ($myrow['Engine'] === 'InnoDB' && ($myrow['Support'] === 'YES' || $myrow['Support'] === 'DEFAULT')) {
            $have_innodb = true;
        }
    }
}
if (!$have_innodb) {
    $message = _AD_GWREPORTS_AD_TODO_INNODB;
    $moduleAdmin->addConfigBoxLine('<span style="color:orange"><img src="../images/admin/warn.png" alt="!" />' . $message . '</span>', 'default');
}

// we don't have any todo checks for gwreports yet
if (false) {
    ++$todocnt;
    $todo[$todocnt]['link']     = 'script.php';
    $todo[$todocnt]['linktext'] = 'Try to fix';
    $todo[$todocnt]['msg']      = 'Something is wrong';
}

// display todo list
echo $moduleAdmin->renderIndex();

// about section
include __DIR__ . '/footer.php';
