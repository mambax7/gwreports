<{include file="db:gwreports_autocomplete.tpl"}>
<{if isset($bc_modtitle)}>
    <div class="breadcrumb">
        <a href="index.php"><{$bc_modtitle}></a>
        <{if isset($bc_topic)}>
            &gt;
            <a href="index.php?tid=<{$bc_tid}>"><{$bc_topic}></a>
        <{/if}>
        <{if isset($bc_report)}>
            &gt;
            <a href="report_view.php?rid=<{$bc_rid}>"><{$bc_report}></a>
        <{/if}>
        <br/>
        <hr/>
    </div>
<{/if}>
<{if isset($title)}>
    <div class="content_body">
        <h1><{$title}></h1>
    </div>
<{/if}>
<{if isset($page_message)}>
    <div class="content_body"><{$page_message}><br/><br/></div>
<{/if}>
<{if isset($err_message)}>
    <hr/>
    <div class="errorMsg"><{$err_message}></div>
    <hr/>
<{/if}>
<{if isset($message)}>
    <hr/>
    <div class="resultMsg"><{$message}></div>
    <hr/>
<{/if}>
<{if isset($report_parameter_form)}>
    <div class="parmform"><{$report_parameter_form}></div>
    <br/>
<{/if}>
<{if isset($body)}>
    <div class="gwrptreport"><{$body}></div>
<{/if}><br/>
<{if is_array($report_sections)}>
    <table width='100%' border='0' cellspacing='1' class='outer'>
        <tr>
            <th><{$smarty.const._MD_GWREPORTS_SECTION_LIST}></th>
        </tr>
        <{foreach key=sid item=section from=$report_sections }>
            <tr class="<{cycle values=" odd,even"}>">
                <td><a href="editsection.php?sid=<{$section.section_id}>" title="<{$section.section_description|truncate:250:'...':false}>"><{$section.section_name}></a></td>
            </tr>
        <{/foreach}>
        <tr>
            <td align="right"><a href="newsection.php?rid=<{$report_id}>"><{$smarty.const._MD_GWREPORTS_ADMIN_SECTION_ADD}></a> | <a href="sortsections.php?rid=<{$report_id}>"><{$smarty.const._MD_GWREPORTS_ADMIN_SECTION_SORT}></a></td>
        </tr>
    </table>
    <br/>
<{/if}>
<{if isset($debug)}>
    <div><{$debug}></div>
<{/if}>
