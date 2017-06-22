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
    top.sMenuSubItem = "[{ oxmultilang ident="oxprobs_delivery" }]";
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
    <h1>[{ oxmultilang ident="oxprobs_delivery" }]</h1>
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
        
        <form name="oxprobs_groups" id="oxprobs_groups" action="[{ $oViewConf->selflink }]" method="post">
        [{ $oViewConf->hiddensid }]
        <input type="hidden" name="cl" value="oxprobs_delivery">
        <input type="hidden" name="oxid" value="[{ $oxid }]">
        <input type="hidden" name="fnc" value="">
        
        <select name="oxprobs_reporttype" onchange="document.forms['oxprobs_groups'].elements['fnc'].value='';document.oxprobs_groups.submit();">
            <option value="delsetcost" [{if $ReportType == "delsectcost"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_DELSETCOST" }]&nbsp;</option>
            <option value="delsetpay" [{if $ReportType == "delsetpay"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_DELSETPAY" }]&nbsp;</option>
            <option value="custpay" [{if $ReportType == "custpay"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_CUSTPAY" }]&nbsp;</option>
        </select>
        
        <input type="submit" 
               onClick="document.forms['oxprobs_groups'].elements['fnc'].value = '';" 
               value=" [{ oxmultilang ident="ORDER_MAIN_UPDATE_DELPAY" }] " />
               
        <span style="margin-left:24px;">
            <input class="edittext" type="submit" 
                onClick="document.forms['oxprobs_groups'].elements['fnc'].value = 'downloadResult';" 
                value=" [{ oxmultilang ident="OXPROBS_DOWNLOAD" }] " [{ $readonly }]>
        </span>
               
        <span style="margin-left:24px;">
            <input class="edittext" type="submit" 
                onClick="Javascript:window.print();return true;" 
                value=" [{ oxmultilang ident="OXPROBS_PRINT" }] " [{ $readonly }]>
        </span>
    </p>
        
    <p style="background-color:#f0f0f0;">
        [{if $ReportType == "delsetcost"}]
            [{ oxmultilang ident="OXPROBS_DELSETCOST_INFO" }]
        [{elseif $ReportType == "invdelsetpaycats"}]
            [{ oxmultilang ident="OXPROBS_DELSETPAY_INFO" }]
        [{/if}]
    </p>
    
    <p><div id="liste">
        <table cellspacing="0" cellpadding="0" border="0" width="99%">
        <tr>
            [{assign var="headStyle" value="border-bottom:1px solid #C8C8C8; font-weight:bold;" }]
            <td class="listfilter first" style="[{ $headStyle }]"><div class="r1"><div class="b1">[{ oxmultilang ident="GENERAL_COUNTRY" }]</div></div></td>
            <td class="listfilter" style="[{ $headStyle }]"><div class="r1"><div class="b1">
                [{if $ReportType == "custpay"}]
                    [{ oxmultilang ident="USER_OVERVIEW_GROUPS" }]
                [{else}]
                    [{ oxmultilang ident="ORDER_MAIN_DELTYPE" }]
                [{/if}]
            </div></div></td>
            <td class="listfilter" style="[{ $headStyle }]"><div class="r1"><div class="b1">
                [{if $ReportType == "delsetcost"}]
                    [{ oxmultilang ident="DELIVERY_LIST_MENUSUBITEM" }]
                [{else}]
                    [{ oxmultilang ident="USER_PAYMENT_PAYMENT" }]
                [{/if}]
            </div></div></td>
            <td class="listfilter" style="[{ $headStyle }]"><div class="r1"><div class="b1">[{ oxmultilang ident="SELECTLIST_MAIN_ADDFIELD_PREIS" }]</div></div></td>
            <td class="listfilter" style="[{ $headStyle }]"><div class="r1"><div class="b1">[{ oxmultilang ident="PAYMENT_MAIN_FROM" }] - [{ oxmultilang ident="PAYMENT_MAIN_TILL" }]</div></div></td>
            <td class="listfilter" style="[{$headStyle}]" align="center"><div class="r1"><div class="b1">
                <input type="checkbox" onclick="change_all('oxprobs_oxid[]', this)">
                </div></div>
            </td>
        </tr>

        [{ assign var="oldCountry" value="-" }]
        [{ assign var="oldDelSet" value="-" }]
        [{foreach name=outer item=Line from=$aList}]
            [{ cycle values="listitem,listitem2" assign="listclass" }]
            <tr>
                [{if $oldCountry != $Line.country}]
                    [{ assign var="oldCountry" value=$Line.country }]
                    [{ assign var="oldDelSet" value="-" }]
                    <td class="[{ $listclass }]">[{$Line.country}]</td>
                [{else}]
                    <td class="[{ $listclass }]"></td>
                [{/if}]
                [{if $oldDelSet != $Line.deliveryset}]
                    [{ assign var="oldDelSet" value=$Line.deliveryset }]
                    <td class="[{ $listclass }]">[{$Line.deliveryset}]</td>
                [{else}]
                    <td class="[{ $listclass }]"></td>
                [{/if}]
                [{if $ReportType == "delsetcost"}]
                    <td class="[{ $listclass }]">[{$Line.deliveryrule}]</td>
                [{else}]
                    <td class="[{ $listclass }]">[{$Line.payment}]</td>
                [{/if}]
                <td class="[{ $listclass }]">[{$Line.addsum|string_format:"%.2f"}] [{$Line.addtype}]</td>
                <td class="[{ $listclass }]">[{$Line.startval|string_format:"%.2f"}] - [{$Line.endval|string_format:"%.2f"}]</td>
                <td class="[{$listclass}]" align="center"><input type="checkbox" name="oxprobs_oxid[]" value="[{$Line.oxid}]"></td>
            </tr>
        [{/foreach}]

        </table>
        
        <p>
            &nbsp;[{$aList|@count}] [{ oxmultilang ident="OXPROBS_NUMOF_ENTRIES" }]
        </p>
        
        </div>
        </form>
    </p>

</div>
