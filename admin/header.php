<?php
/**
 * admin/header.php - preamble for all admin pages
 *
 * @copyright  Copyright © 2013 geekwright, LLC. All rights reserved.
 * @license    GNU General Public License (GPL)
 * @author     Richard Griffith <richard@geekwright.com>
 * @package    gwreports
 */

include __DIR__ . '/../../../include/cp_header.php';
include_once __DIR__ . '/../include/dbcommon.php';

include_once $GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin/moduleadmin.php');

xoops_loadLanguage('modinfo', basename(dirname(__DIR__)));
}

xoops_cp_header();

$moduleAdmin = new ModuleAdmin();
