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
 *    This include file supports the analysis of jxGTaxo module
 */
 
array_push( $aIncReports, array("name"  => "jxgtaxoinfo", 
                                "title" => array("de"=>"Artikel ohne Google Taxonomie",
                                                 "en"=>"Product without Google Taxonomy"), 
                                "desc"  => array("de"=>"Die folgenden aktiven Eltern-Artikel sind ohne Google Taxonomie Zuordnung.",
                                                 "en"=>"The following active parent product are without a Google taxonomy assignment.") 
                                ));

if ($cReportType == "jxgtaxoinfo") {
    $sSql1 = "SELECT a.oxid, $sActive, a.oxartnum, a.$this->ean AS oxean, a.oxmpn, "
                . "a.oxtitle, a.oxvarselect, "
                . "a.oxstock, a.oxprice AS oxprice, "
                . "(SELECT "
                        . "CONCAT (c.oxtitle , ' = ', c.jxgoogletaxonomy) "
                    . "FROM oxcategories c, oxobject2category o2c "
                    . "WHERE o2c.oxobjectid = a.oxid AND o2c.oxcatnid = c.oxid "
                    . "ORDER BY o2c.oxtime "
                    . "LIMIT 1) "
                . "AS infotext, "
                . "(SELECT m.oxtitle FROM oxmanufacturers m WHERE a.oxmanufacturerid = m.oxid) AS oxmantitle "
            . "FROM oxarticles a "
            . "WHERE a.oxparentid = '' "
                . "AND (SELECT c.jxgoogletaxonomy "
                    . "FROM oxcategories c, oxobject2category o2c "
                    . "WHERE o2c.oxobjectid = a.oxid AND o2c.oxcatnid = c.oxid "
                    . "ORDER BY o2c.oxtime "
                    . "LIMIT 1) = '' "
                . $sWhereActive
                . $sWhere;
    $sSql2 = '';
}

?>