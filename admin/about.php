<?php
/**
 * admin/about.php
 *
 * @copyright  Copyright © 2013 geekwright, LLC. All rights reserved.
 * @license    gwreports/docs/license.txt  GNU General Public License (GPL)
 * @author     Richard Griffith <richard@geekwright.com>
 * @package    gwreports
 */

include __DIR__ . '/header.php';
echo $moduleAdmin->addNavigation(basename(__FILE__));
echo $moduleAdmin->renderAbout('', false);

include __DIR__ . '/footer.php';
