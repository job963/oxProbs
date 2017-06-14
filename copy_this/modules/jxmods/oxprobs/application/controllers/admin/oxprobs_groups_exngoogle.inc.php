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
 *    This include file supports the analysis of EXONN Google Merchant data for categories
 */
 
array_push( $aIncReports, array("name"  => "exngoogle", 
                                "title" => array("de"=>"Google Feed: Inaktive Kategorien",
                                                 "en"=>"Google Feed: Inactive Categories"), 
                                "desc"  => array("de"=>"Anzeige aller Kategorien, die nicht aktiv sind f&uuml; den Google Product Feed.",
                                                 "en"=>"Displays all categories which are not active for Google Product Feed.") 
                                ));
if ($cReportType == 'exngoogle') {
    $sSql1 = "SELECT c.oxid AS oxid, c.oxtitle AS oxtitle, "
                ."(SELECT COUNT(*) "
                    . "FROM oxarticles a, oxobject2category o2a "
                    . "WHERE a.oxid = o2a.oxobjectid AND c.oxid = o2a.oxcatnid AND a.oxactive = 1"
                . ") AS count, "
                . "g.oxid, g.active, g.googlecategory, "
                . "CONCAT_WS('|', "
                    . "IF(g.active IS NULL, 'OXPROBS_EXNGOOGLE_INACT', ''), "
                    . "IF(g.googlecategory = '', 'OXPROBS_EXNGOOGLE_CATEMPTY', '') ) "
                . 'AS status '
            . "FROM oxcategories c "
            . "LEFT JOIN exonn_googlem g "
                . "ON (c.oxid = g.oxid) "
            . "WHERE c.oxactive = 1 "
                . "AND (g.active IS NULL OR g.active = 0 OR g.googlecategory = '') ";
    $sSql2 = '';
}

?>