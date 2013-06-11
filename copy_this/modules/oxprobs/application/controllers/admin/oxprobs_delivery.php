<?php

/*
 *    This file is part of the module xProbs for OXID eShop Community Edition.
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
 * 
 * $Id: oxprobs_delivery.php 62 2011-12-03 08:59:41Z jobarthel@gmail.com $
 *
 */
 
class oxprobs_delivery extends oxAdminView
{
    protected $_sThisTemplate = "oxprobs_delivery.tpl";
    
    public function render()
    {
        //ini_set('display_errors', true);

        parent::render();
        $oSmarty = oxUtilsView::getInstance()->getSmarty();
        $oSmarty->assign( "oViewConf", $this->_aViewData["oViewConf"]);
        $oSmarty->assign( "shop", $this->_aViewData["shop"]);
        
        $cReportType = isset($_POST['oxprobs_reporttype']) ? $_POST['oxprobs_reporttype'] : $_GET['oxprobs_reporttype']; 
        if (empty($cReportType))
            $cReportType = "delsetcost";
        $oSmarty->assign( "ReportType", $cReportType );
        
        //include "config.inc.php";
        $myConfig = oxRegistry::get("oxConfig");
        $this->ean = $myConfig->getConfigParam("sOxProbsEANField");
        $this->minDescLen = (int) $myConfig->getConfigParam("sOxProbsMinDescLen");
        $this->bpriceMin = (float) $myConfig->getConfigParam("sOxProbsBPriceMin");

        $sWhere = "";
        if ( is_string($this->_aViewData["oViewConf"]->getActiveShopId()) ) { 
            // This is a CE or PE Shop
            $sShopId = $this->_aViewData["oViewConf"]->getActiveShopId();
            $sWhere = $sWhere . " AND ds.oxshopid = '$sShopId' ";
        }
        else {
            // This is a EE Shop
            $iShopId = $this->_aViewData["oViewConf"]->getActiveShopId();
            $sWhere = $sWhere . " AND ds.oxshopid = $iShopId ";
            
        }
        
        switch ($cReportType) {
            case 'delsetcost':
                $sSql1 = 'SELECT c.oxtitle AS country, o2d.oxdeliveryid, o2d.oxobjectid, ds.oxtitle AS deliveryset, ds.oxpos, '
                            . 'd.oxtitle AS deliveryrule, d.oxaddsum AS addsum, d.oxaddsumtype AS addtype, d.oxparam As startval, d.oxparamend AS endval '
                        . 'FROM oxcountry c, oxobject2delivery o2d, oxdeliveryset ds, oxdel2delset d2d, oxdelivery d '
                        . 'WHERE o2d.oxtype=\'oxdelset\' '
                        . 'AND c.oxid=o2d.oxobjectid '
                        . 'AND o2d.oxdeliveryid=ds.oxid '
                        . 'AND d2d.OXDELID=d.oxid '
                        . 'AND d2d.OXDELSETID=ds.OXID '
                        . 'AND c.oxactive=1 AND d.oxactive=1 AND ds.oxactive=1 '
                        . $sWhere
                        . 'ORDER BY c.oxtitle, ds.oxtitle, d.oxtitle ';
                $sSql2 = '';
                $sSql3 = '';
                $cClass = '---';
                break;

            case 'delsetpay':
                $sSql1 = 'SELECT c.oxid AS countryid, c.oxtitle AS country, o2d.oxdeliveryid, o2d.oxobjectid, ds.oxid AS delsetid, '
                            . 'ds.oxtitle AS deliveryset, ds.oxpos, p.oxid AS paymentid, p.oxdesc AS payment, p.oxaddsum AS addsum, '
                            . 'p.oxaddsumtype AS addtype '
                        . 'FROM oxcountry c, oxobject2delivery o2d, oxdeliveryset ds, oxobject2payment o2p, oxpayments p '
                        . 'WHERE o2d.oxtype=\'oxdelset\' '
                        . 'AND c.oxid=o2d.oxobjectid '
                        . 'AND o2d.oxdeliveryid=ds.oxid '
                        . 'AND o2p.oxobjectid=ds.oxid '
                        . 'AND o2p.oxpaymentid=p.oxid '
                        . 'AND c.oxactive=1 AND ds.oxactive=1 AND p.oxactive=1 '
                        . $sWhere
                        . 'ORDER BY c.oxtitle, ds.oxtitle, p.oxdesc ';
                //$sSql1 = '';
                $sSql2 = '';
                $sSql3 = '';
                $cClass = '---';
                break;
            
            default:
                $sSql = '';
                break;

        }

        $i = 0;
        $aList = array();
        $aDelSets = array();
        $aDelCosts = array();

        if (!empty($sSql1)) {
            $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
            $rs = $oDb->Execute($sSql1);
            //---old---$rs = oxDb::getDb(true)->Execute($sSql1);
            /*echo '<pre>';
            echo $sSql1;
            echo '</pre>';
            /* 
            echo '<pre>';
            echo $sSql2;
            echo '</pre>';*/
            while (!$rs->EOF) {
                array_push($aList, $rs->fields);
                $rs->MoveNext();
            }
        }
        
        if (!empty($sSql2)) {
            $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
            $rs = $oDb->Execute($sSql2);
            //---old---$rs = oxDb::getDb(true)->Execute( $sSql2);
            while (!$rs->EOF) {
                array_push($aDelSets, $rs->fields);
                $rs->MoveNext();
            }
        }

        if (!empty($sSql3)) {
            $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
            $rs = $oDb->Execute($sSql3);
            //---old---$rs = oxDb::getDb(true)->Execute( $sSql3);
            while (!$rs->EOF) {
                array_push($aDelCosts, $rs->fields);
                $rs->MoveNext();
            }
        }

        $oSmarty->assign("editClassName", $cClass);
        $oSmarty->assign("aList", $aList);

         return $this->_sThisTemplate;
    }
    
}
?>