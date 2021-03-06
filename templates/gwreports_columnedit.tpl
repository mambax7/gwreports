<{include file="db:gwreports_autocomplete.tpl"}>
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
<{if isset($body)}>
    <div><{$body}></div>
<{/if}><br/>
<{if is_array($section_columns)  && count($section_columns) > 0 }>
    <table width='100%' border='0' cellspacing='1' class='outer'>
        <tr>
            <th><{$smarty.const._MD_GWREPORTS_COLUMN_LIST}></th>
            <th><{$smarty.const._MD_GWREPORTS_COLUMN_TITLE}></th>
            <th><{$smarty.const._MD_GWREPORTS_COLUMN_AS_VAR}></th>
        </tr>
        <{foreach key=cid item=column from=$section_columns }>
            <tr class="<{cycle values=" odd,even"}>">
                <td><a href="editcolumn.php?cid=<{$column.column_id}>"><{$column.column_name}></a></td>
                <td><a href="editcolumn.php?cid=<{$column.column_id}>"><{$column.column_title}></a></td>
                <td>{<{$column.column_name}>}</td>
            </tr>
        <{/foreach}>
    </table>
    <br/>
<{/if}>
<{if isset($report_parameter_form)}>
    <div class="parmform"><{$report_parameter_form}></div>
    <br/>
<{/if}>
<{if isset($debug)}>
    <div><{$debug}></div>
<{/if}>
