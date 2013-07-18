[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box=" "}]

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
</script>

<div class="center">
    <h1>[{ oxmultilang ident="oxprobs_displayarticles" }]</h1>
	
    [{ if $sqlErrNo != 0 }]
        <div style="border:2px solid #dd0000;padding:3px;background-color:#ffdddd;">
            SQL-Error [{$sqlErrNo}]: [{$sqlErrMsg}]
        </div>
    [{/if}]
	
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
        
        <select name="oxprobs_reporttype" onchange="this.form.submit()">
            <option value="nostock" [{if $ReportType == "nostock"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOSTOCK" }]&nbsp;</option>
            <option value="noartnum" [{if $ReportType == "noartnum"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOARTNUM" }]&nbsp;</option>
            <option value="noshortdesc" [{if $ReportType == "noshortdesc"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOSHORTDESC" }]&nbsp;</option>
            <option value="nopic" [{if $ReportType == "nopic"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOPIC" }]&nbsp;</option>
            <option value="dblactive" [{if $ReportType == "dblactive"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_DBLACTIVE" }]&nbsp;</option>
            <option value="longperiod" [{if $ReportType == "longperiod"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_LONGPERIOD" }]&nbsp;</option>
            <option value="invperiod" [{if $ReportType == "invperiod"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_INVPERIOD" }]&nbsp;</option>
            <option value="noprice" [{if $ReportType == "noprice"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOPRICE" }]&nbsp;</option>
            <option value="nobuyprice" [{if $ReportType == "nobuyprice"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOBUYPRICE" }]&nbsp;</option>
            <option value="noean" [{if $ReportType == "noean"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOEAN" }]&nbsp;</option>
            <option value="eanchk" [{if $ReportType == "eanchk"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_EANCHK" }]&nbsp;</option>
            <option value="nompn" [{if $ReportType == "nompn"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOMPN" }]&nbsp;</option>
            <option value="nocat" [{if $ReportType == "nocat"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOCAT" }]&nbsp;</option>
            <option value="orphan" [{if $ReportType == "orphan"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_ORPHAN" }]&nbsp;</option>
            <option value="nodesc" [{if $ReportType == "nodesc"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NODESC" }]&nbsp;</option>
            <option value="nomanu" [{if $ReportType == "nomanu"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOMANU" }]&nbsp;</option>
            <option value="novend" [{if $ReportType == "novend"}]selected[{/if}]>[{ oxmultilang ident="OXPROBS_NOVEND" }]&nbsp;</option>
        </select>
        <input type="submit" value=" [{ oxmultilang ident="ORDER_MAIN_UPDATE_DELPAY" }] " />
    </p>
    <p style="background-color:#f0f0f0;">
        <div style="padding-bottom:5px;">
        [{if $ReportType == "nostock"}]
            [{ oxmultilang ident="OXPROBS_NOSTOCK_INFO" }]
        [{elseif $ReportType == "noartnum"}]
            [{ oxmultilang ident="OXPROBS_NOARTNUM_INFO" }]
        [{elseif $ReportType == "noshortdesc"}]
            [{ oxmultilang ident="OXPROBS_NOSHORTDESC_INFO" }]
        [{elseif $ReportType == "nopic"}]
            [{ oxmultilang ident="OXPROBS_NOPIC_INFO" }]
        [{elseif $ReportType == "dblactive"}]
            [{ oxmultilang ident="OXPROBS_DBLACTIVE_INFO" }]
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
        [{/if}]
        </div>
        
        <div id="liste">
        <table cellspacing="0" cellpadding="0" border="0" width="99%">
        <tr>
            <td class="listfilter first"><div class="r1"><div class="b1">
                <input class="listedit" type="text" size="15" maxlength="128" name="where[oxartnum]" value="[{ $aWhere.oxartnum }]">
                </div></div></td>
            <td class="listfilter"><div class="r1"><div class="b1">
                <input class="listedit" type="text" size="15" maxlength="128" name="where[oxtitle]" value="[{ $aWhere.oxtitle }]">
                </div></div></td>
            [{if $ReportType == "noshortdesc"}]
                <td class="listfilter"><div class="r1"><div class="b1">
                <input class="listedit" type="text" size="15" maxlength="128" name="where[oxshortdesc]" value="[{ $aWhere.oxshortdesc }]">
                </div></div></td>
            [{/if}]
            <td class="listfilter"><div class="r1"><div class="b1">
                <input class="listedit" type="text" size="15" maxlength="128" name="where[oxvarselect]" value="[{ $aWhere.oxvarselect }]">
                </div></div></td>
            [{if $ReportType != "noshortdesc" and $ReportType != "longperiod" and $ReportType != "invperiod" }]
                <td class="listfilter"><div class="r1"><div class="b1">
                <input class="listedit" type="text" size="15" maxlength="128" name="where[oxean]" value="[{ $aWhere.oxean }]">
                </div></div></td>
            [{/if}]
            <td class="listfilter"><div class="r1"><div class="b1">
                <input class="listedit" type="text" size="15" maxlength="128" name="where[oxmantitle]" value="[{ $aWhere.oxmantitle }]">
                </div></div></td>
            [{if $ReportType == "longperiod" or $ReportType == "invperiod"  }]
                <td class="listfilter"><div class="r1"><div class="b1">
                [{* oxmultilang ident="GENERAL_ARTICLE_OXACTIVEFROM" *}]
                </div></div></td>
                <td class="listfilter"><div class="r1"><div class="b1">
                [{* oxmultilang ident="GENERAL_ARTICLE_OXACTIVETO" *}]
                </div></div></td>
            [{/if}]
            [{if $ReportType != "noshortdesc" and  $ReportType != "longperiod" and $ReportType != "invperiod" }]
                <td class="listfilter"><div class="r1"><div class="b1">
                [{* oxmultilang ident="GENERAL_VENDOR" }] [{ oxmultilang ident="ARTICLE_MAIN_ARTNUM" *}]
                </div></div></td>
            [{/if}]
            <td class="listfilter"><div class="r1"><div class="b1">
                [{if $ReportType == "nobuyprice"}]
                    [{* oxmultilang ident="ARTICLE_EXTEND_BPRICE" *}]
                [{else}]
                    [{* oxmultilang ident="GENERAL_ARTICLE_OXSTOCK" *}]
                [{/if}]
            
                </div></div></td>
            <td class="listfilter"><div class="r1"><div class="b1"><div class="find">
                <input class="listedit" type="submit" name="submitit" value="[{ oxmultilang ident="GENERAL_SEARCH" }]">
                </div></div></div></td>
        </tr>
        <tr>
            [{if $sortopt=='ASC'}]
                [{assign var="sorticon" value="&nbsp;&nbsp;&blacktriangle;"}]
            [{else}]
                [{assign var="sorticon" value="&nbsp;&nbsp;&blacktriangledown;"}]
            [{/if}]
            <td class="listheader first">
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
            [{if $ReportType != "noshortdesc" and  $ReportType != "longperiod" and $ReportType != "invperiod" }]
                <td class="listheader">
                [{ oxmultilang ident="GENERAL_VENDOR" }] [{ oxmultilang ident="ARTICLE_MAIN_ARTNUM" }]
                </td>
            [{/if}]
            <td class="listheader">
                [{if $ReportType == "nobuyprice"}]
                    [{ oxmultilang ident="ARTICLE_EXTEND_BPRICE" }]
                [{else}]
                    [{ oxmultilang ident="GENERAL_ARTICLE_OXSTOCK" }]
                [{/if}]
            
                </td>
            <td class="listheader">
                [{ oxmultilang ident="GENERAL_ARTICLE_OXPRICE" }]
                </td>
        </tr>

        [{foreach name=outer item=Article from=$aArticles}]
            [{ cycle values="listitem,listitem2" assign="listclass" }]
            [{*<tr class="[{cycle values="even,odd"}]">*}]
            <tr>
                <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Article.oxid}]');">[{$Article.oxartnum}]</a></td>
                <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Article.oxid}]');">[{$Article.oxtitle}]</a></td>
                [{if $ReportType == "noshortdesc"}]
                    <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Article.oxid}]');">[{$Article.oxshortdesc}]</a></td>
                [{/if}]
                <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Article.oxid}]');">[{$Article.oxvarselect}]</a></td>
                [{if $ReportType != "noshortdesc" and $ReportType != "longperiod" and $ReportType != "invperiod" }]
                    <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Article.oxid}]');">[{$Article.oxean}]</a></td>
                [{/if}]
                <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Article.oxid}]');">[{$Article.oxmantitle}]</a></td>
                [{if $ReportType == "longperiod" or $ReportType == "invperiod"  }]
                    <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Article.oxid}]');">[{$Article.oxactivefrom}]</a></td>
                    <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Article.oxid}]');">[{$Article.oxactiveto}]</a></td>
                [{/if}]
                [{if $ReportType != "noshortdesc" and $ReportType != "longperiod" and $ReportType != "invperiod" }]
                    <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Article.oxid}]');">[{$Article.oxmpn}]</a></td>
                [{/if}]
                <td class="[{ $listclass }]">
                    <a href="Javascript:editThis('[{$Article.oxid}]');">
                    [{if $ReportType == "nobuyprice"}]
                        [{$Article.oxbprice|string_format:"%.2f"}]
                    [{else}]
                        [{$Article.oxstock}]
                    [{/if}]</a>
                </td>
                <td class="[{ $listclass }]" align="right"><a href="Javascript:editThis('[{$Article.oxid}]');">[{$Article.oxprice|string_format:"%.2f"}]</a></td>
            </tr>
        [{/foreach}]

        </table>
        </form>
        </div>
    </p>

</div>
