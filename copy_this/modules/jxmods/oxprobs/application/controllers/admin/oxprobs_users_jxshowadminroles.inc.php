<?php

/*
 *    This file is part of the module oxProbs for OXID eShop Community Edition.
 *
 *    The module oxProbs for OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    The module oxProbs for OXID eShop Community Edition is distributed in the hope that it will be useful,
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
 *    This include file supports display of admin users
 */
 
if ( is_numeric($this->_aViewData["oViewConf"]->getActiveShopId()) ) {
    // This is a EE Shop
    array_push( $aIncReports, array("name"  => "jxshowadminroles", 
                                    "title" => array("de"=>"Admin Benutzer & Rollen anzeigen",
                                                     "en"=>"Show Admin Users & Roles "), 
                                    "desc"  => array("de"=>"Diese Benutzer sind als Administratoren registiert und den angezeigten Rollen zugeordnet.",
                                                     "en"=>"These users are registered as administrators and assigned to the shown roles.") 
                                    ));

    if ($cReportType == "jxshowadminroles") {
        $sName = "CONCAT(TRIM(u.oxfname), ' ', TRIM(u.oxlname), ', ', TRIM(u.oxcompany), ' (', u.oxusername, ')' )";
        $sMatch = "u.oxid";
        $sSql1 = "SELECT u.oxid AS oxid, u.oxactive AS oxactive, $sName AS name, u.oxrights AS amount, $sMatch AS matchstring "
               . "FROM oxuser u "
               . "WHERE u.oxrights != 'user' ";
        $sSql2 = "SELECT  u.oxid, u.oxactive, r.oxtitle AS oxusername, 0 AS oxdboptin "
                           . "FROM oxuser u, oxroles r, oxobject2role o2r "
                           . "WHERE $sMatch = '@MATCH@' "
                                . "AND u.oxid = o2r.oxobjectid AND o2r.oxroleid = r.oxid ";
        $cClass = 'admin_user';
    }
}
