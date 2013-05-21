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
 
class oxprobs_pictures extends oxAdminDetails
{
    protected $_sThisTemplate = "oxprobs_pictures.tpl";
    
    public function render()
    {
        ini_set('display_errors', true);

        parent::render();
        $oSmarty = oxUtilsView::getInstance()->getSmarty();
        $oSmarty->assign( "oViewConf", $this->_aViewData["oViewConf"]);
        $oSmarty->assign( "shop", $this->_aViewData["shop"]);

        $cReportType = isset($_POST['oxprobs_reporttype']) ? $_POST['oxprobs_reporttype'] : $_GET['oxprobs_reporttype']; 
        if (empty($cReportType))
            $cReportType = "manu";
        $oSmarty->assign( "ReportType", $cReportType );
        
        //include "config.inc.php";
        $myConfig = oxRegistry::get("oxConfig");
        $this->ean = $myConfig->getConfigParam("sOxProbsEANField");
        $this->minDescLen = (int) $myConfig->getConfigParam("sOxProbsMinDescLen");
        $this->bpriceMin = (float) $myConfig->getConfigParam("sOxProbsBPriceMin");
        $this->pictureDir = $myConfig->getPictureDir(FALSE);
        //echo $myConfig->getPictureDir()."<br>";
        //echo $myConfig->getMasterPictureDir()."<br>";
        //echo $myConfig->getImageDir()."<br>";

        $sWhere = "";
        if ( is_string($this->_aViewData["oViewConf"]->getActiveShopId()) ) { 
            // This is a CE or PE Shop
            $sShopId = $this->_aViewData["oViewConf"]->getActiveShopId();
            $sWhere = $sWhere . " AND oxshopid = '$sShopId' ";
        }
        else {
            // This is a EE Shop
            $iShopId = $this->_aViewData["oViewConf"]->getActiveShopId();
            $sWhere = $sWhere . " AND oxshopid = $iShopId ";
            
        }
        
        switch ($cReportType) {
            case 'manu':
                $sSql1 = "SELECT oxid, oxtitle, oxicon, filename, "
                        . "IF(oxicon='', 'OXPROBS_NOPIC_DEF',IF(filename IS NULL, 'OXPROBS_NOPIC_FOUND','')) AS status "
                    . "FROM oxmanufacturers "
                    . "LEFT JOIN tmpimages "
                        . "ON oxicon = filename "
                        . "WHERE filename IS NULL "
                        . $sWhere;
                $sSql2 = "";
                $cClass = 'actions';
                break;

            case 'orphmanu':
                $sSql1 = "SELECT oxid, oxtitle, oxicon, filename, "
                        . "IF(oxicon='', 'OXPROBS_NOPIC_DEF',IF(filename IS NULL, 'OXPROBS_NOPIC_FOUND','')) AS status "
                    . "FROM oxmanufacturers "
                    . "LEFT JOIN tmpimages "
                        . "ON oxicon = filename "
                        . "WHERE filename IS NULL ";
                $sSql2 = "";
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
                        
            default:
                $sSql = '';
                break;

        }

        $i = 0;
        $aItems = array();
        
        $sSql = "CREATE TEMPORARY TABLE tmpimages ( filename VARCHAR(128) )";
        $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
        $rs = $oDb->Execute($sSql);
        
        $dir = $this->pictureDir .'generated/manufacturer/icon/100_100_75';
        $files = scandir($dir);
            /*echo '<pre>';
            print_r($files);
            echo '</pre>';*/
        foreach ($files as $key => $value) { 
           if ( !in_array($value,array(".","..")) ) { 
                $sSql = "INSERT INTO tmpimages (filename) VALUES ('$value') ";
                $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
                $rs = $oDb->Execute($sSql);
           } 
        } 

        if (!empty($sSql1)) {
            //$oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
            $rs = $oDb->Execute($sSql1);
            //---old---$rs = oxDb::getDb(true)->Execute($sSql1);
            /*echo '<pre>';
            echo $sSql1;
            echo '</pre>';
            /* 
            echo '<pre>';
            print_r($rs);
            echo '</pre>';/* */
            while (!$rs->EOF) {
                array_push($aItems, $rs->fields);
                $rs->MoveNext();
            }
            /*echo '<pre>';
            print_r($aItems);
            echo '</pre>';*/
        }
        
        $oSmarty->assign("editClassName", $cClass);
        $oSmarty->assign("aItems", $aItems);

         return $this->_sThisTemplate;
    }
    
}

?>