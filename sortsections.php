<?php
/**
 * sortsections.php - change display order of report sections
 *
 * This file is part of gwreports - geekwright Reports
 *
 * @copyright  Copyright © 2011 geekwright, LLC. All rights reserved.
 * @license    gwreports/docs/license.txt  GNU General Public License (GPL)
 * @author     Richard Griffith <richard@geekwright.com>
 * @package    gwreports
 */

include __DIR__ . '/../../mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'gwreports_index.tpl';
include XOOPS_ROOT_PATH . '/header.php';
$currentscript = basename(__FILE__);
//include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
include __DIR__ . '/include/common.php';

$selectalert = _MD_GWREPORTS_SORT_SECTION_SELECT;
$sortelement = 'sortelement';
$sort_js     = <<<ENDJSCODE
function move(f,bDir) {
  var el = f.elements["$sortelement"]
  var idx = el.selectedIndex
  if (idx==-1)
    alert("$selectalert")
  else {
    var nxidx = idx+( bDir? -1 : 1)
    if (nxidx<0) return; // nxidx=el.length-1
    if (nxidx>=el.length) return; // nxidx=0
    var oldVal = el[idx].value
    var oldText = el[idx].text
    el[idx].value = el[nxidx].value
    el[idx].text = el[nxidx].text
    el[nxidx].value = oldVal
    el[nxidx].text = oldText
    el.selectedIndex = nxidx
  }
}

function reverseorder(f) {
  var el = f.elements["$sortelement"];
  var b = 0;
  var t = el.length;
  t = t-1;
  while (b<t) {
    var oldVal = el[t].value;
    var oldText = el[t].text;
    el[t].value = el[b].value;
    el[t].text = el[b].text;
    el[b].value = oldVal;
    el[b].text = oldText;
    b = b+1;
    t = t-1;
  }
}

function processForm(f) {
  for (var i=0;i<f.length;i++) {
    var el = f[i]
    // If reorder listbox, then generate value for hidden field
    if (el.name=="$sortelement") {
      var strIDs = ""
      for (var j=0;j<f[i].options.length;j++)
        strIDs += f[i].options[j].value + ","
        f.elements['neworder'].value = strIDs.substring(0,strIDs.length-1)
    }
  }
}
ENDJSCODE;

$xoTheme->addScript(null, array('type' => 'text/javascript'), $sort_js);

// leave if we don't have admin authority
if (!($xoopsUser && $xoopsUser->isAdmin())) {
    redirect_header('index.php', 3, _NOPERM);
}

$op = 'display';
if (isset($_POST['submit'])) {
    $op = 'update';
}
$report_id = 0;
if (isset($_GET['rid'])) {
    $report_id = (int)$_GET['rid'];
}
if (isset($_POST['rid'])) {
    $report_id = (int)$_POST['rid'];
}

if (!$report_id) {
    redirect_header('index.php', 3, _MD_GWREPORTS_MISSING_PARAMETER);
}

$sections = getReportSections($report_id);

// leave if there is nothing to sort
if (count($sections) < 2) {
    redirect_header("editreport.php?rid=$report_id", 3, _MD_GWREPORTS_SORT_EMPTY);
}

if ($op === 'update') {
    if (isset($_POST['neworder'])) {
        $neworder = array();
        $neworder = explode(',', $_POST['neworder']);
    } else {
        $op = 'display';
    }
}

if ($op === 'update') {
    foreach ($neworder as $i => $section) {
        if (isset($sections[$section])) {
            $sections[$section]['section_order'] = $i;
        } else {
            $op = 'display';
        }
    }
}

if ($op === 'update') {
    foreach ($sections as $i => $v) {
        $sql = 'UPDATE ' . $xoopsDB->prefix('gwreports_section');
        $sql .= ' SET section_order = ' . $v['section_order'];
        $sql .= ' WHERE section_id = ' . $v['section_id'] . ' ';
        $result = $xoopsDB->queryF($sql);
    }
    unset($sections);
    $sections = getReportSections($report_id);
    $op       = 'display';
}

$token = 0;

$caption = _MD_GWREPORTS_SORT_SECTION_FORM;
$form    = new XoopsThemeForm($caption, 'form1', '', 'POST', $token);

$caption    = _MD_GWREPORTS_SORT_ACTIONS;
$buttontray = new XoopsFormElementTray($caption, '');

$button_moveup = new XoopsFormButton('', 'moveup', _MD_GWREPORTS_SORT_UP, 'button');
$button_moveup->setExtra('onClick="move(this.form,true)" ');
$buttontray->addElement($button_moveup);

$button_movedown = new XoopsFormButton('', 'movedown', _MD_GWREPORTS_SORT_DOWN, 'button');
$button_movedown->setExtra('onClick="move(this.form,false)" ');
$buttontray->addElement($button_movedown);

$button_reverse = new XoopsFormButton('', 'reverse', _MD_GWREPORTS_SORT_REVERSE, 'button');
$button_reverse->setExtra('onClick="reverseorder(this.form)" ');
$buttontray->addElement($button_reverse);

$button_submit = new XoopsFormButton('', 'submit', _MD_GWREPORTS_SORT_SAVE, 'submit');
$button_submit->setExtra('onClick="processForm(this.form)" ');
$buttontray->addElement($button_submit);

$form->addElement($buttontray);

// XoopsFormSelect( string $caption, string $name, [mixed $value = null], [int $size = 1], [bool $multiple = false])
$listbox = new XoopsFormSelect(_MD_GWREPORTS_SORT_SECTIONS, 'sortelement', null, count($sections), false);
foreach ($sections as $i => $v) {
    $listbox->addOption($i, $v['section_name']);
}
$form->addElement($listbox);

$form->addElement($buttontray);

$form->addElement(new XoopsFormHidden('neworder', ''));
$body = $form->render();

//$dirname=$xoopsModule->getInfo('dirname');
$body .= '<br /><a href="admin/index.php">' . _MD_GWREPORTS_ADMIN_MENU . '</a>';
$body .= ' | <a href="admin/reports.php">' . _MD_GWREPORTS_ADMIN_REPORT . '</a>';
$body .= ' | <a href="admin/topics.php">' . _MD_GWREPORTS_ADMIN_TOPIC . '</a>';
$body .= " | <a href=\"editreport.php?rid=$report_id\">" . _MD_GWREPORTS_EDITREPORT_FORM . '</a>';

//$debug='<pre>$_POST='.print_r($_POST,true).'</pre>';
//$debug.='<pre>$places='.print_r($places,true).'</pre>';
//if(isset($neworder)) $debug.='<pre>$neworder='.print_r($neworder,true).'</pre>';
//$debug.='<pre>$topics='.print_r($topics,true).'</pre>';

setPageTitle(_MD_GWREPORTS_SORT_SECTION_FORM);

if (isset($body)) {
    $xoopsTpl->assign('body', $body);
}

if (isset($places['choose'])) {
    $xoopsTpl->assign('choose', $places['choose']);
}
if (isset($places['crumbs'])) {
    $xoopsTpl->assign('crumbs', $places['crumbs']);
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
