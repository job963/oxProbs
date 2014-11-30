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
 * $Id: oxprobs_pictures.php jobarthel@gmail.com $
 *
 */
 
class oxprobs_pictures extends oxAdminView
{
    protected $_sThisTemplate = "oxprobs_pictures.tpl";
    
    public function render()
    {
        ini_set('display_errors', true);

        parent::render();
        $myConfig = oxRegistry::get("oxConfig");
        
        $aIncFiles = array();
        $aIncReports = array();
        $aIncFiles = $myConfig->getConfigParam( 'aOxProbsPicturesIncludeFiles' );
        $sIncPath = $this->jxGetModulePath() . '/application/controllers/admin/';
        if (count($aIncFiles) > 0) {
            foreach ($aIncFiles as $sIncFile) { 
                $sIncFile = $sIncPath . 'oxprobs_pictures_' . $sIncFile . '.inc.php';
                require $sIncFile;
            }
        }

        $cReportType = $this->getConfig()->getRequestParameter( 'oxprobs_reporttype' );
        if (empty($cReportType))
            $cReportType = "manumisspics";
        $this->_aViewData["ReportType"] = $cReportType;
        
        $this->picDirs = $myConfig->getConfigParam("sOxProbsPictureDirs");
        switch ($cReportType) {
            case 'manumisspics':
                if ($this->picDirs == 'generated') {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/manufacturer/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/manufacturer/';
                }
                else {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'master/manufacturer/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'master/manufacturer/';
                }
                break;
            case 'manuorphpics':
                if ($this->picDirs == 'generated') {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/manufacturer/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/manufacturer/';
                }
                else {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'master/manufacturer/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'master/manufacturer/';
                }
                break;
                
            case 'vendmisspics':
                if ($this->picDirs == 'generated') {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/vendor/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/vendor/';
                } 
                else {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'master/vendor/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'master/vendor/';
                }
                break;

            case 'vendorphpics':
                if ($this->picDirs == 'generated') {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/vendor/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/vendor/';
                }
                else {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'master/vendor/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'master/vendor/';
                }
                break;
            
            case 'catmisspics':
                if ($this->picDirs == 'generated') {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/category/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/category/';
                }
                else {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'master/category/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'master/category/';
                }
                break;

            case 'catorphpics':
                if ($this->picDirs == 'generated') {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/category/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/category/';
                }
                else {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'master/category/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'master/category/';
                }
                break;
            
            case 'artmisspics':
                if ($this->picDirs == 'generated') {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/product/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/product/';
                }
                else {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'master/product/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'master/product/';
                }
                break;
            
            case 'artorphpics':
                if ($this->picDirs == 'generated') {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/product/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/product/';
                }
                else {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'master/product/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'master/product/';
                }
                break;
        }
                
        $aItems = array();
        $aItems = $this->_retrieveData();
        
        $oModule = oxNew('oxModule');
        $oModule->load('oxprobs');
        $this->_aViewData["sModuleId"] = $oModule->getId();
        $this->_aViewData["sModuleVersion"] = $oModule->getInfo('version');
        $this->_aViewData["sIsoLang"] = oxRegistry::getLang()->getLanguageAbbr($iLang);

        $this->_aViewData["editClassName"] = $cClass;
        $this->_aViewData["pictureDir"] = $sPictureDir;
        $this->_aViewData["pictureUrl"] = $sPictureUrl;
        $this->_aViewData["aItems"] = $aItems;
        $this->_aViewData["aIncReports"] = $aIncReports;

         return $this->_sThisTemplate;
    }
     
    
    public function downloadResult()
    {
        $aItems = array();
        $aItems = $this->_retrieveData();

        $aSelOxid = $this->getConfig()->getRequestParameter( "oxprobs_oxid" ); 
        
        $sContent = '';
        foreach ($aItems as $aItem) {
            if ( in_array($aItem['oxid'], $aSelOxid) ) {
                $sContent .= '"' . implode('","', $aItem) . '"' . chr(13);
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
        
        $cReportType = $this->getConfig()->getRequestParameter( 'oxprobs_reporttype' );
        if (empty($cReportType))
            $cReportType = "manumisspics";

        $myConfig = oxRegistry::get("oxConfig");
        $this->ean = $myConfig->getConfigParam("sOxProbsEANField");
        $this->minDescLen = (int) $myConfig->getConfigParam("sOxProbsMinDescLen");
        $this->bpriceMin = (float) $myConfig->getConfigParam("sOxProbsBPriceMin");
        $this->picDirs = $myConfig->getConfigParam("sOxProbsPictureDirs");

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
                if ($this->picDirs == 'generated') {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/manufacturer/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/manufacturer/';
                    $aSubDir[0] = 'icon/' . str_replace('*','_',$myConfig->getConfigParam( 'sManufacturerIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                }
                else {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'master/manufacturer/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'master/manufacturer/';
                    $aSubDir[0] = 'icon';
                }
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
                if ($this->picDirs == 'generated') {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/manufacturer/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/manufacturer/';
                    $aSubDir[0] = 'icon/' . str_replace('*','_',$myConfig->getConfigParam( 'sManufacturerIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                }
                else {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'master/manufacturer/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'master/manufacturer/';
                    $aSubDir[0] = 'icon';
                }
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
                if ($this->picDirs == 'generated') {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/vendor/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/vendor/';
                    $aSubDir[0] = 'icon/' . str_replace('*','_',$myConfig->getConfigParam( 'sManufacturerIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                } 
                else {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'master/vendor/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'master/vendor/';
                    $aSubDir[0] = 'icon';
                }
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
                if ($this->picDirs == 'generated') {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/vendor/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/vendor/';
                    $aSubDir[0] = 'icon/' . str_replace('*','_',$myConfig->getConfigParam( 'sManufacturerIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                }
                else {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'master/vendor/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'master/vendor/';
                    $aSubDir[0] = 'icon';
                }
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
                if ($this->picDirs == 'generated') {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/category/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/category/';
                    $aSubDir[0] = 'icon/' . str_replace('*','_',$myConfig->getConfigParam( 'sCatIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                    $aSubDir[1] = 'thumb/' . str_replace('*','_',$myConfig->getConfigParam( 'sCatThumbnailsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                    $aSubDir[2] = 'promo_icon/' . str_replace('*','_',$myConfig->getConfigParam( 'sCatPromotionsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                }
                else {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'master/category/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'master/category/';
                    $aSubDir[0] = 'icon';
                    $aSubDir[1] = 'thumb';
                    $aSubDir[2] = 'promo_icon';
                }
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
                if ($this->picDirs == 'generated') {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/category/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/category/';
                    $aSubDir[0] = 'icon/' . str_replace('*','_',$myConfig->getConfigParam( 'sCatIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                    $aSubDir[1] = 'thumb/' . str_replace('*','_',$myConfig->getConfigParam( 'sCatThumbnailsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                    $aSubDir[2] = 'promo_icon/' . str_replace('*','_',$myConfig->getConfigParam( 'sCatPromotionsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                }
                else {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'master/category/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'master/category/';
                    $aSubDir[0] = 'icon';
                    $aSubDir[1] = 'thumb';
                    $aSubDir[2] = 'promo_icon';
                }
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
                if ($this->picDirs == 'generated') {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/product/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/product/';
                    $aSubDir[0] = 'icon/' . str_replace('*','_',$myConfig->getConfigParam( 'sIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                    $aSubDir[1] = 'thumb/' . str_replace('*','_',$myConfig->getConfigParam( 'sThumbnailsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                    $aDetailSize = $myConfig->getConfigParam( 'aDetailImageSizes' );
                    $aSubDir[2] = '1/' . str_replace('*','_',$aDetailSize['oxpic1']) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                    $aSubDir[3] = '1/' . str_replace('*','_',$myConfig->getConfigParam( 'sIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                    $aSubDir[4] = '1/' . str_replace('*','_',$myConfig->getConfigParam( 'sThumbnailsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                    $aSubDir[5] = '1/' . str_replace('*','_',$myConfig->getConfigParam( 'sZoomImageSize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                }
                else {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'master/product/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'master/product/';
                    $aSubDir[0] = 'icon';
                    $aSubDir[1] = 'thumb';
                    $aSubDir[2] = '1';
                }
                $aSql[0] = "SELECT oxid, oxactive, oxtitle, oxicon AS picname, filename, '@SUBDIR@' AS subdir, "
                        . "IF(oxicon='', 'OXPROBS_NOPIC_DEF',IF(filename IS NULL, 'OXPROBS_NOPIC_FOUND','')) AS status "
                    . "FROM oxarticles "
                    . "LEFT JOIN tmpimages "
                        . "ON oxicon = filename "
                        . "WHERE filename IS NULL "
                        . "AND oxparentid = '' "
                        . "AND oxicon != '' "
                        . "AND " . $sWhere;
                $aSql[1] = "SELECT oxid, oxactive, oxtitle, oxthumb AS picname, filename, '@SUBDIR@' AS subdir, "
                        . "IF(oxthumb='', 'OXPROBS_NOPIC_DEF',IF(filename IS NULL, 'OXPROBS_NOPIC_FOUND','')) AS status "
                    . "FROM oxarticles "
                    . "LEFT JOIN tmpimages "
                        . "ON oxthumb = filename "
                        . "WHERE filename IS NULL "
                        . "AND oxparentid = '' "
                        . "AND oxthumb != '' "
                        . "AND " . $sWhere;
                $aSql[2] = "SELECT oxid, oxactive, oxtitle, oxpic1 AS picname, filename, '@SUBDIR@' AS subdir, "
                        . "IF(oxpic1='', 'OXPROBS_NOPIC_DEF',IF(filename IS NULL, 'OXPROBS_NOPIC_FOUND','')) AS status "
                    . "FROM oxarticles "
                    . "LEFT JOIN tmpimages "
                        . "ON oxpic1 = filename "
                        . "WHERE filename IS NULL "
                        . "AND oxparentid = '' "
                        . "AND " . $sWhere;
                $aSql[3] = "SELECT oxid, oxactive, oxtitle, oxpic1 AS picname, filename, '@SUBDIR@' AS subdir, "
                        . "IF(oxicon='', 'OXPROBS_NOPIC_DEF',IF(filename IS NULL, 'OXPROBS_NOPIC_FOUND','')) AS status "
                    . "FROM oxarticles "
                    . "LEFT JOIN tmpimages "
                        . "ON oxpic1 = filename "
                        . "WHERE filename IS NULL "
                        . "AND oxparentid = '' "
                        . "AND oxicon = '' "
                        . "AND " . $sWhere;
                $aSql[4] = "SELECT oxid, oxactive, oxtitle, oxpic1 AS picname, filename, '@SUBDIR@' AS subdir, "
                        . "IF(oxthumb='', 'OXPROBS_NOPIC_DEF',IF(filename IS NULL, 'OXPROBS_NOPIC_FOUND','')) AS status "
                    . "FROM oxarticles "
                    . "LEFT JOIN tmpimages "
                        . "ON oxpic1 = filename "
                        . "WHERE filename IS NULL "
                        . "AND oxparentid = '' "
                        . "AND oxthumb = '' "
                        . "AND " . $sWhere;
                $aSql[5] = $aSql[2];
                $sSql2 = "";
                $sortCol = 'oxtitle';
                $cClass = 'actions';
                break;
            
            case 'artorphpics':
                if ($this->picDirs == 'generated') {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'generated/product/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'generated/product/';
                    $aSubDir[0] = 'icon/' . str_replace('*','_',$myConfig->getConfigParam( 'sIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                    $aSubDir[1] = 'thumb/' . str_replace('*','_',$myConfig->getConfigParam( 'sThumbnailsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                    $aDetailSize = $myConfig->getConfigParam( 'aDetailImageSizes' );
                    $j = 2;
                    for ($i=1; $i<=12; $i++) {
                        $aSubDir[$j++] = "$i/" . str_replace('*','_',$aDetailSize["oxpic$i"]) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                        $aSubDir[$j++] = "$i/" . str_replace('*','_',$myConfig->getConfigParam( 'sIconsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                        $aSubDir[$j++] = "$i/" . str_replace('*','_',$myConfig->getConfigParam( 'sThumbnailsize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                        $aSubDir[$j++] = "$i/" . str_replace('*','_',$myConfig->getConfigParam( 'sZoomImageSize' )) . '_' . $myConfig->getConfigParam( 'sDefaultImageQuality' );
                    }
                }
                else {
                    $sPictureDir = $myConfig->getPictureDir(FALSE) . 'master/product/';
                    $sPictureUrl = $myConfig->getPictureUrl(FALSE) . 'master/product/';
                    $aSubDir[0] = 'icon';
                    $aSubDir[1] = 'thumb';
                    $j = 2;
                    for ($i=1; $i<=12; $i++) {
                        $aSubDir[$j++] = "$i";
                    }
                }
                $aSql[0] = "SELECT oxicon, filename AS picname, '@SUBDIR@' AS subdir, 'OXPROBS_ORPHPIC_FOUND' AS status "
                        . "FROM tmpimages "
                        . "LEFT JOIN oxarticles "
                            . "ON oxicon = filename "
                            . "WHERE oxicon IS NULL "
                            . "AND (" . $sWhere . " OR oxshopid IS NULL) ";
                $aSql[1] = "SELECT oxthumb, filename AS picname, '@SUBDIR@' AS subdir, 'OXPROBS_ORPHPIC_FOUND' AS status "
                        . "FROM tmpimages "
                        . "LEFT JOIN oxarticles "
                            . "ON oxthumb = filename "
                            . "WHERE oxthumb IS NULL "
                            . "AND (" . $sWhere . " OR oxshopid IS NULL) ";
                $j = 2;
                for ($i=1; $i<=12; $i++) {
                    $aSql[$j++] = "SELECT oxpic$i, filename AS picname, '@SUBDIR@' AS subdir, 'OXPROBS_ORPHPIC_FOUND' AS status "
                            . "FROM tmpimages "
                            . "LEFT JOIN oxarticles "
                                . "ON oxpic$i = filename "
                                . "WHERE oxpic$i IS NULL "
                                . "AND (" . $sWhere . " OR oxshopid IS NULL) ";
                    if ($this->picDirs == 'generated') {
                        $k = $j-1;
                        $aSql[$j++] = $aSql[$k];
                        //echo '<pre>jjj '.$j.'-'.$k.'-'.$aSql[$j].'</pre>';
                        $aSql[$j++] = $aSql[$k];
                        $aSql[$j++] = $aSql[$k];
                    }
                }
                $sSql2 = "";
                $sortCol = 'filename';
                $cClass = 'actions';
                break;

            default:
                $sSql1 = "";
                $aIncFiles = array();
                $aIncReports = array();
                if (count($myConfig->getConfigParam("aOxProbsPicturesIncludeFiles")) != 0) {
                    $aIncFiles = $myConfig->getConfigParam("sOxProbsPicturesIncludeFiles");
                    $sIncPath = $this->jxGetModulePath() . '/application/controllers/admin/';
                    foreach ($aIncFiles as $sIncFile) { 
                        $sIncFile = $sIncPath . 'oxprobs_pictures_' . $sIncFile . '.inc.php';
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

        $i = 0;
        $aItems = array();
        
        $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
        
        foreach ($aSubDir as $key => $sSubDir) {
            $sSql = "DROP TEMPORARY TABLE IF EXISTS tmpimages";
            $rs = $oDb->Execute($sSql);
            $sSql = "CREATE TEMPORARY TABLE tmpimages ( filename VARCHAR(128) )";
            $rs = $oDb->Execute($sSql);

            if (is_dir($sPictureDir.$aSubDir[$key])) {
                $files = scandir($sPictureDir.$aSubDir[$key]);
                //echo '<pre>'.$key.': '.$sPictureDir.$aSubDir[$key].' = '.count($files).'</pre>';

                foreach ($files as $value) { 
                   if ( !in_array($value,array(".","..","dir.txt")) ) { 
                        $sSql = "INSERT INTO tmpimages (filename) VALUES ('$value') ";
                        //echo '<pre>'.$aSubDir[$key].'/'.$value.'</pre>';
                        $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
                        $rs = $oDb->Execute($sSql);
                   } 
                } 

                if (!empty($aSql[$key])) {
                    $sSql = str_replace('@SUBDIR@', $aSubDir[$key], $aSql[$key]);
                    //echo '<pre>'.$key.': '.$sSql.'</pre>';
                    $rs = $oDb->Execute($sSql);

                    if (!empty($rs)){
                        while (!$rs->EOF) {
                            array_push($aItems, $rs->fields);
                            $rs->MoveNext();
                        }
                    }
                }
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
        
        return $aItems;
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