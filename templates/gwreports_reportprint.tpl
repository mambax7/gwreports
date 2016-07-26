<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>

    <{if isset($title)}>
        <title><{$title}></title>
    <{/if}>
    <link rel="stylesheet" type="text/css" href="print.css"/>

</head>
<body onload="window.print()">

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
    <div><{$body}></div>
<{/if}><br/>
<{if isset($debug)}>
    <div><{$debug}></div>
<{/if}>


</body>
</html>
