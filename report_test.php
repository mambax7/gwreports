<?php
/**
 * report_test.php - test a report
 *
 * This file is part of gwreports - geekwright Reports
 *
 * @copyright  Copyright © 2011-2013 geekwright, LLC. All rights reserved.
 * @license    gwreports/docs/license.txt  GNU General Public License (GPL)
 * @author     Richard Griffith <richard@geekwright.com>
 * @package    gwreports
 */

include __DIR__ . '/../../mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'gwreports_reportview.html';
include XOOPS_ROOT_PATH . '/header.php';

include __DIR__ . '/include/common.php';

if (!($xoopsUser && $xoopsUser->isAdmin())) {
    redirect_header('index.php', 3, _NOPERM);
}

// globals that functions depend on
$section_id = 0;          // used for 'test' mode editing

function emitColumnHeader($column_name, &$columns, $multirow = 1)
{
    // return a column header using column definitions
    // this version is for editor testing and builds links to column editor
    global $section_id;

    $link = $column_name;

    if (isset($columns[$column_name])) { // have a definition
        if ($columns[$column_name]['column_hide']) {
            return '';
        }
        $baseurl = 'editcolumn.php?cid=' . $columns[$column_name]['column_id'];
        $title   = $columns[$column_name]['column_title'];
        if ($title == '') {
            $title = $column_name;
        }
        $link = '<a href="' . $baseurl . '">' . $title . '</a>';
    } else {
        $baseurl = 'newcolumn.php?sid=' . $section_id . '&cname=' . $column_name;
        $link    = '<a href="' . $baseurl . '">' . $column_name . '</a>';
    }
    if ($multirow) {
        $header = "<th>$link</th>";
    } else {
        $header = '<td class="head">' . $link . '</td>';
    }
    return $header;
}

function emitColumn($column_name, &$columns, $row, $breaktriggered, $rowheaders)
{
    // condition column display using column definitions

    $r   = $row[$column_name];
    $css = '';
    if (isset($columns[$column_name])) { // have a definition
        if ($columns[$column_name]['column_style'] != '') {
            $css = ' ' . $columns[$column_name]['column_style'];
        }

        if ($columns[$column_name]['column_break']) {
            if ($breaktriggered) {
                $columns[$column_name]['last_column_break'] = $r;
            }
        }

        if ($columns[$column_name]['column_outline']) {
            if ($r == $columns[$column_name]['last_column_outline'] && !$breaktriggered) {
                $r = '';
            } else {
                $columns[$column_name]['last_column_outline'] = $r;
            }
        }

        if ($columns[$column_name]['column_sum']) {
            $columns[$column_name]['total_column_sum'] += $r;
            $columns[$column_name]['sum_column_break'] += $r;
        }

        $r = htmlspecialchars($r);

        if ($columns[$column_name]['column_apply_nl2br']) {
            $r = nl2br($r);
        }

        if ($columns[$column_name]['column_is_unixtime']) {
            if ($columns[$column_name]['column_format'] != '') {
                $r = date($columns[$column_name]['column_format'], $r);
            } else {
                $r = formatTimestamp($r);
            }
        } else { // not unixtime
            if ($columns[$column_name]['column_format'] != '') {
                $r = sprintf($columns[$column_name]['column_format'], $r);
            }
        }

        if ($columns[$column_name]['column_extended_format'] != '') {
            $row[$column_name] = $r; // preserve any formatting we have so far
            $r                 = str_replace($rowheaders, $row, $columns[$column_name]['column_extended_format']);
            $r                 = str_replace('{$xurl}', XOOPS_URL, $r);
        }

        if ($columns[$column_name]['column_hide']) {
            return '';
        }
    }
    $r = "<td$css>$r</td>";
    return $r;
}

function checkBreak(&$columns, $row)
{
    // check row for any column change break
    $breaktriggered = false;

    foreach ($row as $col => $val) {
        if (isset($columns[$col])) { // have a definition
            if ($columns[$col]['column_break']) {
                if ($val != $columns[$col]['last_column_break']) {
                    $breaktriggered = true;
                }
            }
        }
    }
    return $breaktriggered;
}

function emitBreak(&$columns, $rowheaders)
{
    // output columns for a column change break
    $sumline   = '';
    $havesum   = false;
    $havebreak = false;

    foreach ($rowheaders as $col => $val) {
        $r   = '';
        $css = '';
        if (isset($columns[$col])) { // have a definition
            if ($columns[$col]['column_break']) {
                $havebreak = true;
            }
            if (!$columns[$col]['column_hide']) {
                if ($columns[$col]['column_style'] != '') {
                    $css = ' ' . $columns[$col]['column_style'];
                }
                if ($columns[$col]['column_sum']) {
                    $havesum                           = true;
                    $r                                 = $columns[$col]['sum_column_break'] . '';
                    $columns[$col]['sum_column_break'] = 0;   // used it, now reset
                    if ($columns[$col]['column_format'] != '') {
                        $r = sprintf($columns[$col]['column_format'], $r);
                    }
                    $r = '<hr /><br />' . $r;
                } else { // not sum
                    $r = '&nbsp;';
                }
            }
        } else { // no definition
            $r = ' ';
        }
        if ($r != '') {
            $sumline .= "<td$css>$r</td>";
        }
    }

    if ($havebreak) {
        return $sumline;
    } else {
        return '';
    }
}

function emitFinalSummary(&$columns, $rowheaders)
{
    // output columns for final summary row
    $sumline = '';
    $havesum = false;

    foreach ($rowheaders as $col => $val) {
        $r   = '';
        $css = '';
        if (isset($columns[$col])) { // have a definition
            if (!$columns[$col]['column_hide']) {
                if ($columns[$col]['column_style'] != '') {
                    $css = ' ' . $columns[$col]['column_style'];
                }
                if ($columns[$col]['column_sum']) {
                    $havesum = true;
                    $r       = $columns[$col]['total_column_sum'] . ''; // coerce to string
                    if ($columns[$col]['column_format'] != '') {
                        $r = sprintf($columns[$col]['column_format'], $r);
                    }
                    $r = '<hr /><br />' . $r;
                } else { // not sum
                    $r = ' ';
                }
            }
        } else { // no definition
            $r = ' ';
        }
        if ($r != '') {
            $sumline .= "<td$css>$r</td>";
        }
    }

    if ($havesum) {
        return $sumline;
    } else {
        return '';
    }
}

function initializeColumns(&$columns, $row, &$rowheaders)
{
    // initialize columns and return count of columns
    $cnt = 0;

    $rowheaders = array();

    foreach ($row as $col => $val) {
        $rowheaders[$col] = '{' . $col . '}';

        if (isset($columns[$col])) { // have a definition
            if (!$columns[$col]['column_hide']) {
                ++$cnt;
            }
            if ($columns[$col]['column_sum']) {
                $columns[$col]['total_column_sum'] = 0;
                $columns[$col]['sum_column_break'] = 0;
            }
            if ($columns[$col]['column_break']) {
                $columns[$col]['last_column_break'] = $val; // preset with first row
            }
            if ($columns[$col]['column_outline']) {
                $columns[$col]['last_column_outline'] = '';
            }
        } else {
            ++$cnt;
        }
    }

    return $cnt;
}

$op = 'display';
if (isset($_POST['submit'])) {
    $op = 'run';
}

$report_id          = 0;
$report_name        = '';
$report_description = '';
$report_active      = 0;
$access_groups      = array();
$report_topic       = 0;

$this_report_needs_jquery = false;

if (isset($_GET['rid'])) {
    $report_id = (int)$_GET['rid'];
}
if (isset($_POST['rid'])) {
    $report_id = (int)$_POST['rid'];
}

// get data from table

$sql = 'SELECT * FROM ' . $xoopsDB->prefix('gwreports_report');
$sql .= " WHERE report_id = $report_id ";

$cnt    = 0;
$result = $xoopsDB->query($sql);
if ($result) {
    while ($myrow = $xoopsDB->fetchArray($result)) {
        $report_name        = $myrow['report_name'];
        $report_description = $myrow['report_description'];
        $report_active      = $myrow['report_active'];
        ++$cnt;
    }
    if ($cnt) {
        $xoopsTpl->assign('report_id', $report_id);
        $access_groups = getReportAccess($report_id);
        $report_topic  = getReportTopic($report_id);
        $sections      = getReportSections($report_id);
        $xoopsTpl->assign('report_sections', $sections);
    } else {
        $err_message = _MD_GWREPORTS_REPORT_NOTFOUND;
        $report_id   = 0;
    }
} else {
    $err_message = _MD_GWREPORTS_REPORT_NOTFOUND;
    $report_id   = 0;
}

setPageTitle($report_name);

// get parameters
$parameters = getReportParameters($report_id);
//  $xoopsTpl->assign('report_parameters', $parameters);

foreach ($parameters as $v) {
    $parm_length   = $v['parameter_length'];
    $parm_required = $v['parameter_required'];
    $parm_name     = 'rptparm_' . $v['parameter_name'];
    $parm_type     = $v['parameter_type'];
    $parm_value    = $v['parameter_default'];
    if (isset($_POST[$parm_name])) {
        $parm_value = cleaner($_POST[$parm_name]);
    }
    switch ($parm_type) {
        case 'int':
            $parm_value = (int)$parm_value;
            break;
        case 'yesno':
            $parm_value = (int)$parm_value;
            if ($parm_value) {
                $parm_value = '1';
            } else {
                $parm_value = '0';
            }
            break;
        case 'autocomplete':
            $this_report_needs_jquery = true;
        // fall through
        default: // text, liketext, date
            $parm_value = clipstring($parm_value, $parm_length);
            break;
    }
    $parameters[$v['parameter_id']]['value'] = $parm_value;
}
// build form
$report_parameter_form = getParameterForm($report_id, $parameters, $editor = true);
$xoopsTpl->assign('report_parameter_form', $report_parameter_form);

if (count($parameters) == 0) {
    $op = 'run';
}

if ($op === 'display') {
    if ($report_description != '') {
        $xoopsTpl->assign('page_message', $report_description);
    }
}

$body = '';

if ($op === 'run') {
    $parmtags = array();
    $parmsubs = array();

    $pc            = 0;
    $parmtags[$pc] = '{$xpfx}';
    $parmsubs[$pc] = $xoopsDB->prefix('') . '_';
    ++$pc;
    $parmtags[$pc] = '{$xuid}';
    $parmsubs[$pc] = ($xoopsUser ? $xoopsUser->getVar('uid') : 0);
    foreach ($parameters as $v) {
        ++$pc;
        $parmtags[$pc] = '{' . $v['parameter_name'] . '}';
        $parm_type     = $v['parameter_type'];
        // apply any preconditioning
        //  'text','liketext','date','datetime','integer','yesno'
        switch ($parm_type) {
            case 'liketext':
                $parmsubs[$pc] = dbescape('%' . $v['value'] . '%');
                break;
            case 'date':
                $parmsubs[$pc] = strtotime($v['value']);
                break;
            case 'integer':
                $parmsubs[$pc] = (int)$v['value'];
                break;
            case 'decimal':
                $dp            = $v['parameter_decimals'];
                $dp            = sprintf('%01d', $dp);
                $parmsubs[$pc] = sprintf('%01.' . $dp . 'f', round($v['value'], $dp));
                break;
            case 'yesno':
                $parmsubs[$pc] = (int)$v['value'];
                break;
            default:
                $parmsubs[$pc] = dbescape($v['value']);
                break;
        }
    }

    foreach ($sections as $s) {
        $section_id          = $s['section_id'];
        $section_name        = $s['section_name'];
        $section_description = $s['section_description'];
        $section_order       = $s['section_order'];
        $section_showtitle   = $s['section_showtitle'];
        $section_multirow    = $s['section_multirow'];
        $section_skipempty   = $s['section_skipempty'];
        $section_datatools   = $s['section_datatools'];
        $section_query       = $s['section_query'];

        if ($section_datatools) {
            $this_report_needs_jquery = true;
        }

        unset($columns);
        $columns = getColumns($section_id);

        $rowclass = 'even';

        $sql    = str_replace($parmtags, $parmsubs, $section_query);
        $result = $xoopsDB->query($sql);
        $dbmsg  = '';
        if ($xoopsDB->errno()) {
            $dbmsg = formatDBError();
        }
        if ($result) {
            if ($myrow = $xoopsDB->fetchArray($result)) {
                $rowheaders = array();
                $colcount   = initializeColumns($columns, $myrow, $rowheaders);
                if ($section_multirow) {
                    $datatool_class = '';
                    if ($section_datatools) {
                        $datatool_class = ' class="dataTable" ';
                    }
                    $body .= '<table' . $datatool_class . '><thead><tr>';
                    if ($section_showtitle) {
                        $colspan = $colcount;
                        $body .= "<th colspan=$colspan>$section_name</th></tr><tr>";
                    }
                    foreach ($myrow as $col => $row) {
                        $body .= emitColumnHeader($col, $columns, $section_multirow);
                    }
                    $body .= '</tr></thead><tbody>';
                    while ($myrow) {
                        $breaktriggered = checkBreak($columns, $myrow);
                        if ($breaktriggered) {
                            $trcontent = emitBreak($columns, $rowheaders);
                            if ($trcontent != '') {
                                $body .= '<tr class="sumbreak">';
                                $body .= $trcontent . '</tr>';
                            }
                        }
                        if ($rowclass === 'even') {
                            $rowclass = 'odd';
                        } else {
                            $rowclass = 'even';
                        }
                        $body .= '<tr class="' . $rowclass . '">';
                        foreach ($myrow as $col => $row) {
                            $body .= emitColumn($col, $columns, $myrow, $breaktriggered, $rowheaders);
                        }
                        $body .= '</tr>';

                        $myrow = $xoopsDB->fetchArray($result);
                    }

                    // automatic break at end
                    $trcontent = emitBreak($columns, $rowheaders);
                    if ($trcontent != '') {
                        $body .= '<tr class="sumbreak">';
                        $body .= $trcontent . '</tr>';
                    }

                    // final summary
                    $trcontent = emitFinalSummary($columns, $rowheaders);
                    if ($trcontent != '') {
                        $body .= '<tr class="sumtotal">';
                        $body .= $trcontent . '</tr>';
                    }
                } else { // $section_multirow is false
                    $body .= '<table><tbody>';
                    if ($section_showtitle) {
                        $body .= "<tr><th colspan=\"2\">$section_name</th></tr>";
                    }
                    while ($myrow) {
                        $breaktriggered = false;
                        $breaktriggered = checkBreak($columns, $myrow);
                        if ($breaktriggered) {
                            $body .= '<tr class="sumbreak"><td>&nbsp;</td><td></td></tr>';
                        }
                        foreach ($myrow as $col => $row) {
                            $trcontent = emitColumnHeader($col, $columns, $section_multirow);
                            $trcontent .= emitColumn($col, $columns, $myrow, $breaktriggered, $rowheaders);
                            if ($trcontent != '') {
                                $body .= '<tr>' . $trcontent . '</tr>';
                            }
                        }
                        $myrow = $xoopsDB->fetchArray($result);
                    }
                }

                $body .= '</tbody></table><br />';
            } else { // else no data for section
                if (!$section_skipempty) {
                    $body .= '<table><tr>';
                    if ($section_showtitle) {
                        $body .= "<th>$section_name</th></tr><tr>";
                    }
                    $body .= '<td><em>' . _MD_GWREPORTS_SECTION_EMPTY . '</em></th></tr></table><br />';
                }
            }
        }
        //      if($dbmsg!='') $body.='<br /><em>'.sprintf(_MD_GWREPORTS_RUNTIME_SQL_ERROR,$dbmsg).'</em><br />';
        if ($dbmsg != '') {
            $body .= '<br /><em>' . $dbmsg . '</em><br />';
        }
    } // foreach ($sections as $s)
}

//$dirname=$xoopsModule->getInfo('dirname');
$body .= '<br /><a href="admin/index.php">' . _MD_GWREPORTS_ADMIN_MENU . '</a>';
$body .= ' | <a href="admin/reports.php">' . _MD_GWREPORTS_ADMIN_REPORT . '</a>';
$body .= ' | <a href="admin/topics.php">' . _MD_GWREPORTS_ADMIN_TOPIC . '</a>';
$body .= " | <a href=\"editreport.php?rid=$report_id\">" . _MD_GWREPORTS_EDITREPORT_FORM . '</a>';

//$debug='<pre>$_POST='.print_r($_POST,true).'</pre>';
//if(isset($parameters)) $debug.='<pre>$parameters='.print_r($parameters,true).'</pre>';
//if(isset($columns)) $debug.='<pre>$columns='.print_r($columns,true).'</pre>';
//if(isset($rowheaders)) $debug.='<pre>$rowheaders='.print_r($rowheaders,true).'</pre>';

// setPageTitle(_MD_GWREPORTS_EDITSECTION_FORM);
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
