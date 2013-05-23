<?php
$sLangName  = "English";
$iLangNr    = 1;
$aLang = array(
    'oxprobs_module'             => 'Problematic Data',
    'oxprobs_displayarticles'    => 'Articles',
    'oxprobs_displaygroups'      => 'Arrangements',
    
    'OXPROBS_NOSTOCK'            => 'No stock',
    'OXPROBS_NOARTNUM'           => 'No Art.No',
    'OXPROBS_NOSHORTDESC'        => 'Insufficient Short Description',
    'OXPROBS_NOPIC'              => 'No product picture',
    'OXPROBS_DBLACTIVE'          => 'Double activated',
    'OXPROBS_LONGPERIOD'         => 'Too large period',
    'OXPROBS_INVPERIOD'          => 'Invalid period',
    'OXPROBS_NOPRICE'            => 'No sales price',
    'OXPROBS_NOBUYPRICE'         => 'Insufficient buying price',
    'OXPROBS_NOEAN'              => 'No EAN',
    'OXPROBS_EANCHK'             => 'Wrong EAN',
    'OXPROBS_NOMPN'              => 'No manufacturer Art.No.',
    'OXPROBS_NOCAT'              => 'No catergory assigned',
    'OXPROBS_ORPHAN'             => 'Orphaned variants',
    'OXPROBS_NODESC'             => 'Do description',
    'OXPROBS_NOMANU'             => 'No manufacturer',
    'OXPROBS_NOVEND'             => 'No vendor',
    'OXPROBS_NOSTOCK_INFO'       => 'The following articles and variants are having no or a negative stock. <br />'
                                  . '(only articles with status = standard will be listed)',
    'OXPROBS_NOARTNUM_INFO'      => 'The following articles and variants are having no article number.',
    'OXPROBS_NOSHORTDESC_INFO'   => 'The following articles and variants are having no or an insufficient short description',
    'OXPROBS_NOPIC_INFO'         => 'The following articles and variants are having no product picture or just the dummy picture.',
    'OXPROBS_DBLACTIVE_INFO'     => 'The following articles and variants are double activated (as well by the option as by the time period).',
    'OXPROBS_LONGPERIOD_INFO'    => 'The following articles and variants are active for a too large time period.',
    'OXPROBS_INVPERIOD_INFO'     => 'The following articles and variants are active for an invalid time period.',
    'OXPROBS_NOPRICE_INFO'       => 'The following articles and variants are having no or a negative sales price.',
    'OXPROBS_NOBUYPRICE_INFO'    => 'The following articles and variants are having no or a insufficient buying price.',
    'OXPROBS_NOEAN_INFO'         => 'The following articles and variants are having no EAN value.',
    'OXPROBS_EANCHK_INFO'        => 'The following articles and variants are having a wrong EAN value.',
    'OXPROBS_NOMPN_INFO'         => 'The following articles and variants are having no manufacturer article number.',
    'OXPROBS_NOCAT_INFO'         => 'The following articles and variants are not assigned to a category. <br />'
                                  . '(these articles might be exported to price search engines, but they aren\'t visible in the shop)',
    'OXPROBS_ORPHAN_INFO'        => 'The following variants are active, but their parent articles are deactivated. <br />'
                                  . '(these articles might be exported to price search engines, but they aren\'t visible in the shop)',
    'OXPROBS_NODESC_INFO'        => 'The following articles and variants are without a description.',
    'OXPROBS_NOMANU_INFO'        => 'The following articles are without a manufacturer.',
    'OXPROBS_NOVEND_INFO'        => 'The following articles are without a vendor.',

    'OXPROBS_INVACTIONS'         => 'Invalid actions',
    'OXPROBS_INVCATS'            => 'Invalid categories',
    'OXPROBS_INVMAN'             => 'Invalid manufacturers',
    'OXPROBS_INVVEN'             => 'Invalid distributors',
    'OXPROBS_INVACTIONS_INFO'    => 'The following actions are expired, prospective or deactived. <br />'
                                  . 'But they are containing active articles which can be ordered anyway.',
    'OXPROBS_INVCATS_INFO'       => 'The following categories, their parents or their grandparents are deactivated. <br />'
                                  . 'But they are containing active articles which can be ordered anyway.',
    'OXPROBS_INVMAN_INFO'        => 'The following manufacturer are deactivated or don\'t have an icon assigned. <br />'
                                  . 'But they are containing active articles which can be ordered anyway.',
    'OXPROBS_INVVEN_INFO'        => 'The following distributors are deactivated or don\'t have an icon assigned. <br />'
                                  . 'But they are containing active articles which can be ordered anyway.',
    'OXPROBS_DEACT_ACT'          => 'Deactivated action',
    'OXPROBS_EXP_ACT'            => 'Expired action',
    'OXPROBS_PROSPEC_ACT'        => 'Prospective action',
    'OXPROBS_DEACT_CATS'         => 'Deactivated categories',
    'OXPROBS_DEACT_PARENTCAT'    => 'Deactivated parents categories',
    'OXPROBS_DEACT_GRANDCAT'     => 'Deactivated grandparents categories',
    'OXPROBS_DEACT_MAN'          => 'Deactivated manufacturer',
    'OXPROBS_NOICON_MAN'         => 'No icon assigned',
    'OXPROBS_DEACT_VEN'          => 'Deactivated distributor',
    'OXPROBS_NOICON_VEN'         => 'No icon assigned',

    'OXPROBS_DELSETCOST'         => 'Shipping methods and costs',
    'OXPROBS_DELSETPAY'          => 'Shipping and payment methods',
    'OXPROBS_STATE'              => 'State',
    'OXPROBS_DELSETCOST_INFO'    => '',
    'OXPROBS_DELSETPAY_INFO'     => '',
    
    'OXPROBS_USRDBL_NAME'        => 'Gleicher Name und Ort',
    'OXPROBS_USRDBL_ADDR'        => 'Gleiche Stra&szlig;e und Ort',
    'OXPROBS_USRDBL_NAME_INFO'   => 'Nachfolgende Benutzer sind mehrfach mit gleichem Namen und Ort im Shop vorhanden (aber mit unterschiedlichen Logins).',
    'OXPROBS_USRDBL_ADDR_INFO'   => 'Nachfolgende Benutzer sind mehrfach mit gleicher Stra&szlig;e und Ort im Shop vorhanden (aber mit unterschiedlichen Logins).',
    'OXPROBS_LOGINS'             => 'Logins',

    'OXPROBS_MANU_MISSPICS'      => 'Hersteller ohne Bilder',
    'OXPROBS_MANU_ORPHPICS'      => 'Verwaiste Hersteller-Bilder',
    'OXPROBS_VEND_MISSPICS'      => 'Lieferant ohne Bilder',
    'OXPROBS_VEND_ORPHPICS'      => 'Verwaiste Lieferanten-Bilder',
    'OXPROBS_MANU_MISSPICS_INFO' => 'Nachfolgende Hersteller sind keine Bilder zugeordnet oder das zugeordnete Bild wurde nicht gefunden.<br>Pfad:',
    'OXPROBS_MANU_ORPHPICS_INFO' => 'Nachfolgende Bild-Dateien sind keinem Lieferanten zugeordnet.<br>Pfad:',
    'OXPROBS_VEND_MISSPICS_INFO' => 'Nachfolgende Lieferanten sind keine Bilder zugeordnet oder das zugeordnete Bild wurde nicht gefunden.<br>Pfad:',
    'OXPROBS_VEND_ORPHPICS_INFO' => 'Nachfolgende Bild-Dateien sind keinem Hersteller zugeordnet.<br>Pfad:',
    'OXPROBS_NOPIC_DEF'          => 'Kein Bild festgelegt',
    'OXPROBS_NOPIC_FOUND'        => 'Bild-Datei nicht gefunden',
    'OXPROBS_ORPHPIC_FOUND'      => 'verwaiste Bild-Datei gefunden',

    'SHOP_MODULE_GROUP_OXPROBS_ARTICLESETTINGS'    => 'Artikel-Analyse',
    'SHOP_MODULE_sOxProbsEANField'           => 'Zu pr&uuml;fendes EAN-Feld',
    'SHOP_MODULE_sOxProbsEANField_oxean'     => 'EAN',
    'SHOP_MODULE_sOxProbsEANField_oxdistean' => 'Hersteller EAN',
    'SHOP_MODULE_sOxProbsMinDescLen'         => 'Mindest-Textl&auml;nge der Artikel-Beschreibung',
    'SHOP_MODULE_sOxProbsBPriceMin'          => 'Mindest-Faktor f&uuml;r EK-Preis (z.B. 0.5 = 50% des VK)',
    'SHOP_MODULE_sOxProbsMaxActionTime'      => 'Maximale Dauer einer Aktion (in Tagen)',
    'SHOP_MODULE_GROUP_OXPROBS_USERSETTINGS' => 'Benutzer-Analyse',
    'SHOP_MODULE_sOxProbsNameLength'         => 'Mindest-L&auml;nge von Vor- und Nachname',
    
    'charset'                    => 'ISO-8859-15',
);

?>