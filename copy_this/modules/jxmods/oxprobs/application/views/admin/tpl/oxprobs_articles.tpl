[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box=" "}]

[{assign var="cssFilePath" value=$oViewConf->getModulePath('oxprobs','out/admin/src/oxprobs.css') }]
[{php}] 
    $sCssFilePath = $this->get_template_vars('cssFilePath');;
    $sCssTime = filemtime( $sCssFilePath );
    $this->assign('cssTime', $sCssTime);
[{/php}]
[{assign var="cssFileUrl" value=$oViewConf->getModuleUrl('oxprobs','out/admin/src/oxprobs.css') }]
[{assign var="cssFileUrl" value="$cssFileUrl?$cssTime" }]
<link href="[{$cssFileUrl}]" type="text/css" rel="stylesheet">

<script type="text/javascript">
if(top)
{
    top.sMenuItem    = "[{ oxmultilang ident="oxprobs_module" }]";
    top.sMenuSubItem = "[{ oxmultilang ident="oxprobs_displayarticles" }]";
    top.sWorkArea    = "[{$_act}]";
    top.setTitle();
}

function editThis( sID )
{
    [{assign var="shMen" value=1}]

    [{foreach from=$menustructure item=menuholder }]
      [{if $shMen && $menuholder->nodeType == XML_ELEMENT_NODE && $menuholder->childNodes->length }]

        [{assign var="shMen" value=0}]
        [{assign var="mn" value=1}]

        [{foreach from=$menuholder->childNodes item=menuitem }]
          [{if $menuitem->nodeType == XML_ELEMENT_NODE && $menuitem->childNodes->length }]
            [{ if $menuitem->getAttribute('id') == 'mxorders' }]

              [{foreach from=$menuitem->childNodes item=submenuitem }]
                [{if $submenuitem->nodeType == XML_ELEMENT_NODE && $submenuitem->getAttribute('cl') == 'admin_order' }]

                    if ( top && top.navigation && top.navigation.adminnav ) {
                        var _sbli = top.navigation.adminnav.document.getElementById( 'nav-1-[{$mn}]-1' );
                        var _sba = _sbli.getElementsByTagName( 'a' );
                        top.navigation.adminnav._navAct( _sba[0] );
                    }

                [{/if}]
              [{/foreach}]

            [{ /if }]
            [{assign var="mn" value=$mn+1}]

          [{/if}]
        [{/foreach}]
      [{/if}]
    [{/foreach}]

    var oTransfer = document.getElementById("transfer");
    oTransfer.oxid.value=sID;
    oTransfer.cl.value='article';
    oTransfer.submit();
}

function change_all( name, elem )
{
    if(!elem || !elem.form) 
        return alert("Check Parameters");

    var chkbox = elem.form.elements[name];
    if (!chkbox) 
        return alert(name + " doesn't exist!");

    if (!chkbox.length) 
        chkbox.checked = elem.checked; 
    else 
        for(var i = 0; i < chkbox.length; i++)
            chkbox[i].checked = elem.checked;
}

</script>

[{assign var="oConfig" value=$oViewConf->getConfig()}]

<div class="center">
    <h1>[{ oxmultilang ident="oxprobs_displayarticles" }]</h1>
    <div style="position:absolute;top:4px;right:8px;color:gray;font-size:0.9em;border:1px solid gray;border-radius:3px;">&nbsp;[{$sModuleId}]&nbsp;[{$sModuleVersion}]&nbsp;</div>
	
    <p>
        <form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
            [{ $shop->hiddensid }]
            <input type="hidden" name="oxid" value="[{ $oxid }]">
            <input type="hidden" name="cl" value="article">
            <input type="hidden" name="updatelist" value="1">
        </form>

        <form name="showprobs" id="showprobs"  action="[{ $shop->selflink }]" method="post">
        [{ $shop->hiddensid }]
        <input type="hidden" name="cl" value="oxprobs_articles">
        <input type="hidden" name="fnc" value="">
        <input type="hidden" name="sortcol" value="">
        <input type="hidden" name="language" value="[{ $actlang }]">
        <input type="hidden" name="editlanguage" value="[{ $actlang }]">
        <input type="hidden" name="lastsortcol" value="[{ $sortcol }]">
        <input type="hidden" name="lastsortopt" value="[{ $sortopt }]">

        <select name="oxprobs_reporttype" onchange="document.forms['showprobs'].elements['fnc'].value='';this.form.submit()">
            <optgroup label="[{ oxmultilang ident="OXPROBS_GROUP_STOCK" }]">
                <option value="nostock" [{if $ReportType == "nostock"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOSTOCK" }]&nbsp;</option>
                <option value="missstockinfo" [{if $ReportType == "missstockinfo"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_MISSSTOCKINFO" }]&nbsp;</option>
                <option value="stockalert" [{if $ReportType == "stockalert"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_STOCKALERT" }]&nbsp;</option>
                <option value="noreminder" [{if $ReportType == "noreminder"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOREMINDER" }]&nbsp;</option>
                <option value="noremindvalue" [{if $ReportType == "noremindvalue"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOREMINDVALUE" }]&nbsp;</option>
            </optgroup>
            <optgroup label="[{ oxmultilang ident="OXPROBS_GROUP_MISSING" }]">
                <option value="noartnum" [{if $ReportType == "noartnum"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOARTNUM" }]&nbsp;</option>
                <option value="noshortdesc" [{if $ReportType == "noshortdesc"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOSHORTDESC" }]&nbsp;</option>
                <option value="nopic" [{if $ReportType == "nopic"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOPIC" }]&nbsp;</option>
                <option value="noean" [{if $ReportType == "noean"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOEAN" }]&nbsp;</option>
                <option value="noprice" [{if $ReportType == "noprice"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOPRICE" }]&nbsp;</option>
                <option value="nobuyprice" [{if $ReportType == "nobuyprice"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOBUYPRICE" }]&nbsp;</option>
                <option value="nompn" [{if $ReportType == "nompn"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOMPN" }]&nbsp;</option>
                <option value="nocat" [{if $ReportType == "nocat"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOCAT" }]&nbsp;</option>
                <option value="orphan" [{if $ReportType == "orphan"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_ORPHAN" }]&nbsp;</option>
                <option value="nodesc" [{if $ReportType == "nodesc"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NODESC" }]&nbsp;</option>
                <option value="nomanu" [{if $ReportType == "nomanu"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOMANU" }]&nbsp;</option>
                <option value="novend" [{if $ReportType == "novend"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOVEND" }]&nbsp;</option>
            </optgroup>
            <optgroup label="[{ oxmultilang ident="OXPROBS_GROUP_WRONG" }]">
                <option value="duplicate" [{if $ReportType == "duplicate"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_DUPLICATE" }]&nbsp;</option>
                <option value="dblactive" [{if $ReportType == "dblactive"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_DBLACTIVE" }]&nbsp;</option>
                <option value="longperiod" [{if $ReportType == "longperiod"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_LONGPERIOD" }]&nbsp;</option>
                <option value="invperiod" [{if $ReportType == "invperiod"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_INVPERIOD" }]&nbsp;</option>
                <option value="eanchk" [{if $ReportType == "eanchk"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_EANCHK" }]&nbsp;</option>
            </optgroup>
            <optgroup label="[{ oxmultilang ident="OXPROBS_GROUP_MISC" }]">
                <option value="active" [{if $ReportType == "active"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_ACTIVE" }]&nbsp;</option>
                <option value="inactive" [{if $ReportType == "inactive"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_INACTIVE" }]&nbsp;</option>
            </optgroup>
            <optgroup label="[{ oxmultilang ident="OXPROBS_GROUP_CUSTOM" }]">
                [{foreach name=ReportList item=Report from=$aIncReports}]
                    <option value="[{$Report.name}]" [{if $ReportType == $Report.name}]selected[{/if}]>[{ $Report.title[$sIsoLang] }]&nbsp;</option>
                [{/foreach}]
            </optgroup>
        </select>
            
        <input type="submit" 
               onClick="document.forms['showprobs'].elements['fnc'].value = '';" 
               value=" [{ oxmultilang ident="ORDER_MAIN_UPDATE_DELPAY" }] " />
               
        <span style="margin-left:24px;">
            <input class="edittext" type="submit" 
                onClick="document.forms['showprobs'].elements['fnc'].value = 'downloadResult';" 
                value=" [{ oxmultilang ident="OXPROBS_DOWNLOAD" }] " [{ $readonly }]>
        </span>
               
        <span style="margin-left:24px;">
            <input class="edittext" type="submit" 
                onClick="Javascript:window.print();return true;" 
                value=" [{ oxmultilang ident="OXPROBS_PRINT" }] " [{ $readonly }]>
        </span>
    </p>
        
    <p style="background-color:#f0f0f0;">
        <div style="padding-bottom:5px;">
        [{assign var="CustomReport" value="False"}]
        [{if $ReportType == "nostock"}]
            [{ oxmultilang ident="OXPROBS_NOSTOCK_INFO" }]
        [{elseif $ReportType == "missstockinfo"}]
            [{ oxmultilang ident="OXPROBS_MISSSTOCKINFO_INFO" }]
        [{elseif $ReportType == "stockalert"}]
            [{ oxmultilang ident="OXPROBS_STOCKALERT_INFO" }]
        [{elseif $ReportType == "noreminder"}]
            [{ oxmultilang ident="OXPROBS_NOREMINDER_INFO" }]
        [{elseif $ReportType == "noremindvalue"}]
            [{ oxmultilang ident="OXPROBS_NOREMINDVALUE_INFO" }]
        [{elseif $ReportType == "noartnum"}]
            [{ oxmultilang ident="OXPROBS_NOARTNUM_INFO" }]
        [{elseif $ReportType == "noshortdesc"}]
            [{ oxmultilang ident="OXPROBS_NOSHORTDESC_INFO" }]
        [{elseif $ReportType == "nopic"}]
            [{ oxmultilang ident="OXPROBS_NOPIC_INFO" }]
        [{elseif $ReportType == "dblactive"}]
            [{ oxmultilang ident="OXPROBS_DBLACTIVE_INFO" }]
        [{elseif $ReportType == "duplicate"}]
            [{ oxmultilang ident="OXPROBS_DUPLICATE_INFO" }]
        [{elseif $ReportType == "longperiod"}]
            [{ oxmultilang ident="OXPROBS_LONGPERIOD_INFO" }]
        [{elseif $ReportType == "invperiod"}]
            [{ oxmultilang ident="OXPROBS_INVPERIOD_INFO" }]
        [{elseif $ReportType == "noprice"}]
            [{ oxmultilang ident="OXPROBS_NOPRICE_INFO" }]
        [{elseif $ReportType == "nobuyprice"}]
            [{ oxmultilang ident="OXPROBS_NOBUYPRICE_INFO" }]
        [{elseif $ReportType == "noean"}]
            [{ oxmultilang ident="OXPROBS_NOEAN_INFO" }]
        [{elseif $ReportType == "eanchk"}]
            [{ oxmultilang ident="OXPROBS_EANCHK_INFO" }]
        [{elseif $ReportType == "nompn"}]
            [{ oxmultilang ident="OXPROBS_NOMPN_INFO" }]
        [{elseif $ReportType == "nocat"}]
            [{ oxmultilang ident="OXPROBS_NOCAT_INFO" }]
        [{elseif $ReportType == "orphan"}]
            [{ oxmultilang ident="OXPROBS_ORPHAN_INFO" }]
        [{elseif $ReportType == "nodesc"}]
            [{ oxmultilang ident="OXPROBS_NODESC_INFO" }]
        [{elseif $ReportType == "nomanu"}]
            [{ oxmultilang ident="OXPROBS_NOMANU_INFO" }]
        [{elseif $ReportType == "novend"}]
            [{ oxmultilang ident="OXPROBS_NOVEND_INFO" }]
        [{elseif $ReportType == "active"}]
            [{ oxmultilang ident="OXPROBS_ACTIVE_INFO" }]
        [{elseif $ReportType == "inactive"}]
            [{ oxmultilang ident="OXPROBS_INACTIVE_INFO" }]
        [{else}]
            [{foreach name=ReportTypes item=Report from=$aIncReports}]
                [{if $ReportType == $Report.name}][{ $Report.desc[$sIsoLang] }][{/if}]
            [{/foreach}]
            [{assign var="CustomReport" value="True"}]
        [{/if}]
        </div>
        
        <div id="liste">
        <table cellspacing="0" cellpadding="0" border="0" width="99%">
        <tr>
            <td valign="top" class="listfilter first" align="right">
                <div class="r1"><div class="b1">&nbsp;</div></div>
            </td>
            <td class="listfilter">
                <div class="r1"><div class="b1">
                <input class="listedit" type="text" size="15" maxlength="128" name="where[oxartnum]" value="[{ $aWhere.oxartnum }]">
                </div></div>
            </td>
            <td class="listfilter">
                <div class="r1"><div class="b1">
                <input class="listedit" type="text" size="15" maxlength="128" name="where[oxtitle]" value="[{ $aWhere.oxtitle }]">
                </div></div>
            </td>
            [{if $ReportType == "noshortdesc"}]
                <td class="listfilter">
                    <div class="r1"><div class="b1">
                    <input class="listedit" type="text" size="15" maxlength="128" name="where[oxshortdesc]" value="[{ $aWhere.oxshortdesc }]">
                    </div></div>
                </td>
            [{/if}]
            <td class="listfilter">
                <div class="r1"><div class="b1">
                <input class="listedit" type="text" size="15" maxlength="128" name="where[oxvarselect]" value="[{ $aWhere.oxvarselect }]">
                </div></div>
            </td>
            [{if $ReportType != "noshortdesc" and $ReportType != "longperiod" and $ReportType != "invperiod" }]
                <td class="listfilter">
                    <div class="r1"><div class="b1">
                    <input class="listedit" type="text" size="15" maxlength="128" name="where[oxean]" value="[{ $aWhere.oxean }]">
                    </div></div>
                </td>
            [{/if}]
            <td class="listfilter">
                <div class="r1"><div class="b1">
                <input class="listedit" type="text" size="15" maxlength="128" name="where[oxmantitle]" value="[{ $aWhere.oxmantitle }]">
                </div></div>
            </td>
            [{if $ReportType == "longperiod" or $ReportType == "invperiod"  }]
                <td class="listfilter">
                    <div class="r1"><div class="b1">
                    [{* oxmultilang ident="GENERAL_ARTICLE_OXACTIVEFROM" *}]
                    </div></div>
                </td>
                <td class="listfilter">
                    <div class="r1"><div class="b1">
                    [{* oxmultilang ident="GENERAL_ARTICLE_OXACTIVETO" *}]
                    </div></div>
                </td>
            [{/if}]
            [{if $ReportType != "noshortdesc" and  $ReportType != "longperiod" and $ReportType != "invperiod" }]
                <td class="listfilter">
                    <div class="r1"><div class="b1">
                    [{* oxmultilang ident="GENERAL_VENDOR" }] [{ oxmultilang ident="ARTICLE_MAIN_ARTNUM" *}]
                    </div></div>
                </td>
            [{/if}]
            [{if $CustomReport != True }]
                <td class="listfilter">
                    <div class="r1"><div class="b1">
                    [{if $ReportType == "nobuyprice"}]
                        [{* oxmultilang ident="ARTICLE_EXTEND_BPRICE" *}]
                    [{else}]
                        [{* oxmultilang ident="GENERAL_ARTICLE_OXSTOCK" *}]
                    [{/if}]
                    </div></div>
                </td>
            [{/if}]
            <td class="listfilter">
                <div class="r1"><div class="b1"><div class="find">
                <input class="listedit" type="submit" name="submitit" value="[{ oxmultilang ident="GENERAL_SEARCH" }]">
                </div></div></div>
            </td>
            <td class="listfilter" style="[{$headStyle}]" align="center"><div class="r1"><div class="b1">
                <input type="checkbox" onclick="change_all('oxprobs_oxid[]', this)">
                </div></div>
            </td>
        </tr>
        <tr>
            [{if $sortopt=='ASC'}]
                [{assign var="sorticon" value="&nbsp;&nbsp;&blacktriangle;"}]
            [{else}]
                [{assign var="sorticon" value="&nbsp;&nbsp;&blacktriangledown;"}]
            [{/if}]
            <td class="listheader first" height="15" width="30" align="center">
                <a href="javascript:document.forms.showprobs.sortcol.value='oxactive';document.forms.showprobs.submit();" class="listheader">
                    [{ oxmultilang ident="GENERAL_ACTIVTITLE" }]
                </a>
            </td>
            <td class="listheader">
                <a href="javascript:document.forms.showprobs.sortcol.value='oxartnum';document.forms.showprobs.submit();" class="listheader">
                    [{ oxmultilang ident="ARTICLE_MAIN_ARTNUM" }]
                </a>
                [{ if $sortcol == 'oxartnum' }]<span style="font-size:1.5em;">[{ $sorticon }]</span>[{/if}]
            </td>
            <td class="listheader">
                <a href="javascript:document.forms.showprobs.sortcol.value='oxtitle';document.forms.showprobs.submit();" class="listheader">
                    [{ oxmultilang ident="ARTICLE_MAIN_TITLE" }]
                </a>
                [{ if $sortcol == 'oxtitle' }]<span style="font-size:1.5em;">[{ $sorticon }]</span>[{/if}]
            </td>
            [{if $ReportType == "noshortdesc"}]
                <td class="listheader">
                    <a href="javascript:document.forms.showprobs.sortcol.value='oxshortdesc';document.forms.showprobs.submit();" class="listheader">
                    [{ oxmultilang ident="GENERAL_ARTICLE_OXSHORTDESC" }]
                    </a>
                    [{ if $sortcol == 'oxshortdesc' }]<span style="font-size:1.5em;">[{ $sorticon }]</span>[{/if}]
                </td>
            [{/if}]
            <td class="listheader">
                <a href="javascript:document.forms.showprobs.sortcol.value='oxvarselect';document.forms.showprobs.submit();" class="listheader">
                    [{ oxmultilang ident="GENERAL_ARTICLE_OXVARNAME" }]
                </a>
                [{ if $sortcol == 'oxvarselect' }]<span style="font-size:1.5em;">[{ $sorticon }]</span>[{/if}]
            </td>
            [{if $ReportType != "noshortdesc" and $ReportType != "longperiod" and $ReportType != "invperiod" }]
                <td class="listheader">
                    <a href="javascript:document.forms.showprobs.sortcol.value='oxean';document.forms.showprobs.submit();" class="listheader">
                    [{ oxmultilang ident="ARTICLE_MAIN_EAN" }]
                    </a>
                    [{ if $sortcol == 'oxean' }]<span style="font-size:1.5em;">[{ $sorticon }]</span>[{/if}]
                </td>
            [{/if}]
            <td class="listheader">
                    <a href="javascript:document.forms.showprobs.sortcol.value='oxmantitle';document.forms.showprobs.submit();" class="listheader">
                    [{ oxmultilang ident="ARTICLE_MAIN_MANUFACTURERID" }]
                    </a>
                [{ if $sortcol == 'oxmantitle' }]<span style="font-size:1.5em;">[{ $sorticon }]</span>[{/if}]
                </td>
            [{if $ReportType == "longperiod" or $ReportType == "invperiod"  }]
                <td class="listheader">
                [{ oxmultilang ident="GENERAL_ARTICLE_OXACTIVEFROM" }]
                </td>
                <td class="listheader">
                [{ oxmultilang ident="GENERAL_ARTICLE_OXACTIVETO" }]
                </td>
            [{/if}]
            [{if $ReportType != "noshortdesc" and  $ReportType != "longperiod" and $ReportType != "invperiod" and $CustomReport == False }]
                <td class="listheader">
                [{ oxmultilang ident="GENERAL_MANUFACTURER" }] [{ oxmultilang ident="ARTICLE_MAIN_ARTNUM" }]
                </td>
            [{/if}]
            [{if $bCustomColumn }]
                <td class="listheader">
                [{if $aColumnTitles[$sIsoLang] }]
                    [{ $aColumnTitles[$sIsoLang] }]
                [{else}]
                    [{ oxmultilang ident="GENERAL_EXTRAINFO" }]
                [{/if}]
                </td>
            [{else}]
                <td class="listheader">
                    [{if $ReportType == "nobuyprice"}]
                        [{ oxmultilang ident="ARTICLE_EXTEND_BPRICE" }]
                    [{else}]
                        [{ oxmultilang ident="GENERAL_ARTICLE_OXSTOCK" }]
                    [{/if}]
                </td>
            [{/if}]
            <td class="listheader">
                [{ oxmultilang ident="GENERAL_ARTICLE_OXPRICE" }]
            </td>
            <td class="listheader"></td>
        </tr>

        [{foreach name=outer item=Article from=$aArticles}]
            [{ cycle values="listitem,listitem2" assign="listclass" }]
            [{*<tr class="[{cycle values="even,odd"}]">*}]
            [{if  $ReportType == "nobuyprice"}]
            
                [{if $Article.oxbprice == 0.0 }]
                    [{assign var="txtColor" value="#000000" }]
                 [{else}]
                    [{assign var="txtColor" value="#a0a0a0" }]
                [{/if}]
                
             [{else}]
                [{assign var="txtColor" value="#000000" }]
            [{/if}]
            <tr>
                <td class="[{ $listclass }] [{ if $Article.oxactive == 1}] active
                        [{elseif $Article.oxactive == 2}] activetime
                        [{/if}]">
                        <div class="listitemfloating">&nbsp</a></div>
                </td>
                <td class="[{ $listclass }]">
                    [{if $oConfig->getConfigParam("bOxProbsProductPreview") }]
                         <a class="thumbnail" href="#thumb">
                            [{*<img src="[{$Article.picname}]" style="max-height:28px;width:auto;"/>*}]
                            [{$Article.oxartnum}]<span><img src="[{$Article.picname}]" /></span>
                        </a>
                    [{else}]
                        <a href="Javascript:editThis('[{$Article.oxid}]');" style="color:[{$txtColor}];">[{$Article.oxartnum}]</a>
                    [{/if}]
                </td>
                <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Article.oxid}]');" style="color:[{$txtColor}];">[{$Article.oxtitle}]</a></td>
                [{if $ReportType == "noshortdesc"}]
                    <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Article.oxid}]');" style="color:[{$txtColor}];">[{$Article.oxshortdesc}]</a></td>
                [{/if}]
                <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Article.oxid}]');" style="color:[{$txtColor}];">[{$Article.oxvarselect}]</a></td>
                [{if $ReportType != "noshortdesc" and $ReportType != "longperiod" and $ReportType != "invperiod" }]
                    <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Article.oxid}]');" style="color:[{$txtColor}];">[{$Article.oxean}]</a></td>
                [{/if}]
                <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Article.oxid}]');" style="color:[{$txtColor}];">[{$Article.oxmantitle}]</a></td>
                [{if $ReportType == "longperiod" or $ReportType == "invperiod"  }]
                    <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Article.oxid}]');" style="color:[{$txtColor}];">[{$Article.oxactivefrom}]</a></td>
                    <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Article.oxid}]');" style="color:[{$txtColor}];">[{$Article.oxactiveto}]</a></td>
                [{/if}]
                [{if $ReportType != "noshortdesc" and $ReportType != "longperiod" and $ReportType != "invperiod" and  $CustomReport != True }]
                    <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Article.oxid}]');" style="color:[{$txtColor}];">[{$Article.oxmpn}]</a></td>
                [{/if}]
                [{if $bCustomColumn }]
                    <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Article.oxid}]');" style="color:[{$txtColor}];">[{$Article.infotext}]</a></td>
                [{else}]
                    <td class="[{ $listclass }]">
                        <a href="Javascript:editThis('[{$Article.oxid}]');" style="color:[{$txtColor}];">
                        [{if $ReportType == "nobuyprice"}]
                            [{$Article.oxbprice|string_format:"%.2f"}]
                        [{else}]
                            [{$Article.oxstock}]
                        [{/if}]</a>
                    </td>
                [{/if}]
                <td class="[{ $listclass }]" align="right"><a href="Javascript:editThis('[{$Article.oxid}]');" style="color:[{$txtColor}];">[{$Article.oxprice|string_format:"%.2f"}]</a></td>
                <td class="[{$listclass}]" align="center"><input type="checkbox" name="oxprobs_oxid[]" value="[{$Article.oxid}]"></td>
            </tr>
        [{/foreach}]

        </table>
        </form>
        
        <p>
        &nbsp;[{$aArticles|@count}] [{ oxmultilang ident="OXPROBS_NUMOF_ENTRIES" }]
        </p>
        
        </div>
    </p>

</div>
