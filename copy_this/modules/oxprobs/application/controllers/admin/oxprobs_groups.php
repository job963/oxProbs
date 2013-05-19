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
 * 
 * $Id: oxprobs_groups.php 62 2011-12-03 08:59:41Z jobarthel@gmail.com $
 *
 */
 
class oxprobs_groups extends oxAdminDetails
{
    protected $_sThisTemplate = "oxprobs_groups.tpl";
    
    public function render()
    {
        ini_set('display_errors', true);

        parent::render();
        $oSmarty = oxUtilsView::getInstance()->getSmarty();
        $oSmarty->assign( "oViewConf", $this->_aViewData["oViewConf"]);
        $oSmarty->assign( "shop", $this->_aViewData["shop"]);

        $cReportType = isset($_POST['oxprobs_reporttype']) ? $_POST['oxprobs_reporttype'] : $_GET['oxprobs_reporttype']; 
        if (empty($cReportType))
            $cReportType = "invactions";
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
            $sWhere = $sWhere . " AND a.oxshopid = '$sShopId' ";
        }
        else {
            // This is a EE Shop
            $iShopId = $this->_aViewData["oViewConf"]->getActiveShopId();
            $sWhere = $sWhere . " AND a.oxshopid = $iShopId ";
            
        }
        
        switch ($cReportType) {
            case 'invactions':
                $sSql1 = 'SELECT c.oxid AS oxid, c.oxtitle AS oxtitle, COUNT(*) AS count, '
                        . 'CONCAT_WS(\'|\', '
                            . 'IF(c.oxactive = 0, \'OXPROBS_DEACT_ACT\', \'\'), '
                            . 'IF(c.oxactivefrom > DATE(NOW()), \'OXPROBS_PROSPEC_ACT\', \'\'), '
                            . 'IF (c.oxactiveto < DATE(NOW()) AND c.oxactiveto != \'0000-00-00 00:00:00\' , \'OXPROBS_EXP_ACT\', \'\') '
                        . ') AS status '
                        . 'FROM oxarticles a, oxactions2article a2a, oxactions c '
                        . 'WHERE a.oxid = a2a.oxartid AND c.oxid = a2a.oxactionid '
                        . 'AND ('
                            . 'c.oxactive = 0 '
                            . 'OR c.oxactivefrom > date(now()) '
                            . 'OR (c.oxactiveto < date(now()) AND c.oxactiveto != \'0000-00-00 00:00:00\') '
                        . ') '
                        . 'GROUP BY c.oxtitle ';
                $sSql2 = '';
                $cClass = 'actions';
                break;

            case 'invcats':
                $sSql1 = 'SELECT c.oxid AS oxid, c.oxid AS oxid, c.oxtitle AS oxtitle, COUNT(*) AS count, '
                        . 'CONCAT_WS(\'|\', '
                            . 'IF(c.oxactive = 0, \'OXPROBS_DEACT_CATS\', \'\'), '
                            . 'IF((SELECT c1.oxactive FROM oxcategories c1 WHERE c1.oxid = c.oxparentid) = 0, \'OXPROBS_DEACT_PARENTCAT\', \'\'), '
                            . 'IF((SELECT c2.oxactive FROM oxcategories c2, oxcategories c1 WHERE c1.oxid = c.oxparentid AND c2.oxid = c1.oxparentid AND c2.oxactive = 0) = 0, \'OXPROBS_DEACT_GRANDCAT\', \'\') '
                        . ') AS status '
                        . 'FROM oxarticles a, oxobject2category o2a, oxcategories c '
                        . 'WHERE a.oxid = o2a.oxobjectid AND c.oxid = o2a.oxcatnid '
                            . 'AND ('
                                . 'c.oxactive = 0 '
                                . 'OR (SELECT c1.oxactive FROM oxcategories c1 WHERE c1.oxid = c.oxparentid AND c1.oxactive = 0) = 0 '
                                . 'OR (SELECT c2.oxactive FROM oxcategories c2, oxcategories c1 WHERE c1.oxid = c.oxparentid AND c2.oxid = c1.oxparentid AND c2.oxactive = 0) = 0 '
                            . ') '
                            . 'AND a.oxactive = 1 '
                        . 'GROUP BY c.oxtitle ';
                $sSql2 = '';
                $cClass = 'category';
                break;
            
            case 'invman':
                $sSql1 = 'SELECT m.oxid AS oxid, m.oxtitle AS oxtitle, COUNT(*) AS count, '
                        . 'CONCAT_WS(\'|\', '
                            . 'IF(m.oxactive = 0, \'OXPROBS_DEACT_MAN\', \'\'), '
                            . 'IF(m.oxicon = \'\', \'OXPROBS_NOICON_MAN\', \'\') ) '
                        . 'AS status '
                        . 'FROM oxarticles a, oxmanufacturers m '
                        . 'WHERE a.oxmanufacturerid = m.oxid '
                            . 'AND ('
                                . 'm.oxactive = 0 '
                                . 'OR m.oxicon = \'\' '
                            . ') '
                            . 'AND a.oxactive = 1 '
                        . 'GROUP BY m.oxtitle ';
                $sSql2 = '';
                $cClass = 'manufacturer';
                break;
            
            case 'invven':
                $sSql1 = 'SELECT v.oxid AS oxid, v.oxtitle AS oxtitle, COUNT(*) AS count, '
                        . 'CONCAT_WS(\'|\', '
                            . 'IF(v.oxactive = 0, \'OXPROBS_DEACT_VEN\', \'\'), '
                            . 'IF(v.oxicon = \'\', \'OXPROBS_NOICON_VEN\', \'\') ) '
                        . 'AS status '
                        . 'FROM oxarticles a, oxvendor v '
                        . 'WHERE a.oxvendorid = v.oxid '
                            . 'AND ('
                                . 'v.oxactive = 0 '
                                . 'OR v.oxicon = \'\' '
                            . ') '
                            . 'AND a.oxactive = 1 '
                        . 'GROUP BY v.oxtitle ';
                $sSql2 = '';
                $cClass = 'vendor';
                break;
            
            default:
                $sSql = '';
                break;

        }

        $i = 0;
        $aGroups = array();

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
                array_push($aGroups, $rs->fields);
                $rs->MoveNext();
            }
        }
        
        $oSmarty->assign("editClassName", $cClass);
        $oSmarty->assign("aGroups", $aGroups);

         return $this->_sThisTemplate;
    }
    
}

?>