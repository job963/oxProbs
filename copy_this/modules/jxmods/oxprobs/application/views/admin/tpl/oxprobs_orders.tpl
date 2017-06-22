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
    top.sMenuSubItem = "[{ oxmultilang ident="oxprobs_displayorders" }]";
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
    oTransfer.cl.value='admin_order[{*$editClassName*}]';
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
    <h1>[{ oxmultilang ident="oxprobs_displayorders" }]</h1>
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
        <input type="hidden" name="cl" value="oxprobs_orders">
        <input type="hidden" name="oxid" value="[{ $oxid }]">
        <input type="hidden" name="fnc" value="">
        <input type="hidden" name="sortcol" value="">
        <input type="hidden" name="language" value="[{ $actlang }]">
        <input type="hidden" name="editlanguage" value="[{ $actlang }]">
        
        [{*php}] 
            $sIsoLang = oxLang::getInstance()->getLanguageAbbr(); 
            $this->assign('IsoLang', $sIsoLang);
        [{/php*}]
        
        <select name="oxprobs_reporttype" onchange="document.forms['showprobs'].elements['fnc'].value='';document.showprobs.submit();">
            <optgroup label="[{ oxmultilang ident="OXPROBS_GROUP_ORDERS" }]">
                <option value="readyorders" [{if $ReportType == "readyorders"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_READY_ORDERS" }]&nbsp;</option>
                [{*<option value="invcats" [{if $ReportType == "invcats"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_INVCATS" }]&nbsp;</option>
                <option value="invman" [{if $ReportType == "invman"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_INVMAN" }]&nbsp;</option>
                <option value="invven" [{if $ReportType == "invven"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_INVVEN" }]&nbsp;</option>*}]
            </optgroup>
            <optgroup label="[{ oxmultilang ident="OXPROBS_GROUP_PAYMENT" }]">
                <option value="opencia" [{if $ReportType == "opencia"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_PAY_OPENCIA" }]&nbsp;</option>
                <option value="openinv" [{if $ReportType == "openinv"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_PAY_OPENINVOICES" }]&nbsp;</option>
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
        [{if $ReportType == "readyorders"}]
            [{ oxmultilang ident="OXPROBS_READY_ORDERS_INFO" }]
        [{elseif $ReportType == "opencia"}]
            [{ oxmultilang ident="OXPROBS_PAY_OPENCIA_INFO" }]
        [{elseif $ReportType == "openinv"}]
            [{ oxmultilang ident="OXPROBS_PAY_OPENINVOICES_INFO" }]
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
                [{ oxmultilang ident="GENERAL_ORDERNUM" }]
                </div></div></td>
            <td class="listfilter" style="[{ $headStyle }]"><div class="r1"><div class="b1">
                [{ oxmultilang ident="GENERAL_DATE" }]
                </div></div></td>
            <td class="listfilter" style="[{ $headStyle }]"><div class="r1"><div class="b1">
                [{ oxmultilang ident="ARTICLE_STOCK_DAYS" }]
                </div></div></td>
            <td class="listfilter" style="[{ $headStyle }]"><div class="r1"><div class="b1">
                [{ oxmultilang ident="PAYMENT_MAIN_NAME" }]
                </div></div></td>
            <td class="listfilter" style="[{ $headStyle }]"><div class="r1"><div class="b1">
                [{ oxmultilang ident="USER_LIST_PLACE" }]
                </div></div></td>
            <td class="listfilter" style="[{ $headStyle }]"><div class="r1"><div class="b1">
                [{ oxmultilang ident="OXPROBS_ORDER_ITEMS" }]
                </div></div></td>
            <td class="listfilter" style="[{ $headStyle }]"><div class="r1"><div class="b1">
                [{ oxmultilang ident="OXPROBS_ORDER_SUM" }]
                </div></div></td>
            <td class="listfilter" style="[{ $headStyle }]"><div class="r1"><div class="b1">
                [{ oxmultilang ident="OXPROBS_ORDER_REMARK" }]
                </div></div></td>
            <td class="listfilter" style="[{ $headStyle }]"><div class="r1"><div class="b1">
                [{ oxmultilang ident="ORDER_MAIN_PAIDWITH" }]
                </div></div></td>
            [{*<td class="listfilter" style="[{ $headStyle }]"><div class="r1"><div class="b1">
                [{ oxmultilang ident="ORDER_LIST_MENUSUBITEM" }]
                </div></div></td>*}]
            <td class="listfilter" style="[{$headStyle}]" align="center"><div class="r1"><div class="b1">
                <input type="checkbox" onclick="change_all('oxprobs_oxid[]', this)">
                </div></div>
            </td>
        </tr>

        [{foreach name=outer item=Order from=$aOrders}]
            [{ cycle values="listitem,listitem2" assign="listclass" }]
            <tr>
                <td class="[{ $listclass }]" align="center"><a href="Javascript:editThis('[{$Order.oxid}]');">[{$Order.orderno}]</a></td>
                <td class="[{ $listclass }]" style="white-space:nowrap;"><a href="Javascript:editThis('[{$Order.oxid}]');">[{$Order.orderdate}]</a>&nbsp;</td>
                <td class="[{ $listclass }]" align="right"><a href="Javascript:editThis('[{$Order.oxid}]');">[{$Order.days}]</a>&nbsp;&nbsp;</td>
                <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Order.oxid}]');">[{$Order.name}]</a></td>
                <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Order.oxid}]');">[{$Order.custdeladdr}]</a></td>
                <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Order.oxid}]');">[{$Order.orderlist}]</a></td>
                <td class="[{ $listclass }]" align="right"><a href="Javascript:editThis('[{$Order.oxid}]');">[{$Order.ordersum|string_format:"%.2f"}]</a>&nbsp;&nbsp;</td>
                <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Order.oxid}]');">[{$Order.remark}]</a></td>
                <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Order.oxid}]');">[{$Order.paytype}]</a></td>
                <td class="[{$listclass}]" align="center"><input type="checkbox" name="oxprobs_oxid[]" value="[{$Order.oxid}]"></td>
            </tr>
        [{/foreach}]

        </table>
        
        <p>
        &nbsp;[{$aOrders|@count}] [{ oxmultilang ident="OXPROBS_NUMOF_ENTRIES" }]
        </p>
        
        </form>
        </div>
    </p>

</div>
