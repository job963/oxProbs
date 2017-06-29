<?php

/*
 *    This file is part of the module oxProbs for OXID eShop Community Edition.
 *
 *    The module oxProbs for OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    The module oxProbs for OXID eShop Community Edition is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      https://github.com/job963/oxProbs
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @copyright (C) Joachim Barthel 2012-2017
 *
 */
 
class oxprobs_articles extends oxAdminView
{
    protected $_sThisTemplate = "oxprobs_articles.tpl";
    
    /**
     * 
     * @return type
     */
    public function render()
    {
        parent::render();
        $myConfig = oxRegistry::get("oxConfig");
        
        $aIncFiles = array();
        $aIncReports = array();
        $aIncFiles = $myConfig->getConfigParam( 'aOxProbsArticleIncludeFiles' );
        $sIncPath = $this->jxGetModulePath() . '/application/controllers/admin/';
        if (count($aIncFiles) > 0) {
            foreach ($aIncFiles as $sIncFile) { 
                $sIncFile = $sIncPath . 'oxprobs_articles_' . $sIncFile . '.inc.php';
                require $sIncFile;
            }
        }
        
        $cReportType = $this->getConfig()->getRequestParameter( 'oxprobs_reporttype' );
        if (empty($cReportType))
            $cReportType = "nostock";
        $this->_aViewData["ReportType"] = $cReportType;
        $bCustomColumn = FALSE;
        foreach ($aIncReports as $aIncReport) {
            if ( in_array($cReportType,$aIncReport) ) {
                $bCustomColumn = TRUE;
                $aColumnTitles = $aIncReport['extcol'];
            }
        }

        $aArticles = array();
        $aArticles = $this->_retrieveData();
        
        $oModule = oxNew('oxModule');
        $oModule->load('oxprobs');
        $this->_aViewData["sModuleId"] = $oModule->getId();
        $this->_aViewData["sModuleVersion"] = $oModule->getInfo('version');
        $this->_aViewData["sIsoLang"] = oxRegistry::getLang()->getLanguageAbbr($iLang);

        $this->_aViewData["aArticles"] = $aArticles;
        $this->_aViewData["aIncReports"] = $aIncReports;
        $this->_aViewData["bCustomColumn"] = $bCustomColumn;
        $this->_aViewData["aColumnTitles"] = $aColumnTitles;
        $this->_aViewData["aWhere"] = $aWhere;
        $this->_aViewData["sortcol"] = $sortCol;
        $this->_aViewData["sortopt"] = $sortOpt;

        return $this->_sThisTemplate;
    }
     
    
    /**
    
  
     * @return \type  * @return type * 
     */
    public function downloadResult()
    {
        $myConfig = oxRegistry::get("oxConfig");
        switch ( $myConfig->getConfigParam( 'sOxProbsSeparator' ) ) {
            case 'comma':
                $sSep = ',';
                break;
            case 'semicolon':
                $sSep = ';';
                break;
            case 'tab':
                $sSep = chr(9);
                break;
            case 'pipe':
                $sSep = '|';
                break;
            case 'tilde':
                $sSep = '~';
                break;
            default:
                $sSep = ',';
                break;
        }
        if ( $myConfig->getConfigParam( 'bOxProbsQuote' ) ) {
            $sBegin = '"';
            $sSep   = '"' . $sSep . '"';
            $sEnd   = '"';
        }

        $aArticles = array();
        $aArticles = $this->_retrieveData();
        
        if ( $myConfig->getConfigParam( 'bOxProbsStripTags' ) ) {
            $aArticles = $this->_stripTagsFromData($aArticles);
        }
        
        $aArticles = $this->_decodeHtmlSpecialChars($aArticles);

        $aSelOxid = $this->getConfig()->getRequestParameter( 'oxprobs_oxid' ); 
        if(!$aSelOxid){
            return;
        }
        
        $sContent = '';
        if ( $myConfig->getConfigParam("bOxProbsHeader") ) {
            $aHeader = array_keys($aArticles[0]);
            $sContent .= $sBegin . implode($sSep, $aHeader) . $sEnd . chr(13);
        }
        foreach ($aArticles as $aArticle) {
            if ( in_array($aArticle['oxid'], $aSelOxid) ) {
                $sContent .= $sBegin . implode($sSep, $aArticle) . $sEnd . chr(13);
            }
        }

        header("Content-Type: text/plain");
        header("content-length: ".strlen($sContent));
        header("Content-Disposition: attachment; filename=\"problem-report.csv\"");
        echo $sContent;
        
        exit();

        return;
    }

    
    /**
     * 
     */
    private function _retrieveData()
    {
        
        $myConfig = oxRegistry::get("oxConfig");
        $this->ean           = $myConfig->getConfigParam("sOxProbsEANField");
        $this->minDescLen    = (int) $myConfig->getConfigParam("sOxProbsMinDescLen");
        $this->bpriceMin     = (float) $myConfig->getConfigParam("sOxProbsBPriceMin");
        $this->maxActionTime = (int) $myConfig->getConfigParam("sOxProbsMaxActionTime");

        $sWhere = "";
        
        if ( is_array( $aWhere = $this->getConfig()->getRequestParameter( 'where' ) ) ) {

            $aKeys = array_keys($aWhere);

            if ($aWhere['oxartnum'] != '')
                $sWhere .= "AND a.oxartnum LIKE '%".$aWhere['oxartnum']."%' ";
            if ($aWhere['oxtitle'] != '')
                $sWhere .= "AND IF(a.oxparentid = '', a.oxtitle, (SELECT b.oxtitle FROM oxarticles b WHERE b.oxid = a.oxparentid)) LIKE '%".$aWhere['oxtitle']."%' ";
            if ($aWhere['oxshortdesc'] != '')
                $sWhere .= "AND IF(a.oxparentid = '', a.oxshortdesc, (SELECT b.oxshortdesc FROM oxarticles b WHERE b.oxid = a.oxparentid)) LIKE '%".$aWhere['oxshortdesc']."%' ";
            if ($aWhere['oxvarselect'] != '')
                $sWhere .= "AND IF(a.oxvarselect = '', '-', a.oxvarselect) LIKE '%".$aWhere['oxvarselect']."%' ";
            if ($aWhere['oxean'] != '')
                $sWhere .= "AND IF(a.oxparentid = '', a.$this->ean, (SELECT b.$this->ean FROM oxarticles b WHERE b.oxid = a.oxparentid)) LIKE '%".$aWhere['oxean']."%' ";
            if ($aWhere['oxmantitle'] != '')
                $sWhere .= "HAVING IF(oxparentid = '', oxmantitle, (SELECT oxmantitle FROM oxarticles b WHERE b.oxid = a.oxparentid)) LIKE '%".$aWhere['oxmantitle']."%' ";
        }

        if ( is_string($this->_aViewData["oViewConf"]->getActiveShopId()) ) { 
            // This is a CE or PE Shop
            $sShopId = $this->_aViewData["oViewConf"]->getActiveShopId();
            $sWhere = $sWhere . " AND a.oxshopid = '$sShopId' ";
        }
        else {
            // This is a EE Shop
            $iShopId = $this->_aViewData["oViewConf"]->getActiveShopId();
            $sWhere = $sWhere . " AND a.oxshopid = $iShopId ";
        }

        if ( $myConfig->getConfigParam("bOxProbsProductActiveOnly") ) {
            if ( $myConfig->getConfigParam("bOxProbsProductTimeActive") ){
                $sWhereActive = "AND (a.oxactive = 1 OR (NOW()>=a.oxactivefrom AND NOW() <=a.oxactiveto)) ";
            }
            else {
                $sWhereActive .= "AND a.oxactive = 1 ";
            }
        }
        else {
            $sWhereActive = "";
        }
        
        $sortCol = $this->getConfig()->getRequestParameter( 'sortcol' );
        if (empty($sortCol))
            $sortCol = 'oxartnum';

        $lastSortCol = $this->getConfig()->getRequestParameter( 'lastsortcol' );
        $lastSortOpt = $this->getConfig()->getRequestParameter( 'lastsortopt' );
        if ($sortCol == $lastSortCol)
            $sortOpt = ($lastSortOpt == 'ASC') ? 'DESC' : 'ASC';
        else 
            $sortOpt = 'ASC';
        
        if ($sortCol == 'oxartnum')
            $sSort = "a.oxartnum $sortOpt";
        if ($sortCol == 'oxtitle')
            $sSort = "IF(a.oxparentid = '', a.oxtitle, (SELECT b.oxtitle FROM oxarticles b where b.oxid = a.oxparentid)), IF(a.oxvarselect = '', '-', a.oxvarselect) $sortOpt";
        if ($sortCol == 'oxshortdesc')
            $sSort = "IF(a.oxparentid = '', a.oxshortdesc, (SELECT b.oxshortdesc FROM oxarticles b where b.oxid = a.oxparentid)), IF(a.oxvarselect = '', '-', a.oxvarselect) $sortOpt";
        if ($sortCol == 'oxvarselect')
            $sSort = "IF(a.oxvarselect = '', '-', a.oxvarselect), IF(a.oxparentid = '', a.oxtitle, (SELECT b.oxtitle FROM oxarticles b where b.oxid = a.oxparentid)) $sortOpt";
        if ($sortCol == 'oxean')
            $sSort = "IF(a.oxparentid = '', a.$this->ean, (SELECT b.$this->ean FROM oxarticles b where b.oxid = a.oxparentid)), IF(a.oxvarselect = '', '-', a.oxvarselect) $sortOpt";
        if ($sortCol == 'oxmantitle')
            $sSort = "oxmantitle $sortOpt";

        $cReportType = $this->getConfig()->getRequestParameter( 'oxprobs_reporttype' );
        if (empty($cReportType))
            $cReportType = "nostock";
        
        $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'master/product';
        $sIconCol1 = "IF(a.oxicon!='',"
                        . "CONCAT('{$sPictureUrl}/icon/',a.oxicon),"
                        . "IF(a.oxpic1!='',CONCAT('{$sPictureUrl}/1/',a.oxpic1),'')) "
                        . "AS picname";
        $sIconCol2 = "(SELECT "
                        . "IF(b.oxicon!='',"
                            . "CONCAT('{$sPictureUrl}/icon/',b.oxicon),"
                            . "IF(b.oxpic1!='',CONCAT('{$sPictureUrl}/1/',b.oxpic1),'')) "
                        . "FROM oxarticles b "
                        . "WHERE a.oxparentid = b.oxid) "
                        . "AS picname ";

        if ( $myConfig->getConfigParam("bOxProbsProductTimeActive") ) {
            $sActive = "IF(a.oxactive=1,a.oxactive,IF(NOW()>=a.oxactivefrom AND NOW() <=a.oxactiveto,2,0)) AS oxactive ";
        }
        else {
            $sActive = "a.oxactive AS oxactive ";
        }
        
        switch ($cReportType) {
            case 'nostock':
            case 'missstockinfo':
            case 'stockalert':
                if ($cReportType == 'nostock') {
                    $sStockCond = "a.oxstock <= 0";
                    $txtStandard = oxRegistry::getLang()->translateString( "OXPROBS_STOCK_STANDARD" );
                    $txtOffline = oxRegistry::getLang()->translateString( "OXPROBS_STOCK_OFFLINE" );
                    $txtNotBuyable = oxRegistry::getLang()->translateString( "OXPROBS_STOCK_NOTBUYABLE" );
                    $sStock = "CONCAT( '<span class=\"emphasize\">', a.oxstock, ' &ndash; ', "
                            . "IF(a.oxstockflag=1, '<span class=\"stockStandard\">{$txtStandard}</span>', "
                            . "IF(a.oxstockflag=2, '<span class=\"stockOffline\">{$txtOffline}</span>', "
                            . "IF(a.oxstockflag=3, '<span class=\"stockNotBuyable\">{$txtNotBuyable}</span>',''))), ' </span>', "
                            . "IF(a.oxparentid!='' AND a.oxnostocktext='',(SELECT CONCAT('<span style=\"white-space:nowrap;\">',d.oxnostocktext,'</span>') AS oxnostocktext FROM oxarticles d WHERE  a.oxparentid = d.oxid), a.oxnostocktext) ) ";
                            //. "IF(a.oxparentid!='' AND (SELECT d.oxnostocktext FROM oxarticles d WHERE  a.oxparentid = d.oxid)!='',(SELECT CONCAT('<span style=\"white-space:nowrap;\">',d.oxnostocktext,'</span>') AS oxnostocktext FROM oxarticles d WHERE  a.oxparentid = d.oxid), a.oxnostocktext) ) ";
                }
                elseif ($cReportType == 'missstockinfo') {
                    $sStockCond = "a.oxstock <= 0 AND a.oxstockflag = 1 AND IF(a.oxparentid!='' AND a.oxnostocktext='', (SELECT d.oxnostocktext FROM oxarticles d WHERE a.oxparentid = d.oxid), a.oxnostocktext) = ''";
                    $sStock = "CONCAT( '<span class=\"emphasize\">', a.oxstock,'</span>' )";
                }
                else {
                    $sStockCond = "a.oxremindactive = 1 AND a.oxstock < a.oxremindamount";
                    $sStock = "CONCAT( '<span class=\"emphasize\">', a.oxstock,'</span> (',a.oxremindamount,')' )";
                }
                
                $sSql1 = "SELECT a.oxid AS oxid, $sActive , a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmpn, "
                        . "m.oxtitle AS oxmantitle, a.oxtitle AS oxtitle, a.oxvarselect AS oxvarselect, $sStock AS oxstock, a.oxprice AS oxprice, $sIconCol1 "
                        . "FROM oxarticles a "
                        . "LEFT JOIN oxmanufacturers m ON a.oxmanufacturerid = m.oxid "
                        . "WHERE $sStockCond "
                            //. "AND a.oxactive = 1 "
                            //. "AND a.oxstockflag = 1 "
                            . "AND a.oxstockflag IN (1,2,3) "
                            . "AND a.oxvarcount = 0 "
                            . "AND a.oxparentid = '' "
                            . $sWhereActive
                            . $sWhere;
                        
                $sSql2 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmnp, "
                        . "( "
                            . "SELECT m.oxtitle "
                            . "FROM oxarticles c "
                            . "LEFT JOIN oxmanufacturers m ON c.oxmanufacturerid = m.oxid "
                            . "WHERE a.oxparentid = c.oxid "
                        . ") AS oxmantitle, "
                        . "( "
                            . "SELECT b.oxtitle "
                            . "FROM oxarticles b "
                            . "WHERE a.oxparentid = b.oxid "
                        . ") AS oxtitle, "
                        . "a.oxvarselect AS oxvarselect, $sStock AS oxstock, a.oxprice AS oxprice, $sIconCol2 "
                        . "FROM oxarticles a "
                        . "WHERE $sStockCond "
                            //. "AND a.oxactive = 1 "
                            //. "AND a.oxstockflag = 1 "
                            . "AND a.oxstockflag IN (1,2,3) "
                            . "AND a.oxparentid != '' "
                            . $sWhereActive
                            . $sWhere;
                break;

            case 'noreminder':
                $sSql1 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmpn, "
                        . "m.oxtitle AS oxmantitle, a.oxtitle AS oxtitle, a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol1 "
                        . "FROM oxarticles a "
                        . "LEFT JOIN oxmanufacturers m ON a.oxmanufacturerid = m.oxid "
                        . "WHERE a.oxremindactive = 0 "
                            //. "AND a.oxactive = 1 "
                            . "AND a.oxvarcount = 0 "
                            . "AND a.oxparentid = '' "
                            . $sWhereActive
                            . $sWhere;
                $sSql2 = "";
                break;

            case 'noremindvalue':
                $sSql1 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmpn, "
                        . "m.oxtitle AS oxmantitle, a.oxtitle AS oxtitle, a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol1 "
                        . "FROM oxarticles a "
                        . "LEFT JOIN oxmanufacturers m ON a.oxmanufacturerid = m.oxid "
                        . "WHERE a.oxremindactive = 1 AND a.oxremindamount = 0 "
                            //. "AND a.oxactive = 1 "
                            . "AND a.oxvarcount = 0 "
                            . "AND a.oxparentid = '' "
                            . $sWhereActive
                            . $sWhere;
                $sSql2 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmnp, "
                        . "( "
                            . "SELECT m.oxtitle "
                            . "FROM oxarticles c "
                            . "LEFT JOIN oxmanufacturers m ON c.oxmanufacturerid = m.oxid "
                            . "WHERE a.oxparentid = c.oxid "
                        . ") AS oxmantitle, "
                        . "( "
                            . "SELECT b.oxtitle "
                            . "FROM oxarticles b "
                            . "WHERE a.oxparentid = b.oxid "
                        . ") AS oxtitle, "
                        . "a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol2 "
                        . "FROM oxarticles a "
                        . "WHERE a.oxremindactive = 1 AND a.oxremindamount = 0 "
                            //. "AND a.oxactive = 1 "
                            . "AND a.oxparentid != '' "
                            . $sWhereActive
                            . $sWhere;
                break;

            case 'noartnum':
                $sSql1 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmpn, "
                        . "m.oxtitle AS oxmantitle, a.oxtitle AS oxtitle, a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol1 "
                        . "FROM oxarticles a "
                        . "LEFT JOIN oxmanufacturers m ON a.oxmanufacturerid = m.oxid "
                        . "WHERE a.oxartnum = '' "
                            //. "AND a.oxactive = 1 "
                            //. 'AND a.oxstockflag = 1 '
                            . "AND a.oxvarcount = 0 "
                            . "AND a.oxparentid = '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                $sSql2 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmnp, "
                        . "( "
                            . "SELECT m.oxtitle "
                            . "FROM oxarticles c "
                            . "LEFT JOIN oxmanufacturers m ON c.oxmanufacturerid = m.oxid "
                            . "WHERE a.oxparentid = c.oxid "
                        . ") AS oxmantitle, "
                        . "( "
                            . "SELECT b.oxtitle "
                            . "FROM oxarticles b "
                            . "WHERE a.oxparentid = b.oxid "
                        . ") AS oxtitle, "
                        . "a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol2 "
                        . "FROM oxarticles a "
                        . "WHERE a.oxartnum = '' "
                            //. "AND a.oxactive = 1 "
                            //. 'AND a.oxstockflag = 1 '
                            . "AND a.oxparentid != '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                break;

            case 'noshortdesc':
                $sSql1 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmnp, "
                        . "CONCAT('<span class=\"emphasize\">',a.oxshortdesc,'<span>') AS oxshortdesc, m.oxtitle AS oxmantitle, a.oxtitle AS oxtitle, "
                        . "a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol1 "
                        . "FROM oxarticles a "
                        . "LEFT JOIN oxmanufacturers m ON a.oxmanufacturerid = m.oxid "
                        . "WHERE CHAR_LENGTH(a.oxshortdesc) < $this->minDescLen "
                            //. "AND a.oxactive = 1 "
                            //. 'AND a.oxstockflag = 1 '
                            . "AND a.oxvarcount = 0 "
                            . "AND a.oxparentid = '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                $sSql2 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmnp, "
                        . "CONCAT('<span class=\"emphasize\">',a.oxshortdesc,'<span>') AS oxshortdesc, "
                        . "( "
                            . "SELECT m.oxtitle "
                            . "FROM oxarticles c "
                            . "LEFT JOIN oxmanufacturers m ON c.oxmanufacturerid = m.oxid "
                            . "WHERE a.oxparentid = c.oxid "
                        . ") AS oxmantitle, "
                        . "( "
                            . "SELECT b.oxtitle "
                            . "FROM oxarticles b "
                            . "WHERE a.oxparentid = b.oxid "
                        . ") AS oxtitle, "
                        . "a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol2 "
                        . "FROM oxarticles a "
                        . "WHERE "
                            . "(CHAR_LENGTH(a.oxshortdesc) < $this->minDescLen "
                                . "AND  (SELECT CHAR_LENGTH(b.oxshortdesc) FROM oxarticles b WHERE a.oxparentid = b.oxid) < $this->minDescLen) "
                            //. "AND a.oxactive = 1 "
                            //. 'AND a.oxstockflag = 1 '
                            . "AND a.oxparentid != '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                break;

            case 'nopic':
                $sSql1 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.oxshortdesc AS oxshortdesc, "
                        . "m.oxtitle AS oxmantitle, a.oxtitle AS oxtitle, a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol1 "
                        . "FROM oxarticles a "
                        . "LEFT JOIN oxmanufacturers m ON a.oxmanufacturerid = m.oxid "
                        . "WHERE (a.oxpic1 = '' OR a.oxpic1 = 'nopic.jpg') "
                            //. "AND a.oxactive = 1 "
                            //. 'AND a.oxstockflag = 1 '
                            . "AND a.oxvarcount = 0 "
                            . "AND a.oxparentid = '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                $sSql2 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.oxshortdesc AS oxshortdesc, "
                        . "( "
                            . "SELECT m.oxtitle "
                            . "FROM oxarticles c "
                            . "LEFT JOIN oxmanufacturers m ON c.oxmanufacturerid = m.oxid "
                            . "WHERE a.oxparentid = c.oxid "
                        . ") AS oxmantitle, "
                        . "( "
                            . "SELECT b.oxtitle "
                            . "FROM oxarticles b "
                            . "WHERE a.oxparentid = b.oxid "
                        . ") AS oxtitle, "
                        . "a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol2 "
                        . "FROM oxarticles a "
                        . "WHERE "
                            . "(a.oxpic1 = '' OR a.oxpic1 = 'nopic.jpg') "
                                . "AND  ((SELECT b.oxpic1 FROM oxarticles b WHERE a.oxparentid = b.oxid)  = '' OR (SELECT b.oxpic1 FROM oxarticles b WHERE a.oxparentid = b.oxid)  = 'nopic.jpg') "
                            //. "AND a.oxactive = 1 "
                            //. 'AND a.oxstockflag = 1 '
                            . "AND a.oxparentid != '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                break;

            case 'duplicate':
                // find duplicate oxartnum
                $sSql1 = "SELECT a.oxid, $sActive, CONCAT('<span class=\"emphasize\">',COUNT(*), ' x </span>',a.oxartnum) AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmpn, "
                            . "m.oxtitle AS oxmantitle, IF(a.oxparentid='',a.oxtitle,(SELECT a1.oxtitle FROM oxarticles a1 WHERE a1.oxid=a.oxparentid)) AS oxtitle, "
                            . "a.oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol1 "
                        . "FROM oxarticles a "
                        . "LEFT JOIN oxmanufacturers m "
                            . "ON a.oxmanufacturerid = m.oxid "
                        . "WHERE a.oxvarcount=0 "
                            //. "AND a.oxactive=1 "
                            . $sWhereActive
                            . $sWhere
                        . "GROUP BY oxartnum "
                        . "HAVING COUNT(*) > 1";
                // find duplicate oxean
                $sSql2 = "SELECT a.oxid, $sActive, a.oxartnum, CONCAT('<span class=\"emphasize\">',COUNT(*), ' x </span>',a.$this->ean) AS oxean, a.oxmpn AS oxmpn, "
                            . "m.oxtitle AS oxmantitle, IF(a.oxparentid='',a.oxtitle,(SELECT a1.oxtitle FROM oxarticles a1 WHERE a1.oxid=a.oxparentid)) AS oxtitle, "
                            . "a.oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol2 "
                        . "FROM oxarticles a "
                        . "LEFT JOIN oxmanufacturers m "
                            . "ON a.oxmanufacturerid = m.oxid "
                        . "WHERE a.oxvarcount=0 AND a.$this->ean != '' "
                            //. "AND a.oxactive=1 "
                            . $sWhereActive
                            . $sWhere
                        . "GROUP BY a.$this->ean "
                        . "HAVING COUNT(*) > 1";
                break;

            case 'dblactive':
                $sSql1 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmpn, a.oxtitle AS oxtitle, "
                        . "m.oxtitle AS oxmantitle, a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol1 "
                        . "FROM oxarticles a "
                        . "LEFT JOIN oxmanufacturers m ON a.oxmanufacturerid = m.oxid "
                        . "WHERE (a.oxactivefrom != '0000-00-00 00:00:00' OR a.oxactiveto != '0000-00-00 00:00:00') "
                            //. "AND a.oxactive = 1 "
                            //. 'AND a.oxstockflag = 1 '
                            . "AND a.oxvarcount = 0 "
                            . "AND a.oxparentid = '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                $sSql2 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmnp, "
                        . "( "
                            . "SELECT m.oxtitle "
                            . "FROM oxarticles c "
                            . "LEFT JOIN oxmanufacturers m ON c.oxmanufacturerid = m.oxid "
                            . "WHERE a.oxparentid = c.oxid "
                        . ") AS oxmantitle, "
                        . "( "
                            . "SELECT b.oxtitle "
                            . "FROM oxarticles b "
                            . "WHERE a.oxparentid = b.oxid "
                        . ") AS oxtitle, "
                        . "a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol2 "
                        . "FROM oxarticles a "
                        . "WHERE (a.oxactivefrom != '0000-00-00 00:00:00' OR a.oxactiveto != '0000-00-00 00:00:00') "
                            //. "AND a.oxactive = 1 "
                            //. 'AND a.oxstockflag = 1 '
                            . "AND a.oxparentid != '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                break;

            case 'longperiod':
                $sSql1 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmpn, "
                        . "m.oxtitle AS oxmantitle, a.oxtitle AS oxtitle, a.oxvarselect AS oxvarselect, "
                        . "CONCAT('<span class=\"emphasize\">',a.oxactivefrom,'</span>') AS oxactivefrom, CONCAT('<span class=\"emphasize\">',a.oxactiveto,'</span>') AS oxactiveto, a.oxstock AS oxstock, "
                        . "a.oxprice AS oxprice, $sIconCol1 "
                        . "FROM oxarticles a "
                        . "LEFT JOIN oxmanufacturers m ON a.oxmanufacturerid = m.oxid "
                        . "WHERE DATE(a.oxactiveto) - DATE(a.oxactivefrom) > $this->maxActionTime "
                            //. "AND a.oxactive = 0 "
                            //. 'AND a.oxstockflag = 1 '
                            . "AND a.oxvarcount = 0 "
                            . "AND a.oxparentid = '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                $sSql2 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmnp, "
                        . "( "
                            . "SELECT m.oxtitle "
                            . "FROM oxarticles c "
                            . "LEFT JOIN oxmanufacturers m ON c.oxmanufacturerid = m.oxid "
                            . "WHERE a.oxparentid = c.oxid "
                        . ") AS oxmantitle, "
                        . "( "
                            . "SELECT b.oxtitle "
                            . "FROM oxarticles b "
                            . "WHERE a.oxparentid = b.oxid "
                        . ") AS oxtitle, "
                        . "a.oxvarselect AS oxvarselect, CONCAT('<span class=\"emphasize\">',a.oxactivefrom,'</span>') AS oxactivefrom, CONCAT('<span class=\"emphasize\">',a.oxactiveto,'</span>') AS oxactiveto, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol2 "
                        . "FROM oxarticles a "
                        . "WHERE DATE(a.oxactiveto) - DATE(a.oxactivefrom) > $this->maxActionTime "
                            //. "AND a.oxactive = 0 "
                            //. 'AND a.oxstockflag = 1 '
                            . "AND a.oxparentid != '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                break;

            case 'invperiod':
                $sSql1 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmpn, "
                        . "m.oxtitle AS oxmantitle, a.oxtitle AS oxtitle, a.oxvarselect AS oxvarselect, "
                        . "CONCAT('<span class=\"emphasize\">',a.oxactivefrom,'</span>') AS oxactivefrom, CONCAT('<span class=\"emphasize\">',a.oxactiveto,'</span>') AS oxactiveto, a.oxstock AS oxstock, "
                        . "a.oxprice AS oxprice, $sIconCol1 "
                        . "FROM oxarticles a "
                        . "LEFT JOIN oxmanufacturers m ON a.oxmanufacturerid = m.oxid "
                        . "WHERE a.oxactiveto < a.oxactivefrom "
                            //. "AND a.oxactive = 0 "
                            //. 'AND a.oxstockflag = 1 '
                            . "AND a.oxvarcount = 0 "
                            . "AND a.oxparentid = '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                $sSql2 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmnp, "
                        . "( "
                            . "SELECT m.oxtitle "
                            . "FROM oxarticles c "
                            . "LEFT JOIN oxmanufacturers m ON c.oxmanufacturerid = m.oxid "
                            . "WHERE a.oxparentid = c.oxid "
                        . ") AS oxmantitle, "
                        . "( "
                            . "SELECT b.oxtitle "
                            . "FROM oxarticles b "
                            . "WHERE a.oxparentid = b.oxid "
                        . ") AS oxtitle, "
                        . "a.oxvarselect AS oxvarselect, CONCAT('<span class=\"emphasize\">',a.oxactivefrom,'</span>') AS oxactivefrom, CONCAT('<span class=\"emphasize\">',a.oxactiveto,'</span>') AS oxactiveto, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol2 "
                        . "FROM oxarticles a "
                        . "WHERE a.oxactiveto < a.oxactivefrom "
                            //. "AND a.oxactive = 0 "
                            . "AND a.oxparentid != '' "
                            . $sWhereActive
                            . $sWhere;
                break;

            case 'noprice':
                $sSql1 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmpn, "
                        . "m.oxtitle AS oxmantitle, a.oxtitle AS oxtitle, a.oxvarselect AS oxvarselect, "
                        . "a.oxstock AS oxstock, CONCAT('<span class=\"emphasize\">',a.oxprice,'</span>') AS oxprice, $sIconCol1 "
                        . "FROM oxarticles a "
                        . "LEFT JOIN oxmanufacturers m ON a.oxmanufacturerid = m.oxid "
                        . "WHERE a.oxprice < 0.01 "
                            //. "AND a.oxactive = 1 "
                            . "AND a.oxvarcount = 0 "
                            . "AND a.oxparentid = '' "
                            . $sWhereActive
                            . $sWhere;
                $sSql2 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmnp, "
                        . "( "
                            . "SELECT m.oxtitle "
                            . "FROM oxarticles c "
                            . "LEFT JOIN oxmanufacturers m ON c.oxmanufacturerid = m.oxid "
                            . "WHERE a.oxparentid = c.oxid "
                        . ") AS oxmantitle, "
                        . "( "
                            . "SELECT b.oxtitle "
                            . "FROM oxarticles b "
                            . "WHERE a.oxparentid = b.oxid "
                        . ") AS oxtitle, "
                        . "a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, CONCAT('<span class=\"emphasize\">',a.oxprice,'</span>') AS oxprice, $sIconCol2 "
                        . "FROM oxarticles a "
                        . "WHERE a.oxprice < 0.01 "
                            //. "AND a.oxactive = 1 "
                            . "AND a.oxparentid != '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                break;

            case 'nobuyprice':
                $sSql1 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmpn, "
                        . "m.oxtitle AS oxmantitle, a.oxtitle AS oxtitle, a.oxvarselect AS oxvarselect, "
                        . "a.oxstock AS oxstock, CONCAT('<span class=\"emphasize\">',a.oxbprice,'</span>') AS oxbprice, a.oxprice AS oxprice, $sIconCol1 "
                        . "FROM oxarticles a "
                        . "LEFT JOIN oxmanufacturers m ON a.oxmanufacturerid = m.oxid "
                        . "WHERE (a.oxbprice/a.oxprice) < $this->bpriceMin "
                            //. "AND a.oxactive = 1 "
                            //. 'AND a.oxstockflag = 1 '
                            . "AND a.oxvarcount = 0 "
                            . "AND a.oxparentid = '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                $sSql2 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmnp, "
                            . "( "
                                . "SELECT m.oxtitle "
                                . "FROM oxarticles c "
                                . "LEFT JOIN oxmanufacturers m ON c.oxmanufacturerid = m.oxid "
                                . "WHERE a.oxparentid = c.oxid "
                            . ") AS oxmantitle, "
                            . "( "
                                . "SELECT b.oxtitle "
                                . "FROM oxarticles b "
                                . "WHERE a.oxparentid = b.oxid "
                            . ") AS oxtitle, "
                            . "a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, "
                            . "CONCAT('<span class=\"emphasize\">',IF(a.oxbprice=0.0, (SELECT b.oxbprice FROM oxarticles b where b.oxid = a.oxparentid), a.oxbprice),'</span>') AS oxbprice, "
                            . "a.oxprice AS oxprice, $sIconCol2 "
                        . "FROM oxarticles a "
                        . "WHERE (IF(a.oxbprice=0.0, (SELECT b.oxbprice FROM oxarticles b where b.oxid = a.oxparentid), a.oxbprice)/a.oxprice) < $this->bpriceMin "
                            //. "AND a.oxactive = 1 "
                            //. 'AND a.oxstockflag = 1 '
                            . "AND a.oxparentid != '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                break;

            case 'noean':
                $sSql1 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, CONCAT('<span class=\"emphasize\">',a.$this->ean,'</span>') AS oxean, a.oxmpn AS oxmpn, "
                        . "m.oxtitle AS oxmantitle, a.oxtitle AS oxtitle, a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol1 "
                        . "FROM oxarticles a "
                        . "LEFT JOIN oxmanufacturers m ON a.oxmanufacturerid = m.oxid "
                        . "WHERE a.$this->ean = '' "
                            //. "AND a.oxactive = 1 "
                            //. 'AND a.oxstockflag = 1 '
                            . "AND a.oxvarcount = 0 "
                            . "AND a.oxparentid = '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                $sSql2 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, CONCAT('<span class=\"emphasize\">',a.$this->ean,'</span>') AS oxean, a.oxmpn AS oxmnp, "
                        . "( "
                            . "SELECT m.oxtitle "
                            . "FROM oxarticles c "
                            . "LEFT JOIN oxmanufacturers m ON c.oxmanufacturerid = m.oxid "
                            . "WHERE a.oxparentid = c.oxid "
                        . ") AS oxmantitle, "
                        . "( "
                            . "SELECT b.oxtitle "
                            . "FROM oxarticles b "
                            . "WHERE a.oxparentid = b.oxid "
                        . ") AS oxtitle, "
                        . "a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol2 "
                        . "FROM oxarticles a "
                        . "WHERE a.$this->ean = '' "
                            //. "AND a.oxactive = 1 "
                            //. 'AND a.oxstockflag = 1 '
                            . "AND a.oxparentid != '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                break;

            case 'eanchk':
                $sSql1 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, CONCAT('<span class=\"emphasize\">',a.$this->ean,'</span>') AS oxean, a.oxmpn AS oxmpn, "
                        . "m.oxtitle AS oxmantitle, a.oxtitle AS oxtitle, a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol1 "
                        . "FROM oxarticles a "
                        . "LEFT JOIN oxmanufacturers m ON a.oxmanufacturerid = m.oxid "
                        . "WHERE a.$this->ean != '' "
                            . "AND ("
                                . "LENGTH(a.$this->ean) != 13 "
                                . "OR "
                                . "SUBSTRING((10 - (((("
                                . "SUBSTRING($this->ean FROM 2 FOR 1) + "
                                . "SUBSTRING($this->ean FROM 4 FOR 1) + "
                                . "SUBSTRING($this->ean FROM 6 FOR 1) + "
                                . "SUBSTRING($this->ean FROM 8 FOR 1) + "
                                . "SUBSTRING($this->ean FROM 10 FOR 1) + "
                                . "SUBSTRING($this->ean FROM 12 FOR 1)  "
                                . ")*3) + ("
                                . "SUBSTRING($this->ean FROM 1 FOR 1) + "
                                . "SUBSTRING($this->ean FROM 3 FOR 1) + "
                                . "SUBSTRING($this->ean FROM 5 FOR 1) + "
                                . "SUBSTRING($this->ean FROM 7 FOR 1) + "
                                . "SUBSTRING($this->ean FROM 9 FOR 1) + "
                                . "SUBSTRING($this->ean FROM 11 FOR 1)"
                                . ")) MOD 10)) FROM -1 FOR 1) != SUBSTRING($this->ean FROM 13 FOR 1) "
                                . ") "
                            //. "AND a.oxactive = 1 "
                            //. 'AND a.oxstockflag = 1 '
                            . "AND a.oxvarcount = 0 "
                            . "AND a.oxparentid = '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                $sSql2 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, CONCAT('<span class=\"emphasize\">',a.$this->ean,'</span>') AS oxean, a.oxmpn AS oxmnp, "
                        . "( "
                            . "SELECT m.oxtitle "
                            . "FROM oxarticles c "
                            . "LEFT JOIN oxmanufacturers m ON c.oxmanufacturerid = m.oxid "
                            . "WHERE a.oxparentid = c.oxid "
                        . ") AS oxmantitle, "
                        . "( "
                            . "SELECT b.oxtitle "
                            . "FROM oxarticles b "
                            . "WHERE a.oxparentid = b.oxid "
                        . ") AS oxtitle, "
                        . "a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol2 "
                        . "FROM oxarticles a "
                        . "WHERE a.$this->ean != '' "
                            . "AND ("
                                . "LENGTH(a.$this->ean) != 13 "
                                . "OR "
                                . "SUBSTRING((10 - (((("
                                . "SUBSTRING($this->ean FROM 2 FOR 1) + "
                                . "SUBSTRING($this->ean FROM 4 FOR 1) + "
                                . "SUBSTRING($this->ean FROM 6 FOR 1) + "
                                . "SUBSTRING($this->ean FROM 8 FOR 1) + "
                                . "SUBSTRING($this->ean FROM 10 FOR 1) + "
                                . "SUBSTRING($this->ean FROM 12 FOR 1)  "
                                . ")*3) + ("
                                . "SUBSTRING($this->ean FROM 1 FOR 1) + "
                                . "SUBSTRING($this->ean FROM 3 FOR 1) + "
                                . "SUBSTRING($this->ean FROM 5 FOR 1) + "
                                . "SUBSTRING($this->ean FROM 7 FOR 1) + "
                                . "SUBSTRING($this->ean FROM 9 FOR 1) + "
                                . "SUBSTRING($this->ean FROM 11 FOR 1)"
                                . ")) MOD 10)) FROM -1 FOR 1) != SUBSTRING($this->ean FROM 13 FOR 1) "
                                . ") "
                            //. "AND a.oxactive = 1 "
                            //. 'AND a.oxstockflag = 1 '
                            . "AND a.oxparentid != '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                break;

            case 'nompn':
                $sSql1 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmpn, "
                        . "m.oxtitle AS oxmantitle, a.oxtitle AS oxtitle, a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol1 "
                        . "FROM oxarticles a "
                        . "LEFT JOIN oxmanufacturers m ON a.oxmanufacturerid = m.oxid "
                        . "WHERE a.oxmpn = '' "
                            //. "AND a.oxactive = 1 "
                            //. 'AND a.oxstockflag = 1 '
                            . "AND a.oxvarcount = 0 "
                            . "AND a.oxparentid = '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                $sSql2 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmnp, "
                        . "( "
                            . "SELECT m.oxtitle "
                            . "FROM oxarticles c "
                            . "LEFT JOIN oxmanufacturers m ON c.oxmanufacturerid = m.oxid "
                            . "WHERE a.oxparentid = c.oxid "
                        . ") AS oxmantitle, "
                        . "( "
                            . "SELECT b.oxtitle "
                            . "FROM oxarticles b "
                            . "WHERE a.oxparentid = b.oxid "
                        . ") AS oxtitle, "
                        . "a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol2 "
                        . "FROM oxarticles a "
                        . "WHERE a.oxmpn = '' "
                            //. "AND a.oxactive = 1 "
                            //. 'AND a.oxstockflag = 1 '
                            . "AND a.oxparentid != '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                break;

            case 'nocat':
                $sSql1 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmpn, "
                        . "m.oxtitle AS oxmantitle, a.oxtitle AS oxtitle, a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol1 "
                        . "FROM oxarticles a "
                        . "LEFT JOIN oxmanufacturers m ON a.oxmanufacturerid = m.oxid "
                        . "WHERE NOT EXISTS ( "
                            . "SELECT * "
                                . "FROM oxobject2category o2c "
                                . "WHERE a.oxid = o2c.oxobjectid "
                                . ") "
                            //. "AND a.oxactive = 1 "
                            . "AND a.oxparentid = '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                $sSQL2 = '';
                break;

            case 'orphan':
                $sSql1 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmnp, "
                        . "( "
                            . "SELECT m.oxtitle "
                            . "FROM oxarticles c "
                            . "LEFT JOIN oxmanufacturers m ON c.oxmanufacturerid = m.oxid "
                            . "WHERE a.oxparentid = c.oxid "
                            . ") AS oxmantitle, "
                        . "( "
                            . "SELECT b.oxtitle "
                            . "FROM oxarticles b "
                            . "WHERE a.oxparentid = b.oxid "
                            . ") AS oxtitle, "
                        . "a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, $sIconCol1 "
                        . "FROM oxarticles a "
                        . "WHERE "
                            . "("
                                . "SELECT b.oxactive "
                                . "FROM oxarticles b "
                                . "WHERE a.oxparentid = b.oxid "
                                . ") = 0 "
                            //. "AND a.oxactive = 1 "
                            //. 'AND a.oxstockflag = 1 '
                            . "AND a.oxparentid != '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                $sSQL2 = '';
                break;

            case 'nodesc':
                $sSql1 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmpn, "
                        . "( "
                            . "SELECT m.oxtitle "
                            . "FROM oxmanufacturers m "
                            . "WHERE a.oxmanufacturerid = m.oxid "
                        . ") AS oxmantitle, "
                        . "a.oxtitle AS oxtitle, "
                        . "a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, e.oxlongdesc, $sIconCol1 "
                        . "FROM oxarticles a, oxartextends e  "
                        . "WHERE a.oxid = e.oxid "
                            . "AND TRIM(e.oxlongdesc) = '' "
                            //. "AND a.oxactive = 1 "
                            . "AND a.oxparentid = '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                $sSql2 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmpn, "
                        . "("
                            ."SELECT m.oxtitle "
                            . "FROM oxarticles c "
                            . "LEFT JOIN oxmanufacturers m ON c.oxmanufacturerid = m.oxid "
                            . "WHERE a.oxparentid = c.oxid "
                        . ") AS oxmantitle, "
                        . "(SELECT b.oxtitle FROM oxarticles b WHERE a.oxparentid = b.oxid) AS oxtitle, "
                        . "a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, e.oxlongdesc, $sIconCol2 "
                        . "FROM oxarticles a, oxartextends e "
                        . "WHERE a.oxid = e.oxid "
                            . "AND TRIM(e.oxlongdesc) = '' "
                            . "AND (SELECT TRIM(f.oxlongdesc) FROM oxarticles b, oxartextends f WHERE b.oxid = f.oxid AND b.oxid = a.oxparentid) = '' "
                            //. "AND a.oxactive = 1 "
                            . "AND a.oxparentid != '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                break;

            case 'nomanu':
                $sSql1 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmpn, "
                        . "a.oxtitle AS oxtitle, a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, a.oxmanufacturerid, $sIconCol1  "
                        . "FROM oxarticles a "
                        . "WHERE a.oxmanufacturerid = '' "
                            //. "AND oxactive = 1 "
                            . "AND a.oxparentid = '' "
                            . $sWhereActive
                            . $sWhere;
                        //. "ORDER BY $sSort ";
                $sSql2 = "";
                break;

            case 'novend':
                $sSql1 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmpn, a.oxtitle AS oxtitle, "
                        . "a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, a.oxmanufacturerid, m.oxtitle AS oxmantitle, $sIconCol1 "
                        . "FROM oxarticles a "
                        . "LEFT JOIN oxmanufacturers m ON a.oxmanufacturerid = m.oxid "
                        . "WHERE a.oxvendorid = '' "
                            //. "AND a.oxactive = 1 "
                            . "AND a.oxparentid = '' "
                            . $sWhereActive
                            . $sWhere;
                $sSql2 = '';
                break;

            case 'active':
            case 'inactive':
                if ($cReportType == 'active') {
                    $sWhereActive = "AND a.oxactive = 1 ";
                }
                else {
                    $sWhereActive = "AND a.oxactive = 0 ";
                }

                $sSql1 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmpn, "
                        . "m.oxtitle AS oxmantitle, a.oxtitle AS oxtitle, a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, a.oxmanufacturerid, $sIconCol1 "
                        . "FROM oxarticles a "
                        . "LEFT JOIN oxmanufacturers m ON a.oxmanufacturerid = m.oxid "
                        . "WHERE a.oxparentid = '' "
                            . $sWhereActive
                            . $sWhere;
                $sSql2 = "SELECT a.oxid AS oxid, $sActive, a.oxparentid AS oxparentid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, a.oxmpn AS oxmpn, "
                        . "( "
                            . "SELECT m.oxtitle "
                            . "FROM oxarticles c "
                            . "LEFT JOIN oxmanufacturers m ON c.oxmanufacturerid = m.oxid "
                            . "WHERE a.oxparentid = c.oxid "
                        . ") AS oxmantitle, "
                        . "a.oxtitle AS oxtitle, a.oxvarselect AS oxvarselect, a.oxstock AS oxstock, a.oxprice AS oxprice, a.oxmanufacturerid, "
                        . "( "
                            . "SELECT b.oxtitle "
                            . "FROM oxarticles b "
                            . "WHERE a.oxparentid = b.oxid "
                        . ") AS oxtitle, "
                        . "$sIconCol2 "
                        . "FROM oxarticles a "
                        . "WHERE a.oxparentid != '' "
                            . $sWhereActive
                            . $sWhere;
                break;

            default:
                $sSql1 = '';
                $sSql2 = '';
                $aIncFiles = array();
                $aIncReports = array();
                if (count($myConfig->getConfigParam("aOxProbsArticleIncludeFiles")) != 0) {
                    $aIncFiles = $myConfig->getConfigParam("aOxProbsArticleIncludeFiles");
                    $sIncPath = $this->jxGetModulePath() . '/application/controllers/admin/';
                    foreach ($aIncFiles as $sIncFile) { 
                        $sIncFile = $sIncPath . 'oxprobs_articles_' . $sIncFile . '.inc.php';
                        try {
                            require $sIncFile;
                        }
                        catch (Exception $e) {
                            echo $e->getMessage();
                            die();
                        }
                    } 
                }
                
                break;
        }

        $aArticles = array();

        if (!empty($sSql1)) {
            $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );

            try {
                $rs = $oDb->Execute($sSql1);
            }
            catch (Exception $e) {
                echo '<div style="border:2px solid #dd0000;margin:10px;padding:5px;background-color:#ffdddd;font-family:sans-serif;font-size:14px;">';
                echo '<b>SQL-Error '.$e->getCode().' in SQL1</b><br />'.$e->getMessage().'';
                echo '<hr><pre style="white-space:pre-wrap;word-wrap:break-word;">'.$sSql1.'</pre>';
                echo '</div>';
                return;
            }

            while (!$rs->EOF) {
                array_push($aArticles, $rs->fields);
                $rs->MoveNext();
            }
        }
        
        if (!empty($sSql2)) {
            $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );

            try {
                $rs = $oDb->Execute($sSql2);
            }
            catch (Exception $e) {
                echo '<div style="border:2px solid #dd0000;margin:10px;padding:5px;background-color:#ffdddd;font-family:sans-serif;font-size:14px;">';
                echo '<b>SQL-Error '.$e->getCode().' in SQL2</b><br />'.$e->getMessage().'';
                echo '</div>';
                return;
            }
            
            while (!$rs->EOF) {
                array_push($aArticles, $rs->fields);
                $rs->MoveNext();
            }
        }

        if ( (!empty($sortCol)) && (count($aArticles)!=0) ) {
            foreach ($aArticles as $key => $row) {
                $column1[$key]    = strtolower( $row[$sortCol] );
                $column2[$key]    = strtolower( $row['oxtitle'] );
            }
            $sortDir = ($sortOpt == 'ASC') ? SORT_ASC : SORT_DESC;
            array_multisort($column1, $sortDir, $column2, SORT_ASC, SORT_STRING, $aArticles);
        }
        
        return $aArticles;
    }
    
    
    /**
     * 
     * @param array $aArticles
     * @return array
     */
    private function _decodeHtmlSpecialChars( $aArticles )
    {
        foreach ($aArticles as $index => $aArticle) {
            foreach ($aArticle as $FieldName => $sFieldValue) 
            {
                $aArticles[$index][$FieldName] = htmlspecialchars_decode($sFieldValue);
                $aArticles[$index][$FieldName] = str_replace('&ndash;', '-', $sFieldValue);
            }
        }
        return $aArticles;
    }
    
    
    /**
     * 
     * @param array $aArticles
     * @return array
     */
    private function _stripTagsFromData( $aArticles )
    {
        foreach ($aArticles as $index => $aArticle) {
            foreach ($aArticle as $FieldName => $sFieldValue) 
            {
                $aArticles[$index][$FieldName] = strip_tags($sFieldValue);
            }
        }
        return $aArticles;
    }

    
    /**
     * 
     * @return string
     */
    public function jxGetModulePath()
    {
        $sModuleId = $this->getEditObjectId();

        $this->_aViewData['oxid'] = $sModuleId;

        $oModule = oxNew('oxModule');
        $oModule->load($sModuleId);
        $sModuleId = $oModule->getId();
        
        $myConfig = oxRegistry::get("oxConfig");
        $sModulePath = $myConfig->getConfigParam("sShopDir") . 'modules/' . $oModule->getModulePath("oxprobs");
        
        return $sModulePath;
    }
    
}

?>
