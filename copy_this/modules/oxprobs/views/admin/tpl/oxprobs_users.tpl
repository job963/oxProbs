[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box=" "}]

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

</script>

<div class="center">
    <h1>[{ oxmultilang ident="oxprobs_users" }]</h1>
    <p>
        <form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
            [{ $shop->hiddensid }]
            <input type="hidden" name="oxid" value="[{ $oxid }]">
            <input type="hidden" name="cl" value="[{$editClassName}]">
            <input type="hidden" name="updatelist" value="1">
        </form>
    
        <form name="showprobs" id="showprobs" action="[{ $oViewConf->selflink }]" method="post">
        [{ $oViewConf->hiddensid }]
        <input type="hidden" name="cl" value="oxprobs_users">
        <input type="hidden" name="oxid" value="[{ $oxid }]">
        <input type="hidden" name="fnc" value="">
        <input type="hidden" name="sortcol" value="">
        <input type="hidden" name="language" value="[{ $actlang }]">
        <input type="hidden" name="editlanguage" value="[{ $actlang }]">
        
        <select name="oxprobs_reporttype" onchange="Javascript:document.showprobs.submit();">
            <option value="dblname" [{if $ReportType == "dblname"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_USRDBL_NAME" }]&nbsp;</option>
            <option value="dbladdr" [{if $ReportType == "dbladdr"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_USRDBL_ADDR" }]&nbsp;</option>
        </select>
        <input type="submit" value=" [{ oxmultilang ident="ORDER_MAIN_UPDATE_DELPAY" }] " />
    </p>
    <p style="background-color:#f0f0f0;">
        <div style="padding-bottom:5px;">
        [{if $ReportType == "dblname"}]
            [{ oxmultilang ident="OXPROBS_USRDBL_NAME_INFO" }]
        [{elseif $ReportType == "dbladdr"}]
            [{ oxmultilang ident="OXPROBS_USRDBL_ADDR_INFO" }]
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
                [{ oxmultilang ident="OXPROBS_LOGINS" }]
                </div></div></td>
        </tr>

        [{foreach name=outer item=User from=$aUsers}]
            [{ cycle values="listitem,listitem2" assign="listclass" }]
            <tr>
                <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$User.oxid}]');">[{$User.name}]</a></td>
                <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$User.oxid}]');">[{$User.amount}]</a></td>
                [{ assign var="errCodes" value="|"|explode:$Group.status }]
                <td class="[{ $listclass }]">
                    [{foreach name=inner item=Login from=$User.logins}]
                        <span style="background-color:#e0e0e0;color:#f00000;border-radius:4px;border:1px solid #c8c8c8;"><nobr>
                        [{if $Login.oxactive==0 }]
                            [{*<div style="font-size:1.5;font-weight:bold;color:#ffffff;background-color:#f00000;border-radius:6px;height:12px;width:12px;text-align:center;vertical-align:bottom;display:inline-block;">*}]
                                <span style="font-size:1.2em;font-weight:bold;color:#f00000;" title="[{ oxmultilang ident="OXPROBS_DEACT_USER" }]">&nbsp;x</span>
                            [{*</div>*}]
                        [{else}]
                                <span style="font-size:1.2em;font-weight:bold;color:#f00000;">&nbsp;</span>
                        [{/if}]
                        [{if $Login.oxdboptin==1 }]
                            <span style="font-size:1.2em;font-weight:bold;color:#00d000;" title="[{ oxmultilang ident="OXPROBS_CONF_NEWS" }]">N</span>
                        [{elseif $Login.oxdboptin==2 }]
                            <span style="font-size:1.2em;font-weight:bold;color:#c0c000;" title="[{ oxmultilang ident="OXPROBS_UNCONF_NEWS" }]">N</span>
                        [{/if}]
                        <a href="Javascript:editThis('[{$Login.oxid}]');">[{$Login.oxusername}]</a>&nbsp;
                        </nobr></span> &nbsp;
                    [{/foreach}]
                </td>
            </tr>
        [{/foreach}]

        </table>
        </form>
        </div>
    </p>

</div>
