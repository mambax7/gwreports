<?php
/**
 * install.php - initializations on module installation
 *
 * This file is part of gwreports - geekwright Reports
 *
 * @copyright  Copyright © 2011 geekwright, LLC. All rights reserved.
 * @license    gwreports/docs/license.txt  GNU General Public License (GPL)
 * @author     Richard Griffith <richard@geekwright.com>
 * @package    gwreports
 */

defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

function xoops_module_install_gwreports(&$module)
{
    // currently nothing to do
    $module->setErrors('Install Post-Process Completed');
    return true;
}
