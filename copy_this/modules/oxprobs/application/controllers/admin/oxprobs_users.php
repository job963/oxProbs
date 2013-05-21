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
 
class oxprobs_users extends oxAdminDetails
{
    protected $_sThisTemplate = "oxprobs_users.tpl";
    
    public function render()
    {
        ini_set('display_errors', true);

        parent::render();
        $oSmarty = oxUtilsView::getInstance()->getSmarty();
        $oSmarty->assign( "oViewConf", $this->_aViewData["oViewConf"]);
        $oSmarty->assign( "shop", $this->_aViewData["shop"]);

        $cReportType = isset($_POST['oxprobs_reporttype']) ? $_POST['oxprobs_reporttype'] : $_GET['oxprobs_reporttype']; 
        if (empty($cReportType))
            $cReportType = "dblname";
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
            $sWhere = $sWhere . " u.oxshopid = '$sShopId' ";
        }
        else {
            // This is a EE Shop
            $iShopId = $this->_aViewData["oViewConf"]->getActiveShopId();
            $sWhere = $sWhere . " u.oxshopid = $iShopId ";
            
        }
        
        switch ($cReportType) {
            case 'dblname':
                $sName = "CONCAT(TRIM(u.oxfname), ' ', TRIM(u.oxlname), ', ', TRIM(u.oxcity))";
                $sSql1 = "SELECT $sName AS name, COUNT(*) AS amount "
                       . "FROM oxuser u "
                       . "WHERE $sWhere "
                       . "GROUP BY name "
                       . "HAVING COUNT(*) > 1 ";
                $sSql2 = "SELECT u.oxid, u.oxactive, u.oxusername, n.oxdboptin "
                       . "FROM oxuser u, oxnewssubscribed n "
                       . "WHERE $sName = '@NAME@' "
                            . "AND u.oxid = n.oxuserid "
                            . "AND $sWhere ";
                $cClass = 'admin_user';
                break;

            case 'dbladdr':
                $sName = "CONCAT( REPLACE(REPLACE(REPLACE(u.oxstreet,'.',''),' ',''),'-','') , ', ', TRIM(u.oxcity))";
                $sSql1 = "SELECT $sName AS name, COUNT(*) AS amount "
                       . "FROM oxuser u "
                       . "WHERE $sWhere "
                       . "GROUP BY name "
                       . "HAVING COUNT(*) >  1";
                $sSql2 = "SELECT u.oxid, u.oxactive, u.oxusername, n.oxdboptin "
                       . "FROM oxuser u, oxnewssubscribed n "
                       . "WHERE $sName = '@NAME@' "
                            . "AND u.oxid = n.oxuserid "
                            . "AND $sWhere ";
                $cClass = 'admin_user';
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
        $aUsers = array();

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
                array_push($aUsers, $rs->fields);
                $rs->MoveNext();
            }
            /*echo '<pre>';
            print_r($aUsers);
            echo '</pre>';*/
        }
        
        if (!empty($sSql2)) {
            $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
            foreach ($aUsers as $key => $row) {
                $aLogins = array();
                $sSql = str_replace('@NAME@', $row['name'], $sSql2);
                /*echo '<pre>';
                echo $sSql;
                echo '</pre>'; /* */
                $rs = $oDb->Execute($sSql);
                while (!$rs->EOF) {
                    array_push($aLogins, $rs->fields);
                    $rs->MoveNext();
                }
                $aUsers[$key]['logins'] = $aLogins;
                /*echo '<pre>';
                print_r($aLogins);
                echo '</pre>';*/
            }
                /*echo '<pre>';
                print_r($aUsers);
                echo '</pre>';*/
            //$rs = $oDb->Execute($sSql2);
            //---old---$rs = oxDb::getDb(true)->Execute( $sSql2);
            //echo "<hr><pre>$sSql2</pre>";
            /*if (oxDb::getDb(true)->errorNo() != 0) {
                $oSmarty->assign ( "sqlErrNo", oxDb::getDb(true)->errorNo() );
                $oSmarty->assign ( "sqlErrMsg",  oxDb::getDb(true)->errorMsg().' in $sSql2' ) ;
            }
            else {
                while (!$rs->EOF) {
                    array_push($aArticles, $rs->fields);
                    $rs->MoveNext();
                }
            }*/
        }
        
        $oSmarty->assign("editClassName", $cClass);
        $oSmarty->assign("aUsers", $aUsers);

         return $this->_sThisTemplate;
    }
    
}

?>