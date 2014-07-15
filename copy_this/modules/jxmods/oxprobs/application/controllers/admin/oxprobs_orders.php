<?php

/*
 *    This file is part of the module oxProbs for OXID eShop Community Edition.
 *
 *    The module oxProbs for OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    The module OxProbs for OXID eShop Community Edition is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link    https://github.com/job963/oxProbs
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @copyright (C) Joachim Barthel 2012-2014
 * 
 * $Id: oxprobs_orders.php jobarthel@gmail.com $
 *
 */
 
class oxprobs_orders extends oxAdminView
{
    protected $_sThisTemplate = "oxprobs_orders.tpl";
    
    public function render()
    {
        parent::render();
        $oSmarty = oxUtilsView::getInstance()->getSmarty();
        $oSmarty->assign( "oViewConf", $this->_aViewData["oViewConf"]);
        $oSmarty->assign( "shop", $this->_aViewData["shop"]);
        $myConfig = oxRegistry::get("oxConfig");
        
        $aIncFiles = array();
        $aIncReports = array();
        if (trim($myConfig->getConfigParam("sOxProbsOrdersIncludeFiles")) != '') {
            $aIncFiles = explode( ',', $myConfig->getConfigParam("sOxProbsOrdersIncludeFiles") );
            $sIncPath = $this->jxGetModulePath() . '/application/controllers/admin/';
            foreach ($aIncFiles as $sIncFile) { 
                $sIncFile = $sIncPath . 'oxprobs_orders_' . $sIncFile . '.inc.php';
                require $sIncFile;
            } 
        }

        $cReportType = oxConfig::getParameter( 'oxprobs_reporttype' );
        if (empty($cReportType))
            $cReportType = "readyorders";
        $oSmarty->assign( "ReportType", $cReportType );
        
        $aOrders = array();
        $aOrders = $this->_retrieveData();
        
        $oSmarty->assign("editClassName", $cClass);
        $oSmarty->assign("aOrders", $aOrders);
        $oSmarty->assign("aIncReports",$aIncReports);

         return $this->_sThisTemplate;
    }
     
    
    public function downloadResult()
    {
        $aOrders = array();
        $aOrders = $this->_retrieveData();

        $aSelOxid = oxConfig::getParameter( "oxprobs_oxid" ); 
        
        $sContent = '';
        foreach ($aOrders as $aOrder) {
            if ( in_array($aOrder['oxid'], $aSelOxid) ) {
                $sContent .= '"' . implode('","', $aOrder) . '"' . chr(13);
            }
        }

        header("Content-Type: text/plain");
        header("content-length: ".strlen($sContent));
        header("Content-Disposition: attachment; filename=\"problem-report.csv\"");
        echo $sContent;
        
        exit();

        return;
    }

    
    private function _retrieveData()
    {
        
        $cReportType = oxConfig::getParameter( 'oxprobs_reporttype' );
        if (empty($cReportType))
            $cReportType = "readyorders";

        $myConfig = oxRegistry::get("oxConfig");
        $this->ean = $myConfig->getConfigParam("sOxProbsEANField");
        $this->minDescLen = (int) $myConfig->getConfigParam("sOxProbsMinDescLen");
        $this->bpriceMin = (float) $myConfig->getConfigParam("sOxProbsBPriceMin");

        $sWhere = "";
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
        
        switch ($cReportType) {
            case 'readyorders':
            case 'opencia':
            case 'openinv':
                $txtIgnoreRemark = $myConfig->getConfigParam("sOxProbsOrderIgnoredRemark");                                               //"Hier k%nnen Sie uns noch etwas mitteilen.";
                if ($cReportType == 'readyorders') {
                    $payTypeList = "'" . implode("','", explode(',', $myConfig->getConfigParam("sOxProbsOrderPaidLater"))) . "'";         //"oxidinvoice,oxidcashondel";
                } elseif ($cReportType == 'opencia') {
                    $payTypeList = "'" . implode("','", explode(',', $myConfig->getConfigParam("sOxProbsOrderPaidbyCIA"))) . "'";         //"oxidpayadvance";
                } elseif ($cReportType == 'openinv') {
                    $payTypeList = "'" . implode("','", explode(',', $myConfig->getConfigParam("sOxProbsOrderPaidbyInvoice"))) . "'";     //"oxidinvoice";
                } else {
                    // nothing to do
                }
                
                $sSql1 = "SELECT o.oxid AS oxid, o.oxordernr AS orderno, o.oxtotalordersum AS ordersum, o.oxbillsal AS salutation, "
                         . "CONCAT('<nobr>', o.oxbillcompany, '</nobr>') AS company, "
                         . "CONCAT('<a href=\"mailto:', o.oxbillemail, '\" style=\"text-decoration:underline;\"><nobr>', o.oxbillfname, '&nbsp;', o.oxbilllname, '</nobr></a>') AS name, "
                         . "IF (o.oxdelcity = '', "
                            . "CONCAT('<a href=\"http://maps.google.com/maps?f=q&hl=de&geocode=&q=', o.oxbillstreet,'+',o.oxbillstreetnr,',+',o.oxbillzip,'+',o.oxbillcity,'&z=10\" style=\"text-decoration:underline;\" target=\"_blank\">', o.oxbillzip, '&nbsp;', o.oxbillcity, '</a>'), "
                            . "CONCAT('<a href=\"http://maps.google.com/maps?f=q&hl=de&geocode=&q=', o.oxdelstreet,'+',o.oxdelstreetnr,',+',o.oxdelzip,'+',o.oxdelcity,'&z=10\" style=\"text-decoration:underline;\" target=\"_blank\">', o.oxdelzip, '&nbsp;', o.oxdelcity, '</a>') "
                            . ") AS  custdeladdr, "
                         . "p.oxdesc AS paytype, "
                         . "GROUP_CONCAT(CONCAT('<nobr>', a.oxamount, ' x ', a.oxtitle, IF (a.oxselvariant != '', CONCAT(' &ndash; ', a.oxselvariant), ''), '</nobr>') SEPARATOR '<br>') AS orderlist, "
                         . "(TO_DAYS(NOW())-TO_DAYS(o.oxorderdate)) AS days, DATE(o.oxorderdate) AS orderdate , "
                         . "IF(o.oxremark!='', "
                            . "IF((SELECT o.oxremark LIKE '{$txtIgnoreRemark}') != 1,"
                                . "o.oxremark, "
                                . "''"
                            . "), "
                            . "''"
                         . ") AS remark "
                     . "FROM oxorder o, oxpayments p, oxorderarticles a "
                     . "WHERE o.oxpaymenttype = p.oxid "
                         . "AND o.oxid = a.oxorderid  "
                         . "AND ((o.oxpaid != '0000-00-00 00:00:00') OR (o.oxpaymenttype IN ({$payTypeList}))) "
                         . "AND o.oxsenddate = '0000-00-00 00:00:00' "
                         /*. "AND DATE(o.oxorderdate) >= '{$dateStart}' "
                         . "AND DATE(o.oxorderdate)  <= '{$dateEnd}' "*/
                         . "AND o.oxstorno = 0 "
                         . "AND o.oxshopid = '{$sShopId}' "
                     . "GROUP BY o.oxordernr "
                     . "ORDER BY days ASC "; 

                
                $cClass = 'actions';
                break;

            default:
                $sSql1 = '';
                $sSql2 = '';
                $aIncFiles = array();
                $aIncReports = array();
                if (trim($myConfig->getConfigParam("sOxProbsOrderIncludeFiles")) != '') {
                    $aIncFiles = explode( ',', $myConfig->getConfigParam("sOxProbsOrderIncludeFiles") );
                    $sIncPath = $this->jxGetModulePath() . '/application/controllers/admin/';
                    foreach ($aIncFiles as $sIncFile) { 
                        $sIncFile = $sIncPath . 'oxprobs_orders_' . $sIncFile . '.inc.php';
                        require $sIncFile;
                    } 
                }
                
                break;

        }

        $aOrders = array();

        //echo "<hr><pre>$sSql1</pre>";
        if (!empty($sSql1)) {
            $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
            $rs = $oDb->Execute($sSql1);
            while (!$rs->EOF) {
                array_push($aOrders, $rs->fields);
                $rs->MoveNext();
            }
            /*echo '<pre>';
            print_r($aOrders);
            echo '</pre>';*/
        }
        
        return $aOrders;
    }

    
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