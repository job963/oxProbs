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
        $aSubDir = array();
        $aSql = array();
        
        switch ($cReportType) {
            case 'manumisspics':
                $aSubDir[0] = 'icon/' . str_replace('*','_',$myConfig->getConfigParam( 'sManufacturerIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/manufacturer/';
                $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/manufacturer/';
                $aSql[0] = "SELECT oxid, oxactive, oxtitle, oxicon AS picname, filename, '@SUBDIR@' AS subdir, "
                        . "IF(oxicon='', 'OXPROBS_NOPIC_DEF',IF(filename IS NULL, 'OXPROBS_NOPIC_FOUND','')) AS status "
                    . "FROM oxmanufacturers "
                    . "LEFT JOIN tmpimages "
                        . "ON oxicon = filename "
                        . "WHERE filename IS NULL "
                        . "AND " . $sWhere;
                $sSql2 = "";
                $sortCol = 'oxtitle';
                $cClass = 'actions';
                break;

            case 'manuorphpics':
                $aSubDir[0] = 'icon/' . str_replace('*','_',$myConfig->getConfigParam( 'sManufacturerIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/manufacturer/';
                $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/manufacturer/';
                $aSql[0] = "SELECT oxicon, filename AS picname, '@SUBDIR@' AS subdir, 'OXPROBS_ORPHPIC_FOUND' AS status "
                        . "FROM tmpimages "
                        . "LEFT JOIN oxmanufacturers "
                            . "ON oxicon = filename "
                            . "WHERE oxicon IS NULL "
                            . "AND (" . $sWhere . " OR oxshopid IS NULL) ";
                $sSql2 = "";
                $sortCol = 'filename';
                $cClass = 'actions';
                break;
            
            case 'vendmisspics':
                $aSubDir[0] = 'icon/' . str_replace('*','_',$myConfig->getConfigParam( 'sManufacturerIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/vendor/';
                $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/vendor/';
                $aSql[0] = "SELECT oxid, oxactive, oxtitle, oxicon AS picname, filename, '@SUBDIR@' AS subdir, "
                        . "IF(oxicon='', 'OXPROBS_NOPIC_DEF',IF(filename IS NULL, 'OXPROBS_NOPIC_FOUND','')) AS status "
                    . "FROM oxvendor "
                    . "LEFT JOIN tmpimages "
                        . "ON oxicon = filename "
                        . "WHERE filename IS NULL "
                        . "AND " . $sWhere;
                $sSql2 = "";
                $sortCol = 'oxtitle';
                $cClass = 'actions';
                break;

            case 'vendorphpics':
                $aSubDir[0] = 'icon/' . str_replace('*','_',$myConfig->getConfigParam( 'sManufacturerIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/vendor/';
                $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/vendor/';
                $aSql[0] = "SELECT oxicon, filename AS picname, '@SUBDIR@' AS subdir, 'OXPROBS_ORPHPIC_FOUND' AS status "
                        . "FROM tmpimages "
                        . "LEFT JOIN oxvendor "
                            . "ON oxicon = filename "
                            . "WHERE oxicon IS NULL "
                            . "AND (" . $sWhere . " OR oxshopid IS NULL) ";
                $sSql2 = "";
                $sortCol = 'filename';
                $cClass = 'actions';
                break;
            
            case 'catmisspics':
                $aSubDir[0] = 'icon/' . str_replace('*','_',$myConfig->getConfigParam( 'sCatIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                $aSubDir[1] = 'thumb/' . str_replace('*','_',$myConfig->getConfigParam( 'sCatThumbnailsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                $aSubDir[2] = 'promo_icon/' . str_replace('*','_',$myConfig->getConfigParam( 'sCatPromotionsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/category/';
                $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/category/';
                $aSql[0] = "SELECT oxid, oxactive, oxtitle, oxicon AS picname, filename, '@SUBDIR@' AS subdir, "
                        . "IF(oxicon='', 'OXPROBS_NOPIC_DEF',IF(filename IS NULL, 'OXPROBS_NOPIC_FOUND','')) AS status "
                    . "FROM oxcategories "
                    . "LEFT JOIN tmpimages "
                        . "ON oxicon = filename "
                        . "WHERE filename IS NULL "
                        . "AND " . $sWhere;
                $aSql[1] = "SELECT oxid, oxactive, oxtitle, oxthumb AS picname, filename, '@SUBDIR@' AS subdir, "
                        . "IF(oxthumb='', 'OXPROBS_NOPIC_DEF',IF(filename IS NULL, 'OXPROBS_NOPIC_FOUND','')) AS status "
                    . "FROM oxcategories "
                    . "LEFT JOIN tmpimages "
                        . "ON oxthumb = filename "
                        . "WHERE filename IS NULL "
                        . "AND " . $sWhere;
                $aSql[2] = "SELECT oxid, oxactive, oxtitle, oxpromoicon AS picname, filename, '@SUBDIR@' AS subdir, "
                        . "IF(oxpromoicon='', 'OXPROBS_NOPIC_DEF',IF(filename IS NULL, 'OXPROBS_NOPIC_FOUND','')) AS status "
                    . "FROM oxcategories "
                    . "LEFT JOIN tmpimages "
                        . "ON oxpromoicon = filename "
                        . "WHERE filename IS NULL "
                        . "AND " . $sWhere;
                $sSql2 = "";
                $sortCol = 'oxtitle';
                $cClass = 'actions';
                break;

            case 'catorphpics':
                $aSubDir[0] = 'icon/' . str_replace('*','_',$myConfig->getConfigParam( 'sCatIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                $aSubDir[1] = 'thumb/' . str_replace('*','_',$myConfig->getConfigParam( 'sCatThumbnailsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                $aSubDir[2] = 'promo_icon/' . str_replace('*','_',$myConfig->getConfigParam( 'sCatPromotionsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/category/';
                $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/category/';
                $aSql[0] = "SELECT oxicon, filename AS picname, '@SUBDIR@' AS subdir, 'OXPROBS_ORPHPIC_FOUND' AS status "
                        . "FROM tmpimages "
                        . "LEFT JOIN oxcategories "
                            . "ON oxicon = filename "
                            . "WHERE oxicon IS NULL "
                            . "AND (" . $sWhere . " OR oxshopid IS NULL) ";
                $aSql[1] = "SELECT oxthumb, filename AS picname, '@SUBDIR@' AS subdir, 'OXPROBS_ORPHPIC_FOUND' AS status "
                        . "FROM tmpimages "
                        . "LEFT JOIN oxcategories "
                            . "ON oxthumb = filename "
                            . "WHERE oxthumb IS NULL "
                            . "AND (" . $sWhere . " OR oxshopid IS NULL) ";
                $aSql[2] = "SELECT oxpromoicon, filename AS picname, '@SUBDIR@' AS subdir, 'OXPROBS_ORPHPIC_FOUND' AS status "
                        . "FROM tmpimages "
                        . "LEFT JOIN oxcategories "
                            . "ON oxpromoicon = filename "
                            . "WHERE oxpromoicon IS NULL "
                            . "AND (" . $sWhere . " OR oxshopid IS NULL) ";
                $sSql2 = "";
                $sortCol = 'filename';
                $cClass = 'actions';
                break;
            
            case 'artmisspics':
                $aSubDir[0] = '1/' . str_replace('*','_',$myConfig->getConfigParam( 'sIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                $aSubDir[1] = 'icon/' . str_replace('*','_',$myConfig->getConfigParam( 'sIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                $aSubDir[2] = '1/' . str_replace('*','_',$myConfig->getConfigParam( 'sThumbnailsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                $aSubDir[3] = 'thumb/' . str_replace('*','_',$myConfig->getConfigParam( 'sThumbnailsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                $aDetailSize = $myConfig->getConfigParam( 'aDetailImageSizes' );
                //echo '<pre>'.print_r($aDetailSize).'</pre>';
                //echo '<pre>sThumbnailsize = '.$myConfig->getConfigParam( 'sThumbnailsize' ).'</pre>';
                //echo '<pre>oxpic1 = '.$aDetailSize['oxpic1'].'</pre>';
                $aSubDir[4] = '1/' . str_replace('*','_',$aDetailSize['oxpic1']) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                $aSubDir[5] = '1/' . str_replace('*','_',$myConfig->getConfigParam( 'sZoomImageSize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/product/';
                $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/product/';
                $aSql[0] = "SELECT oxid, oxactive, oxtitle, oxpic1 AS picname, filename, '@SUBDIR@' AS subdir, "
                        . "IF(oxicon='', 'OXPROBS_NOPIC_DEF',IF(filename IS NULL, 'OXPROBS_NOPIC_FOUND','')) AS status "
                    . "FROM oxarticles "
                    . "LEFT JOIN tmpimages "
                        . "ON oxpic1 = filename "
                        . "WHERE filename IS NULL "
                        . "AND oxparentid = '' "
                        . "AND oxicon = '' "
                        . "AND " . $sWhere;
                $aSql[1] = "SELECT oxid, oxactive, oxtitle, oxicon AS picname, filename, '@SUBDIR@' AS subdir, "
                        . "IF(oxicon='', 'OXPROBS_NOPIC_DEF',IF(filename IS NULL, 'OXPROBS_NOPIC_FOUND','')) AS status "
                    . "FROM oxarticles "
                    . "LEFT JOIN tmpimages "
                        . "ON oxicon = filename "
                        . "WHERE filename IS NULL "
                        . "AND oxparentid = '' "
                        . "AND oxicon != '' "
                        . "AND " . $sWhere;
                $aSql[2] = "SELECT oxid, oxactive, oxtitle, oxpic1 AS picname, filename, '@SUBDIR@' AS subdir, "
                        . "IF(oxthumb='', 'OXPROBS_NOPIC_DEF',IF(filename IS NULL, 'OXPROBS_NOPIC_FOUND','')) AS status "
                    . "FROM oxarticles "
                    . "LEFT JOIN tmpimages "
                        . "ON oxpic1 = filename "
                        . "WHERE filename IS NULL "
                        . "AND oxparentid = '' "
                        . "AND oxthumb = '' "
                        . "AND " . $sWhere;
                $aSql[3] = "SELECT oxid, oxactive, oxtitle, oxthumb AS picname, filename, '@SUBDIR@' AS subdir, "
                        . "IF(oxthumb='', 'OXPROBS_NOPIC_DEF',IF(filename IS NULL, 'OXPROBS_NOPIC_FOUND','')) AS status "
                    . "FROM oxarticles "
                    . "LEFT JOIN tmpimages "
                        . "ON oxthumb = filename "
                        . "WHERE filename IS NULL "
                        . "AND oxparentid = '' "
                        . "AND oxthumb != '' "
                        . "AND " . $sWhere;
                $aSql[4] = "SELECT oxid, oxactive, oxtitle, oxpic1 AS picname, filename, '@SUBDIR@' AS subdir, "
                        . "IF(oxpic1='', 'OXPROBS_NOPIC_DEF',IF(filename IS NULL, 'OXPROBS_NOPIC_FOUND','')) AS status "
                    . "FROM oxarticles "
                    . "LEFT JOIN tmpimages "
                        . "ON oxpic1 = filename "
                        . "WHERE filename IS NULL "
                        . "AND oxparentid = '' "
                        . "AND " . $sWhere;
                $aSql[5] = $aSql[4];
                $sSql2 = "";
                $sortCol = 'oxtitle';
                $cClass = 'actions';
                break;

            default:
                $sSql1 = "";
                break;

        }

        $i = 0;
        $aItems = array();
        
        $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
        
        foreach ($aSubDir as $key => $sSubDir) {
            $sSql = "DROP TEMPORARY TABLE IF EXISTS tmpimages";
            $rs = $oDb->Execute($sSql);
            $sSql = "CREATE TEMPORARY TABLE tmpimages ( filename VARCHAR(128) )";
            $rs = $oDb->Execute($sSql);

            //$dir = $this->pictureDir .'generated/manufacturer/icon/100_100_75';
            //echo '<pre>'.$sPictureDir.$aSubDir[$key].'</pre>';
            $files = scandir($sPictureDir.$aSubDir[$key]);
                /*echo '<pre>';
                print_r($files);
                echo '</pre>';/**/
            foreach ($files as $value) { 
               if ( !in_array($value,array(".","..")) ) { 
                    $sSql = "INSERT INTO tmpimages (filename) VALUES ('$value') ";
                    //echo $aSubDir[$key] .' --> '. $sSql.'<br>';
                    $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
                    $rs = $oDb->Execute($sSql);
               } 
            } 

            if (!empty($aSql[$key])) {
                //$oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
                $sSql = str_replace('@SUBDIR@', $aSubDir[$key], $aSql[$key]);
                $rs = $oDb->Execute($sSql);
                //---old---$rs = oxDb::getDb(true)->Execute($sSql1);
                /*echo '<pre>';
                echo $sSql;
                echo '</pre>';/**/
                 
                /*echo '<pre>';
                print_r($rs);
                echo '</pre>';/* */
                if (!empty($rs)){
                    while (!$rs->EOF) {
                        array_push($aItems, $rs->fields);
                        $rs->MoveNext();
                    }
                }
                /*echo '<pre>';
                print_r($aItems);
                echo '</pre>';/**/
            }
        }

        if ( (!empty($sortCol)) && (count($aItems)!=0) ) {
            foreach ($aItems as $key => $row) {
                $column1[$key]    = strtolower( $row[$sortCol] );
                $column2[$key]    = strtolower( $row['subdir'] );
            }
            $sortDir = SORT_ASC; //($sortOpt == 'ASC') ? SORT_ASC : SORT_DESC;
            array_multisort($column1, $sortDir, $column2, SORT_ASC, SORT_STRING, $aItems);
        }
        
        $oSmarty->assign("editClassName", $cClass);
        $oSmarty->assign("pictureDir", $sPictureDir);
        $oSmarty->assign("pictureUrl", $sPictureUrl);
        $oSmarty->assign("aItems", $aItems);

         return $this->_sThisTemplate;
    }
    
}

?>