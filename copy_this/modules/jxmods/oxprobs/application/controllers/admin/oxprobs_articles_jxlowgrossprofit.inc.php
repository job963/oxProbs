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
 *    This include file supports the analysis of low gross profit
 */
 
array_push( $aIncReports, array("name"   => "jxlowgrossprofit", 
                                "title"  => array("de" => "Geringe Rohertragsmarge",
                                                  "en" => "Low gross profit margin"), 
                                "desc"   => array("de" => "Nachfolgende Artikel weisen einen geringen Rohertrag auf.<br />(aufsteigend sortiert nach Rohertrag).",
                                                  "en" => "The following products are having a low gross profit.<br />(ascending ordered by gross profit)."), 
                                "extcol" => array("de" => "Rohertragsmarge",
                                                  "en" => "Gross profit margin") 
                                ));

if ($cReportType == "jxlowgrossprofit") {
    //$sWhereActive = "a.oxactivefrom != '0000-00-00 00:00:00' AND a.oxactivefrom < NOW() AND a.oxactiveto != '0000-00-00 00:00:00' AND a.oxactiveto < NOW() ";
    $sWhereActive = " ";
    $sSql1 = "SELECT a.oxid, IF(a.oxactive=1,a.oxactive,IF(NOW()>=a.oxactivefrom AND NOW() <=a.oxactiveto,2,0)) AS oxactive , 
	a.oxartnum, a.oxean AS oxean, a.oxmpn, IF(a.oxparentid='',a.oxtitle,(SELECT a1.oxtitle FROM oxarticles a1 WHERE a1.oxid=a.oxparentid)) AS oxtitle, 
	a.oxvarselect, a.oxstock AS oxstock, 
	ROUND(IF(a.oxparentid='',
		a.oxprice/a.oxbprice/1.19*100,
		IF(a.oxprice!=0,a.oxprice,(select a2.oxprice FROM oxarticles a2 WHERE a2.oxid=a.oxparentid))/IF(a.oxbprice!=0,a.oxbprice,(select a2.oxbprice FROM oxarticles a2 WHERE a2.oxid=a.oxparentid))/1.19*100
		),1) AS infotext, 
	a.oxbprice AS oxbprice, a.oxprice AS oxprice, 
	IF(a.oxparentid='',(SELECT m.oxtitle FROM oxmanufacturers m WHERE a.oxmanufacturerid = m.oxid),(SELECT m.oxtitle FROM oxarticles a1, oxmanufacturers m WHERE a.oxparentid = a1.oxid AND a1.oxmanufacturerid = m.oxid)) AS oxmantitle 
FROM oxarticles a 
WHERE a.oxshopid = 'oxbaseshop' AND oxactive = 1 AND a.oxvarcount = 0 
ORDER BY infotext ASC
 ";
/*            . "WHERE "
                . $sWhereActive
                . $sWhere;*/
    $sSql2 = '';
    $sortCol = 'infotext';
}

?>