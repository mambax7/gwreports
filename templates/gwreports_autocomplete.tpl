<{if $needjquery}>
    <script>
        if (!(document.getElementById('gwreports_ac_dt'))) {
            var gwrpt_lang_all = "<{$smarty.const._MD_GWREPORTS_JQDT_ALL}>";
            var gwrpt_lang_slengthmenu = "<{$smarty.const._MD_GWREPORTS_JQDT_SLENGTHMENU}>";
            var gwrpt_lang_ssearch = "<{$smarty.const._MD_GWREPORTS_JQDT_SSEARCH}>";
            var gwrpt_lang_sinfo = "<{$smarty.const._MD_GWREPORTS_JQDT_SINFO}>";
            var gwrpt_lang_sinfoempty = "<{$smarty.const._MD_GWREPORTS_JQDT_SINFOEMPTY}>";
            var gwrpt_lang_sinfofiltered = "<{$smarty.const._MD_GWREPORTS_JQDT_SINFOFILTERED}>";
            var gwrpt_lang_semptytable = "<{$smarty.const._MD_GWREPORTS_JQDT_SEMPTYTABLE}>";
            var gwrpt_lang_szerorecords = "<{$smarty.const._MD_GWREPORTS_JQDT_SZERORECORDS}>";

            var gwrpt_lang_snext = "<{$smarty.const._MD_GWREPORTS_JQDT_SNEXT}>";
            var gwrpt_lang_sprevious = "<{$smarty.const._MD_GWREPORTS_JQDT_SPREVIOUS}>";
            var gwrpt_lang_sfirst = "<{$smarty.const._MD_GWREPORTS_JQDT_SFIRST}>";
            var gwrpt_lang_slast = "<{$smarty.const._MD_GWREPORTS_JQDT_SLAST}>";
            var gwrpt_jqurl = '<{$xoops_url}>/modules/gwreports/include/jqueryui/';

            var head = document.getElementsByTagName('head')[0]
            var script = document.createElement('script');
            script.type = 'text/javascript';
            script.src = '<{$xoops_url}>/modules/gwreports/include/gwreports_ac_dt.js';
            script.id = 'gwreports_ac_dt';
            head.appendChild(script);
        }
    </script>
<{/if}>
