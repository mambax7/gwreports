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
    <br/>
<{/if}>
<{if is_array($topics) && count($topics) > 0 }>
    <br/>
    <table width='100%' border='0' cellspacing='1' class='outer'>
        <tr>
            <th><{$smarty.const._MD_GWREPORTS_TOPIC_LIST}></th>
        </tr>

        <{foreach key=tid item=topic from=$topics }>
            <tr class="<{cycle values=" odd,even
    "}>">
                <td><a href="edittopic.php?tid=<{$topic.topic_id}>" title="<{$topic.topic_description|truncate:250:'...':false}>"><{$topic.topic_name}></a></td>
            </tr>
        <{/foreach}>
    </table>
<{/if}>
<{if isset($debug)}>
    <div><{$debug}></div>
<{/if}>
