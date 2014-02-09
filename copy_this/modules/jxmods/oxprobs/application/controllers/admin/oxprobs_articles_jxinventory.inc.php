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
 
array_push( $aIncReports, array("name"  => "jxinvempty", 
                                "title" => array("de"=>"Ware nicht auf Lager",
                                                 "en"=>"Product not in stock"), 
                                "desc"  => array("de"=>"Nachfolgende Artikel sind nicht auf Lager.<br />(Tats&auml;chlicher Lagerbestand).",
                                                 "en"=>"The following products are not in stock.<br />(Real inventory).") 
                                ));

if ($cReportType == "jxinvempty") {
    $sSql1 = "SELECT a.oxid, a.oxartnum, a.$this->ean AS oxean, a.oxmpn, "
                . "IF(a.oxparentid='',a.oxtitle,(SELECT a1.oxtitle FROM oxarticles a1 WHERE a1.oxid=a.oxparentid)) AS oxtitle, a.oxvarselect, "
                . "CONCAT('<span class=\"emphasize\">',i.jxinvstock,'</span>') AS oxstock, a.oxprice AS oxprice, "
                . "IF(a.oxparentid='',"
                    . "(SELECT m.oxtitle FROM oxmanufacturers m WHERE a.oxmanufacturerid = m.oxid),"
                    . "(SELECT m.oxtitle FROM oxarticles a1, oxmanufacturers m WHERE a.oxparentid = a1.oxid AND a1.oxmanufacturerid = m.oxid)"
                    . ") AS oxmantitle "
            . "FROM oxarticles a, jxinvarticles i "
            . "WHERE "
                . "a.oxid = i.jxartid "
                . "AND i.jxinvsite IS NOT NULL "
                . "AND i.jxinvstock = 0 "
                . $sWhere;
    $sSql2 = '';
}

?>