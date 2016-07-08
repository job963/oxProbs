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
 * @copyright (C) Joachim Barthel 2012-2016
 *
 */

/*
 *    This include file supports the analysis of jxInventory module
 */
 
array_push( $aIncReports, array("name"  => "jxbimplus", 
                                "title" => array("de"=>"Neueste bim+ Abonnements",
                                                 "en"=>"Newest bim+ Subscriptions"), 
                                "desc"  => array("de"=>"Neueste Bestellungen",
                                                 "en"=>"Newest orders") 
                                ));

if ($cReportType == "jxbimplus") {
    $txtIgnoreRemark = $myConfig->getConfigParam("sOxProbsOrderIgnoredRemark");
                            
             
    $sSql1 = "SELECT o.oxid AS oxid, o.oxordernr AS orderno, o.oxtotalordersum AS ordersum, o.oxbillsal AS salutation, "
             . "CONCAT('<nobr>', o.oxbillcompany, '</nobr>') AS company, "
             . "CONCAT('<a href=\"mailto:', o.oxbillemail, '\" style=\"text-decoration:underline;\"><nobr>', o.oxbillfname, '&nbsp;', o.oxbilllname, '</nobr></a>') AS name, "
             . "IF (o.oxdelcity = '', "
                . "CONCAT('<a href=\"http://maps.google.com/maps?f=q&hl=de&geocode=&q=', o.oxbillstreet,'+',o.oxbillstreetnr,',+',o.oxbillzip,'+',o.oxbillcity,'&z=10\" style=\"text-decoration:underline;\" target=\"_blank\">', o.oxbillzip, '&nbsp;', o.oxbillcity, '</a>'), "
                . "CONCAT('<a href=\"http://maps.google.com/maps?f=q&hl=de&geocode=&q=', o.oxdelstreet,'+',o.oxdelstreetnr,',+',o.oxdelzip,'+',o.oxdelcity,'&z=10\" style=\"text-decoration:underline;\" target=\"_blank\">', o.oxdelzip, '&nbsp;', o.oxdelcity, '</a>') "
                . ") AS  custdeladdr, "
             . "u.smx_slug AS paytype, "
             . "GROUP_CONCAT(CONCAT('<nobr>', a.oxamount, ' x ', a.oxtitle, IF (a.oxselvariant != '', CONCAT(' &ndash; ', a.oxselvariant), ''), '</nobr>') SEPARATOR '<br>') AS orderlist, "
             . "(TO_DAYS(NOW())-TO_DAYS(o.oxorderdate)) AS days, DATE(o.oxorderdate) AS orderdate , "
             . "IF(o.oxremark!='', "
                . "IF((SELECT o.oxremark LIKE '{$txtIgnoreRemark}') != 1,"
                    . "o.oxremark, "
                    . "''"
                . "), "
                . "''"
             . ") AS remark "
         . "FROM oxorder o, oxorderarticles a, oxuser u "
         . "WHERE u.oxid = o.oxuserid "
             . "AND o.oxid = a.oxorderid  "
             . "AND u.smx_slug != '' "
             . "AND o.oxstorno = 0 "
             . $whereShopId
         . "GROUP BY o.oxordernr "
         . "ORDER BY o.oxordernr DESC "
         . "LIMIT 0,100"; 
 
    $sSql2 = '';
}

?>