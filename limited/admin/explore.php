<?php
/**
 * explore.php - disabled stub for limited mode
 *
 * This file is part of gwreports - geekwright Reports
 *
 * @copyright  Copyright © 2011-2013 geekwright, LLC. All rights reserved.
 * @license    gwreports/docs/license.txt  GNU General Public License (GPL)
 * @author     Richard Griffith <richard@geekwright.com>
 * @package    gwreports
 */

include __DIR__ . '/../../../include/cp_header.php';
redirect_header('index.php', 3, _AD_GWREPORTS_DISABLED);
exit;
