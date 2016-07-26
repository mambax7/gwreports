<?php
/**
 * newreport.php - stub for limited mode
 *
 * This file is part of gwreports - geekwright Reports
 *
 * @copyright  Copyright © 2011 geekwright, LLC. All rights reserved.
 * @license    gwreports/docs/license.txt  GNU General Public License (GPL)
 * @author     Richard Griffith <richard@geekwright.com>
 * @package    gwreports
 */

include __DIR__ . '/../../mainfile.php';
redirect_header('admin/reports.php', 3, _MD_GWREPORTS_DISABLED);
exit;
