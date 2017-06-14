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
 * @copyright (C) Joachim Barthel 2012-2017
 *
 */

/*
 *    This include file supports the analysis manufacturers without assigned products
 */
 
array_push( $aIncReports, array("name"   => "jxmanswoprods", 
                                "title"  => array("de" => "Hersteller ohne Produkte",
                                                  "en" => "Manufacturers without Products"), 
                                "desc"   => array("de" => "Nachfolgende Hersteller sind keine Artikel zugeordnet",
                                                  "en" => "The following manufacturers aren't having any products assigned.")
                                ));

if ($cReportType == "jxmanswoprods") {
    $sWhereActive = " ";
    $sSql1 = "SELECT m.oxid, m.oxtitle, IFNULL(SUM(a.oxactive),0) AS count "
             . "FROM oxmanufacturers m "
             . "LEFT JOIN oxarticles a "
                . "ON m.oxid = a.oxmanufacturerid "
             . "WHERE m.oxactive = 1 "
             . "GROUP BY m.oxid "
             . "HAVING count = 0 "
             . "ORDER BY m.oxtitle";
    $sSql2 = '';
    $sortCol = '';
}

?>