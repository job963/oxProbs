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
  if(parent.parent)
  {
    top.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
    top.sMenuItem    = "[{ oxmultilang ident="oxprobs_module" }]";
    top.sMenuSubItem = "[{ oxmultilang ident="oxprobs_displaygroups" }]";
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
    oTransfer.cl.value='[{$editClassName}]';
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

<div class="center">
    <h1>[{ oxmultilang ident="oxprobs_displaygroups" }]</h1>
    <div style="position:absolute;top:4px;right:8px;color:gray;font-size:0.9em;border:1px solid gray;border-radius:3px;">
        &nbsp;[{$sModuleId}]&nbsp;[{$sModuleVersion}]&nbsp;
    </div>

    <p>
        <form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
            [{ $shop->hiddensid }]
            <input type="hidden" name="oxid" value="[{ $oxid }]">
            <input type="hidden" name="cl" value="[{$editClassName}]">
            <input type="hidden" name="updatelist" value="1">
        </form>
    
        <form name="showprobs" id="showprobs" action="[{ $oViewConf->selflink }]" method="post">
        [{ $oViewConf->hiddensid }]
        <input type="hidden" name="cl" value="oxprobs_groups">
        <input type="hidden" name="oxid" value="[{ $oxid }]">
        <input type="hidden" name="fnc" value="">
        <input type="hidden" name="sortcol" value="">
        <input type="hidden" name="language" value="[{ $actlang }]">
        <input type="hidden" name="editlanguage" value="[{ $actlang }]">
        
        <select name="oxprobs_reporttype" onchange="document.forms['showprobs'].elements['fnc'].value='';document.showprobs.submit();">
            <optgroup label="[{ oxmultilang ident="OXPROBS_GROUP_WRONG" }]">
                <option value="invactions" [{if $ReportType == "invactions"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_INVACTIONS" }]&nbsp;</option>
                <option value="invcats" [{if $ReportType == "invcats"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_INVCATS" }]&nbsp;</option>
                <option value="invman" [{if $ReportType == "invman"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_INVMAN" }]&nbsp;</option>
                <option value="invven" [{if $ReportType == "invven"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_INVVEN" }]&nbsp;</option>
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
        [{if $ReportType == "invactions"}]
            [{ oxmultilang ident="OXPROBS_INVACTIONS_INFO" }]
        [{elseif $ReportType == "invcats"}]
            [{ oxmultilang ident="OXPROBS_INVCATS_INFO" }]
        [{elseif $ReportType == "invman"}]
            [{ oxmultilang ident="OXPROBS_INVMAN_INFO" }]
        [{elseif $ReportType == "invven"}]
            [{ oxmultilang ident="OXPROBS_INVVEN_INFO" }]
        [{else}]
            [{foreach name=ReportTypes item=Report from=$aIncReports}]
                [{if $ReportType == $Report.name}][{ $Report.desc[$sIsoLang] }][{/if}]
            [{/foreach}]
        [{/if}]
        </div>
        
        <div id="liste">
        <table cellspacing="0" cellpadding="0" border="0" width="99%">
        <tr>
            [{ assign var="headStyle" value="border-bottom:1px solid #C8C8C8; font-weight:bold;" }]
            <td class="listfilter first" style="[{ $headStyle }]"><div class="r1"><div class="b1">
                [{ oxmultilang ident="ARTICLE_MAIN_TITLE" }]
                </div></div></td>
            <td class="listfilter" style="[{ $headStyle }]"><div class="r1"><div class="b1">
                [{ oxmultilang ident="USER_ARTICLE_QUANTITY" }]
                </div></div></td>
            <td class="listfilter" style="[{ $headStyle }]"><div class="r1"><div class="b1">
                [{ oxmultilang ident="OXPROBS_STATE" }]
                </div></div></td>
            <td class="listfilter" style="[{$headStyle}]" align="center"><div class="r1"><div class="b1">
                <input type="checkbox" onclick="change_all('oxprobs_oxid[]', this)">
                </div></div>
            </td>
        </tr>

        [{foreach name=outer item=Group from=$aGroups}]
            [{ cycle values="listitem,listitem2" assign="listclass" }]
            <tr>
                <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Group.oxid}]');">[{$Group.oxtitle}]</a></td>
                <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Group.oxid}]');">[{$Group.count}]</a></td>
                [{ assign var="errCodes" value="|"|explode:$Group.status }]
                <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Group.oxid}]');">
                    [{foreach name=inner item=errCode from=$errCodes}]
                        [{ if $errCode != "" }]
                            [{ oxmultilang ident=$errCode }]&nbsp;&nbsp;&nbsp;
                        [{/if}]
                    [{/foreach}]
                </a></td>
                <td class="[{$listclass}]" align="center"><input type="checkbox" name="oxprobs_oxid[]" value="[{$Group.oxid}]"></td>
            </tr>
        [{/foreach}]

        </table>
        
        <p>
        &nbsp;[{$aGroups|@count}] [{ oxmultilang ident="OXPROBS_NUMOF_ENTRIES" }]
        </p>
        
        </form>
        </div>
    </p>

</div>
