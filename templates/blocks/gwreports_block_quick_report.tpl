<{* available indexes in $block *}>
<{* report_id *}>
<{* report_name *}>
<{* report_description *}>
<{* link - link to report_view *}>
<{* form - simple form to run report  *}>
<{if is_array($block) && count($block) > 0 }>
    <{assign var=needjquery value=$block.needjquery}>
    <{include file="db:gwreports_autocomplete.tpl"}>
    <div class="gwrptblockform">
        <{if isset($block.form)}>
            <{$block.form}>
        <{else}>
            <a href="<{$block.link}>" title="<{$block.report_description|truncate:250:'...':false}>"><{$block.report_name}></a>
        <{/if}>
    </div>
<{/if}>
