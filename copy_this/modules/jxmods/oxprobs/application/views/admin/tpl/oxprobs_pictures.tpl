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
    top.sMenuSubItem = "[{ oxmultilang ident="oxprobs_pictures" }]";
    top.sWorkArea    = "[{$_act}]";
    top.setTitle();
  }

function editThis( sID, sClass )
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
    [{* oTransfer.cl.value='[{$editClassName}]'; *}]
    oTransfer.cl.value=sClass;
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
    <h1>[{ oxmultilang ident="oxprobs_pictures" }]</h1>
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
        <input type="hidden" name="cl" value="oxprobs_pictures">
        <input type="hidden" name="oxid" value="[{ $oxid }]">
        <input type="hidden" name="fnc" value="">
        <input type="hidden" name="sortcol" value="">
        <input type="hidden" name="language" value="[{ $actlang }]">
        <input type="hidden" name="editlanguage" value="[{ $actlang }]">
        
        <select name="oxprobs_reporttype" onchange="document.forms['showprobs'].elements['fnc'].value='';document.showprobs.submit();">
            <optgroup label="[{ oxmultilang ident="OXPROBS_GROUP_MISSED" }]">
                <option value="manumisspics" [{if $ReportType == "manumisspics"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_MANU_MISSPICS" }]&nbsp;</option>
                <option value="vendmisspics" [{if $ReportType == "vendmisspics"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_VEND_MISSPICS" }]&nbsp;</option>
                <option value="catmisspics" [{if $ReportType == "catmisspics"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_CAT_MISSPICS" }]&nbsp;</option>
                <option value="artmisspics" [{if $ReportType == "artmisspics"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_ART_MISSPICS" }]&nbsp;</option>
            </optgroup>
            <optgroup label="[{ oxmultilang ident="OXPROBS_GROUP_ORPHANED" }]">
                <option value="manuorphpics" [{if $ReportType == "manuorphpics"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_MANU_ORPHPICS" }]&nbsp;</option>
                <option value="vendorphpics" [{if $ReportType == "vendorphpics"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_VEND_ORPHPICS" }]&nbsp;</option>
                <option value="catorphpics" [{if $ReportType == "catorphpics"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_CAT_ORPHPICS" }]&nbsp;</option>
                <option value="artorphpics" [{if $ReportType == "artorphpics"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_ART_ORPHPICS" }]&nbsp;</option>
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
        [{if $ReportType == "manumisspics"}]
            [{ oxmultilang ident="OXPROBS_MANU_MISSPICS_INFO" }]
            [{ assign var="editClass" value="manufacturer" }] [{ $pictureDir }]
        [{elseif $ReportType == "manuorphpics"}]
            [{ oxmultilang ident="OXPROBS_MANU_ORPHPICS_INFO" }] [{ $pictureDir }]
            [{ assign var="editClass" value="manufacturer" }]
        [{elseif $ReportType == "vendmisspics"}]
            [{ oxmultilang ident="OXPROBS_VEND_MISSPICS_INFO" }] [{ $pictureDir }]
            [{ assign var="editClass" value="vendor" }]
        [{elseif $ReportType == "vendorphpics"}]
            [{ oxmultilang ident="OXPROBS_VEND_ORPHPICS_INFO" }] [{ $pictureDir }]
            [{ assign var="editClass" value="vendor" }]
        [{elseif $ReportType == "catmisspics"}]
            [{ oxmultilang ident="OXPROBS_CAT_MISSPICS_INFO" }] [{ $pictureDir }]
            [{ assign var="editClass" value="category" }]
        [{elseif $ReportType == "catorphpics"}]
            [{ oxmultilang ident="OXPROBS_CAT_ORPHPICS_INFO" }] [{ $pictureDir }]
            [{ assign var="editClass" value="category" }]
        [{elseif $ReportType == "artmisspics"}]
            [{ oxmultilang ident="OXPROBS_ART_MISSPICS_INFO" }] [{ $pictureDir }]
            [{ assign var="editClass" value="article" }]
        [{elseif $ReportType == "artorphpics"}]
            [{ oxmultilang ident="OXPROBS_ART_ORPHPICS_INFO" }] [{ $pictureDir }]
            [{ assign var="editClass" value="article" }]
        [{/if}]
        </div>
        
        <div id="liste">
        <table cellspacing="0" cellpadding="0" border="0" width="99%">
        <tr>
            [{ assign var="headStyle" value="border-bottom:1px solid #C8C8C8; font-weight:bold;" }]
            [{if $ReportType == "manumisspics" || $ReportType == "vendmisspics" || $ReportType == "catmisspics" || $ReportType == "artmisspics" }]
                <td valign="top" class="listfilter first" style="[{ $headStyle }]" height="15" width="30" align="center">
                    <div class="r1"><div class="b1">[{ oxmultilang ident="GENERAL_ACTIVTITLE" }]</div></div>
                </td>
                <td class="listfilter" style="[{ $headStyle }]"><div class="r1"><div class="b1">
                    [{ oxmultilang ident="ARTICLE_MAIN_TITLE" }]
                    </div></div>
                </td>
            [{/if}]
                <td class="listfilter" style="[{ $headStyle }]"><div class="r1"><div class="b1">
                    [{ oxmultilang ident="OXPROBS_SUBDIR_FILE" }]
                    </div></div>
                </td>
            <td class="listfilter" style="[{ $headStyle }]"><div class="r1"><div class="b1">
                [{ oxmultilang ident="OXPROBS_STATE" }]
                </div></div>
            </td>
            <td class="listfilter" style="[{$headStyle}]" align="center"><div class="r1"><div class="b1">
                <input type="checkbox" onclick="change_all('oxprobs_oxid[]', this)">
                </div></div>
            </td>
        </tr>

        [{foreach name=outer item=Item from=$aItems}]
            [{ cycle values="listitem,listitem2" assign="listclass" }]
            <tr>
                [{if $ReportType == "manumisspics" || $ReportType == "vendmisspics" || $ReportType == "catmisspics" || $ReportType == "artmisspics" }]
                    <td valign="top" class="[{ $listclass}][{ if $Item.oxactive == 1}] active[{/if}]" height="15">
                        <div class="listitemfloating">&nbsp</a></div>
                    </td>
                    <td class="[{ $listclass }]">
                        <a href="Javascript:editThis('[{$Item.oxid}]','[{$editClass}]');">[{$Item.oxtitle}]</a>
                    </td>
                [{/if}]
                [{if $ReportType == "manumisspics" || $ReportType == "vendmisspics" || $ReportType == "catmisspics" || $ReportType == "artmisspics" }]
                    <td class="[{ $listclass }]">
                        <a href="Javascript:editThis('[{$Item.oxid}]','[{$editClass}]');">
                        [{$Item.subdir}]/[{if $Item.picname!=""}][{/if}][{$Item.picname}]
                        </a>
                    </td>
                [{elseif $ReportType == "manuorphpics" || $ReportType == "vendorphpics" || $ReportType == "catorphpics" || $ReportType == "artorphpics" }]
                    <td class="[{ $listclass }]">
                         <a class="thumbnail" href="#thumb">
                            <img src="[{$pictureUrl}]/[{$Item.subdir}]/[{$Item.picname}]" style="max-height:28px;width:auto;"/>
                            [{$Item.subdir}]/[{$Item.picname}]<span><img src="[{$pictureUrl}]/[{$Item.subdir}]/[{$Item.picname}]" /></span>
                        </a>
                    </td>
                [{/if}]
                <td class="[{ $listclass }]">[{ oxmultilang ident=$Item.status }]</td>
                <td class="[{$listclass}]" align="center"><input type="checkbox" name="oxprobs_oxid[]" value="[{$Item.oxid}]"></td>
            </tr>
        [{/foreach}]

        </table>
        
        <p>
        &nbsp;[{$aItems|@count}] [{ oxmultilang ident="OXPROBS_NUMOF_ENTRIES" }]
        </p>
        
        </form>
        </div>
        <div style="height:50px;">
            &nbsp;
        </div>
    </p>

</div>
