[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box=" "}]

<script type="text/javascript">
  if(parent.parent)
  {
    top.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
    top.sMenuItem    = "[{ oxmultilang ident="oxprobs_module" }]";
    top.sMenuSubItem = "[{ oxmultilang ident="oxprobs_pictures" }]";
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

</script>

<div class="center">
    <h1>[{ oxmultilang ident="oxprobs_pictures" }]</h1>
    <p>
        <form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
            [{ $shop->hiddensid }]
            <input type="hidden" name="oxid" value="[{ $oxid }]">
            <input type="hidden" name="cl" value="[{$editClassName}]">
            <input type="hidden" name="updatelist" value="1">
        </form>
    
        <form name="showprobs" id="showprobs" action="[{ $oViewConf->selflink }]" method="post">
        [{ $oViewConf->hiddensid }]
        <input type="hidden" name="cl" value="oxprobs_pictures">
        <input type="hidden" name="oxid" value="[{ $oxid }]">
        <input type="hidden" name="fnc" value="">
        <input type="hidden" name="sortcol" value="">
        <input type="hidden" name="language" value="[{ $actlang }]">
        <input type="hidden" name="editlanguage" value="[{ $actlang }]">
        
        <select name="oxprobs_reporttype" onchange="Javascript:document.showprobs.submit();">
            <option value="manu" [{if $ReportType == "manu"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_MANU_NOPICS" }]</option>
        </select>
        <input type="submit" value=" [{ oxmultilang ident="ORDER_MAIN_UPDATE_DELPAY" }] " />
    </p>
    <p style="background-color:#f0f0f0;"><div id="liste">
        [{if $ReportType == "manu"}]
            [{ oxmultilang ident="OXPROBS_MANU_NOPICS" }]
        [{elseif $ReportType == "invcats"}]
            [{ oxmultilang ident="OXPROBS_INVCATS_INFO" }]
        [{elseif $ReportType == "invman"}]
            [{ oxmultilang ident="OXPROBS_INVMAN_INFO" }]
        [{elseif $ReportType == "invven"}]
            [{ oxmultilang ident="OXPROBS_INVVEN_INFO" }]
        [{/if}]
        
        <table cellspacing="0" cellpadding="0" border="0" width="99%">
        <tr>
            [{ assign var="headStyle" value="border-bottom:1px solid #C8C8C8; font-weight:bold;" }]
            <td class="listfilter first" style="[{ $headStyle }]"><div class="r1"><div class="b1">
                [{ oxmultilang ident="ARTICLE_MAIN_TITLE" }]
                </div></div></td>
            <td class="listfilter" style="[{ $headStyle }]"><div class="r1"><div class="b1">
                [{ oxmultilang ident="USER_ARTICLE_QUANTITY" }]
                </div></div></td>
        </tr>

        [{foreach name=outer item=Item from=$aItems}]
            [{ cycle values="listitem,listitem2" assign="listclass" }]
            <tr>
                <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Item.oxid}]');">[{$Item.oxtitle}]</a></td>
                <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Item.oxid}]');">[{$Item.oxicon}]</a></td>
            </tr>
        [{/foreach}]

        </table>
        </form>
        </div>
    </p>

</div>
