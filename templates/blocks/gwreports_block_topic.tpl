<{* available column list *}>
<{* report_id *}>
<{* report_name *}>
<{* report_description *}>
<{* link - link to report_view *}>
<{if is_array($block) && count($block) > 0 }>
    <div class="gwrptblocktopic">
        <ul>
            <{foreach key=rid item=report from=$block }>
                <li><a href="<{$report.link}>" title="<{$report.report_description|truncate:250:'...':false}>"><{$report.report_name}> </a></li>
            <{/foreach}>
        </ul>
    </div>
<{/if}>
