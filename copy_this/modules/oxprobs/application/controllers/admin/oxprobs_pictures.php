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
            $cReportType = "manumisspics";
        $oSmarty->assign( "ReportType", $cReportType );
        
        //include "config.inc.php";
        $myConfig = oxRegistry::get("oxConfig");
        $this->ean = $myConfig->getConfigParam("sOxProbsEANField");
        $this->minDescLen = (int) $myConfig->getConfigParam("sOxProbsMinDescLen");
        $this->bpriceMin = (float) $myConfig->getConfigParam("sOxProbsBPriceMin");
        //--echo $myConfig->getConfigParam("sManufacturerIconsize");
        //$this->pictureDir = $myConfig->getPictureDir(FALSE);
        //echo $myConfig->getPictureDir()."<br>";
        //echo $myConfig->getMasterPictureDir()."<br>";
        //echo $myConfig->getImageDir()."<br>";
        //echo $myConfig->getPictureUrl(FALSE)."<br>";
        //echo "getIconUrl".oxManufacturer::getIconUrl();
        //$oManufacturer = oxNew( 'oxmanufacturer' );
        //echo "getIconUrl" . $oManufacturer->getIconUrl();
        /*echo 'ico=';
        echo $myConfig->getConfigParam( 'sIconsize' ).' thumb=';
        echo $myConfig->getConfigParam( 'sThumbnailsize' ).' detail=';
        print_r ($myConfig->getConfigParam( 'aDetailImageSizes' ));
        echo ' zoom=';
        echo $myConfig->getConfigParam( 'sZoomImageSize' ).' ';*/

        $sWhere = "";
        if ( is_string($this->_aViewData["oViewConf"]->getActiveShopId()) ) { 
            // This is a CE or PE Shop
            $sShopId = $this->_aViewData["oViewConf"]->getActiveShopId();
            $sWhere = $sWhere . " oxshopid = '$sShopId' ";
        }
        else {
            // This is a EE Shop
            $iShopId = $this->_aViewData["oViewConf"]->getActiveShopId();
            $sWhere = $sWhere . " oxshopid = $iShopId ";
            
        }
        
        switch ($cReportType) {
            case 'manumisspics':
                $sSql1 = "SELECT oxid, oxtitle, oxicon AS picname, filename, "
                        . "IF(oxicon='', 'OXPROBS_NOPIC_DEF',IF(filename IS NULL, 'OXPROBS_NOPIC_FOUND','')) AS status "
                    . "FROM oxmanufacturers "
                    . "LEFT JOIN tmpimages "
                        . "ON oxicon = filename "
                        . "WHERE filename IS NULL "
                        . "AND " . $sWhere;
                $sSql2 = "";
                $sSubDir = str_replace('*','_',$myConfig->getConfigParam( 'sManufacturerIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/manufacturer/icon/' . $sSubDir;
                $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/manufacturer/icon/' . $sSubDir;
                $cClass = 'actions';
                break;

            case 'manuorphpics':
                $sSql1 = "SELECT oxicon, filename AS picname, 'OXPROBS_ORPHPIC_FOUND' AS status "
                        . "FROM tmpimages "
                        . "LEFT JOIN oxmanufacturers "
                            . "ON oxicon = filename "
                            . "WHERE oxicon IS NULL "
                            . "AND (" . $sWhere . " OR oxshopid IS NULL) ";
                $sSql2 = "";
                $sSubDir = str_replace('*','_',$myConfig->getConfigParam( 'sManufacturerIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/manufacturer/icon/' . $sSubDir;
                $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/manufacturer/icon/' . $sSubDir;
                $cClass = 'actions';
                break;
            
            case 'vendmisspics':
                $sSql1 = "SELECT oxid, oxtitle, oxicon AS picname, filename, "
                        . "IF(oxicon='', 'OXPROBS_NOPIC_DEF',IF(filename IS NULL, 'OXPROBS_NOPIC_FOUND','')) AS status "
                    . "FROM oxvendor "
                    . "LEFT JOIN tmpimages "
                        . "ON oxicon = filename "
                        . "WHERE filename IS NULL "
                        . "AND " . $sWhere;
                $sSql2 = "";
                $sSubDir = str_replace('*','_',$myConfig->getConfigParam( 'sManufacturerIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/vendor/icon/' . $sSubDir;
                $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/vendor/icon/' . $sSubDir;
                $cClass = 'actions';
                break;

            case 'vendorphpics':
                $sSql1 = "SELECT oxicon, filename AS picname, 'OXPROBS_ORPHPIC_FOUND' AS status "
                        . "FROM tmpimages "
                        . "LEFT JOIN oxvendor "
                            . "ON oxicon = filename "
                            . "WHERE oxicon IS NULL "
                            . "AND (" . $sWhere . " OR oxshopid IS NULL) ";
                $sSql2 = "";
                $sSubDir = str_replace('*','_',$myConfig->getConfigParam( 'sManufacturerIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/vendor/icon/' . $sSubDir;
                $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/vendor/icon/' . $sSubDir;
                $cClass = 'actions';
                break;

            default:
                $sSql1 = "";
                break;

        }

        $i = 0;
        $aItems = array();
        
        $sSql = "CREATE TEMPORARY TABLE tmpimages ( filename VARCHAR(128) )";
        $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
        $rs = $oDb->Execute($sSql);
        
        //$dir = $this->pictureDir .'generated/manufacturer/icon/100_100_75';
        $files = scandir($sPictureDir);
            /*echo '<pre>';
            print_r($files);
            echo '</pre>';/**/
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
            echo '</pre>';/**/
        }
        
        $oSmarty->assign("editClassName", $cClass);
        $oSmarty->assign("pictureDir", $sPictureDir);
        $oSmarty->assign("pictureUrl", $sPictureUrl);
        $oSmarty->assign("aItems", $aItems);

         return $this->_sThisTemplate;
    }
    
}

?>