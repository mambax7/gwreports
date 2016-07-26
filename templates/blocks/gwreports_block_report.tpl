<{* report is in $block.body *}>
<{if isset($block.body)}>
    <{assign var=needjquery value=$block.needjquery}>
    <{include file="db:gwreports_autocomplete.tpl"}>
    <div class="gwrptblockreport"><{$block.body}></div>
<{/if}>
