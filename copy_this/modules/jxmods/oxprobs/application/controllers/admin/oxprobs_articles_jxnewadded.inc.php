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
 * @link      https://github.com/job963/oxProbs
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @copyright (C) Joachim Barthel 2012-2014
 *
 */

/*
 *    This include file supports the analysis of jxInventory module
 */
 
array_push( $aIncReports, array("name"  => "jxnewadded", 
                                "title" => array("de"=>"Neue Artikel",
                                                 "en"=>"New Products"), 
                                "desc"  => array("de"=>"Nachfolgende Artikel sind neu im Sortiment.",
                                                 "en"=>"The following products are new in the product range.") 
                                ));

if ($cReportType == "jxnewadded") {
    $sSql1 = "SELECT a.oxid, a.oxartnum, a.$this->ean AS oxean, a.oxmpn, "
                . "IF(a.oxparentid='',a.oxtitle,(SELECT a1.oxtitle FROM oxarticles a1 WHERE a1.oxid=a.oxparentid)) AS oxtitle, a.oxvarselect, "
                . "a.oxstock, a.oxprice AS oxprice, "
                . "IF(a.oxparentid='',"
                    . "(SELECT m.oxtitle FROM oxmanufacturers m WHERE a.oxmanufacturerid = m.oxid),"
                    . "(SELECT m.oxtitle FROM oxarticles a1, oxmanufacturers m WHERE a.oxparentid = a1.oxid AND a1.oxmanufacturerid = m.oxid)"
                    . ") AS oxmantitle, "
                . "IF(a.oxparentid='',"
                    . "IF(a.oxicon!='',"
                        . "CONCAT('{$sPictureUrl}/icon/',a.oxicon),"
                        . "IF(a.oxpic1!='',CONCAT('{$sPictureUrl}/1/',a.oxpic1),'')),"
                    . "(SELECT "
                        . "IF(b.oxicon!='',"
                            . "CONCAT('{$sPictureUrl}/icon/',b.oxicon),"
                            . "IF(b.oxpic1!='',CONCAT('{$sPictureUrl}/1/',b.oxpic1),'')) "
                        . "FROM oxarticles b "
                        . "WHERE a.oxparentid = b.oxid) ) "
                . "AS picname "
            . "FROM oxarticles a "
            . "WHERE DATEDIFF(NOW(),a.oxinsert) <= 30 "
                . "AND a.oxvarcount = 0 "
                . $sWhere;
    $sSql2 = '';
}

?>