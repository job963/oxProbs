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
 *    This include file supports the analysis of EXONN Google Merchant data for products
 */
 
array_push( $aIncReports, array("name"  => "exngoogle", 
                                "title" => array("de"=>"Inaktiv f&uuml;r Google Feed",
                                                 "en"=>"Inactive for Google Feed"), 
                                "desc"  => array("de"=>"Anzeige aller Artikel, die nicht aktiv sind f&uuml;r den Google Product Feed.",
                                                 "en"=>"Displays all products which are not active for Google Product Feed.") 
                                ));
if ($cReportType == 'exngoogle') {
    $sSql1 = "SELECT a.oxid AS oxid, a.oxartnum AS oxartnum, a.$this->ean AS oxean, "
                ."IF(a.oxparentid='',a.oxtitle,(SELECT a1.oxtitle FROM oxarticles a1 WHERE a.oxparentid=a1.oxid)) AS oxtitle, "
                . "a.oxvarselect AS oxvarselect, a.oxmpn AS oxmpn, a.oxstock AS oxstock, a.oxprice AS oxprice, "
                . "IF(a.oxmanufacturerid='','',(SELECT m.oxtitle FROM oxmanufacturers m WHERE a.oxmanufacturerid=m.oxid)) AS oxmantitle, "
                . "g.oxid AS exn_oxid, g.active AS exn_active "
            . "FROM oxarticles a "
                . "LEFT JOIN exonn_googlem g ON (a.oxid=g.oxid) "
            . "WHERE  a.oxactive=1 "
                . "AND a.oxvarcount=0 "
                . "AND a.oxstock>0 "
                . "AND (g.active IS NULL OR g.active = 0) "
                . $sWhere;
    $sSql2 = '';
}

?>