<?php
/**
 * newsection.php - add new report section
 *
 * This file is part of gwreports - geekwright Reports
 *
 * @copyright  Copyright © 2011-2013 geekwright, LLC. All rights reserved.
 * @license    gwreports/docs/license.txt  GNU General Public License (GPL)
 * @author     Richard Griffith <richard@geekwright.com>
 * @package    gwreports
 */

include __DIR__ . '/../../mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'gwreports_sectionedit.html';
include XOOPS_ROOT_PATH . '/header.php';

include __DIR__ . '/include/common.php';

if (!($xoopsUser && $xoopsUser->isAdmin())) {
    redirect_header('index.php', 3, _NOPERM);
}

$op = 'display';
if (isset($_POST['submit'])) {
    $op = 'add';
}

$report_id                = 0;
$report_name              = '';
$this_report_needs_jquery = false;

if (isset($_GET['rid'])) {
    $report_id = (int)$_GET['rid'];
}
if (isset($_POST['rid'])) {
    $report_id = (int)$_POST['rid'];
}
$report_definition = getReport($report_id);
if (isset($report_definition['report_name'])) {
    $report_name = $report_definition['report_name'];
} else {
    redirect_header('index.php', 3, _MD_GWREPORTS_REPORT_NOTFOUND);
}
$xoopsTpl->assign('report_id', $report_id);

$parameters = getReportParameters($report_id);
foreach ($parameters as $p) {
    if ($p['parameter_type'] === 'autocomplete') {
        $this_report_needs_jquery = true;
    }
}
$xoopsTpl->assign('report_parameters', $parameters);
$sections = getReportSections($report_id);
$xoopsTpl->assign('report_sections', $sections);
$report_parameter_form = getParameterForm($report_id, $parameters, $editor = true);
$xoopsTpl->assign('report_parameter_form', $report_parameter_form);

$section_name        = '';
$section_description = '';
$section_showtitle   = 0;
$section_multirow    = 1;
$section_skipempty   = 0;
$section_datatools   = 0;
$section_query       = '';

if (isset($_POST['section_name'])) {
    $section_name = cleaner($_POST['section_name']);
}
if (isset($_POST['section_description'])) {
    $section_description = cleaner($_POST['section_description']);
}
if (isset($_POST['section_query'])) {
    $section_query = cleaner($_POST['section_query'], true);
}
if (isset($_POST['section_multirow'])) {
    $section_multirow = cleaneryn($_POST['section_multirow']);
}
if (isset($_POST['section_showtitle'])) {
    $section_showtitle = cleaneryn($_POST['section_showtitle']);
}
if (isset($_POST['section_skipempty'])) {
    $section_skipempty = cleaneryn($_POST['section_skipempty']);
}
if (isset($_POST['section_datatools'])) {
    $section_datatools = cleaneryn($_POST['section_datatools']);
}

if ($op !== 'display') {
    $check = $GLOBALS['xoopsSecurity']->check();

    if (!$check) {
        $op          = 'display';
        $err_message = _MD_GWREPORTS_MSG_BAD_TOKEN;
    }
}

if ($op === 'add') {
    $sl_section_name        = dbescape($section_name);
    $sl_section_description = dbescape($section_description);
    $sl_section_query       = dbescape($section_query);

    $dberr = false;
    $dbmsg = '';

    $sql = 'INSERT INTO ' . $xoopsDB->prefix('gwreports_section');
    $sql .= ' (report, section_name, section_description, section_showtitle, section_multirow, section_skipempty, section_datatools, section_query) ';
    $sql .= " VALUES ( $report_id, '$sl_section_name', '$sl_section_description', $section_showtitle, $section_multirow, $section_skipempty, $section_datatools, '$sl_section_query') ";

    $result = $xoopsDB->queryF($sql);
    if (!$result) {
        $dberr = true;
        $dbmsg = formatDBError();
    }

    if (!$dberr) {
        $new_section_id = $xoopsDB->getInsertId();
        $message        = _MD_GWREPORTS_SECTION_ADD_OK;
        redirect_header("editsection.php?sid=$new_section_id", 3, $message);
    } else {
        $err_message = _MD_GWREPORTS_SECTION_ADD_ERR . ' ' . $dbmsg;
    }
}

$body      = '';
$token     = true;
$formtitle = _MD_GWREPORTS_NEWSECTION_FORM;
$form      = new XoopsThemeForm($formtitle, 'form1', 'newsection.php', 'POST', $token);

$caption = _MD_GWREPORTS_SECTION_REPORT_NAME;
$form->addElement(new XoopsFormLabel($caption, '<a href="editreport.php?rid=' . $report_id . '">' . $report_name . '</a>', 'cplan_name'), false);

$caption = _MD_GWREPORTS_SECTION_NAME;
$form->addElement(new XoopsFormText($caption, 'section_name', 40, 250, htmlspecialchars($section_name, ENT_QUOTES)), true);

$caption = _MD_GWREPORTS_SECTION_QUERY;
$form->addElement(new XoopsFormTextArea($caption, 'section_query', $section_query, 10, 50, 'section_query'), true);

$caption = _MD_GWREPORTS_SECTION_SHOWTITLE;
$form->addElement(new XoopsFormRadioYN($caption, 'section_showtitle', $section_showtitle), true);

$caption = _MD_GWREPORTS_SECTION_MULTIROW;
$form->addElement(new XoopsFormRadioYN($caption, 'section_multirow', $section_multirow), true);

$caption = _MD_GWREPORTS_SECTION_SKIPEMPTY;
$form->addElement(new XoopsFormRadioYN($caption, 'section_skipempty', $section_skipempty), true);

$caption = _MD_GWREPORTS_SECTION_DATATOOLS;
$form->addElement(new XoopsFormRadioYN($caption, 'section_datatools', $section_datatools), true);

$caption = _MD_GWREPORTS_SECTION_DESC;
$form->addElement(new XoopsFormTextArea($caption, 'section_description', $section_description, 4, 50, 'section_description'), false);

$form->addElement(new XoopsFormButton(_MD_GWREPORTS_NEWSECTION_ADD_BUTTON_DSC, 'submit', _MD_GWREPORTS_NEWSECTION_ADD_BUTTON, 'submit'));

$form->addElement(new XoopsFormHidden('rid', $report_id));

//$form->display();
$body = $form->render();

//$dirname=$xoopsModule->getInfo('dirname');
$body .= '<br /><a href="admin/index.php">' . _MD_GWREPORTS_ADMIN_MENU . '</a>';
$body .= ' | <a href="admin/reports.php">' . _MD_GWREPORTS_ADMIN_REPORT . '</a>';
$body .= ' | <a href="admin/topics.php">' . _MD_GWREPORTS_ADMIN_TOPIC . '</a>';

//$debug='<pre>$_POST='.print_r($_POST,true).'</pre>';

setPageTitle(_MD_GWREPORTS_NEWSECTION_FORM);
$xoopsTpl->assign('needjquery', $this_report_needs_jquery);
if (isset($body)) {
    $xoopsTpl->assign('body', $body);
}

if (isset($message)) {
    $xoopsTpl->assign('message', $message);
}
if (isset($err_message)) {
    $xoopsTpl->assign('err_message', $err_message);
}
if (isset($debug)) {
    $xoopsTpl->assign('debug', $debug);
}

include XOOPS_ROOT_PATH . '/footer.php';
