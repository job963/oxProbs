<?php

/**
 * Metadata version
 */
$sMetadataVersion = '1.1';
 
/**
 * Module information
 * 
 * @link      https://github.com/job963/oxProbs
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @copyright (C) Joachim Barthel 2012-2016
 * 
*/

$aModule = array(
    'id'           => 'oxprobs',
    'title'        => 'OxProbs - Data Problem Analysis',
    'description'  => array(
                        'de' => 'Analyse-Modul zum Auffinden problematischer Shop Daten.',
                        'en' => 'Analysis module for finding problematical shop data.'
                        ),
    'thumbnail'    => 'oxprobs.png',
    'version'      => '0.8.8',
    'author'       => 'Joachim Barthel',
    'url'          => 'https://github.com/job963/oxProbs',
    'email'        => 'jobarthel@gmail.com',
    'extend'       => array(
                        ),
    'files'        => array(
        'oxprobs_articles' => 'jxmods/oxprobs/application/controllers/admin/oxprobs_articles.php',
        'oxprobs_delivery' => 'jxmods/oxprobs/application/controllers/admin/oxprobs_delivery.php',
        'oxprobs_groups'   => 'jxmods/oxprobs/application/controllers/admin/oxprobs_groups.php',
        'oxprobs_users'    => 'jxmods/oxprobs/application/controllers/admin/oxprobs_users.php',
        'oxprobs_orders'   => 'jxmods/oxprobs/application/controllers/admin/oxprobs_orders.php',
        'oxprobs_pictures' => 'jxmods/oxprobs/application/controllers/admin/oxprobs_pictures.php'
                        ),
    'templates'    => array(
        'oxprobs_articles.tpl' => 'jxmods/oxprobs/application/views/admin/tpl/oxprobs_articles.tpl',
        'oxprobs_delivery.tpl' => 'jxmods/oxprobs/application/views/admin/tpl/oxprobs_delivery.tpl',
        'oxprobs_groups.tpl'   => 'jxmods/oxprobs/application/views/admin/tpl/oxprobs_groups.tpl',
        'oxprobs_users.tpl'    => 'jxmods/oxprobs/application/views/admin/tpl/oxprobs_users.tpl',
        'oxprobs_orders.tpl'   => 'jxmods/oxprobs/application/views/admin/tpl/oxprobs_orders.tpl',
        'oxprobs_pictures.tpl' => 'jxmods/oxprobs/application/views/admin/tpl/oxprobs_pictures.tpl'
                        ),
    'settings' => array(
                        array(
                                'group' => 'OXPROBS_ARTICLESETTINGS', 
                                'name'  => 'sOxProbsEANField', 
                                'type'  => 'select', 
                                'value' => 'oxean',
                                'constrains' => 'oxean|oxdistean', 
                                'position' => 0 
                                ),
                        array(
                                'group' => 'OXPROBS_ARTICLESETTINGS', 
                                'name'  => 'sOxProbsMinDescLen', 
                                'type'  => 'str', 
                                'value' => '15'
                                ),
                        array(
                                'group' => 'OXPROBS_ARTICLESETTINGS', 
                                'name'  => 'sOxProbsBPriceMin',  
                                'type'  => 'str', 
                                'value' => '0.5'
                                ),
                        array(
                                'group' => 'OXPROBS_ARTICLESETTINGS', 
                                'name'  => 'sOxProbsMaxActionTime',  
                                'type'  => 'str', 
                                'value' => '14'
                                ),
                        array(
                                'group' => 'OXPROBS_ARTICLESETTINGS', 
                                'name'  => 'bOxProbsProductPreview',  
                                'type'  => 'bool', 
                                'value' => true
                                ),
                        array(
                                'group' => 'OXPROBS_ARTICLESETTINGS', 
                                'name'  => 'bOxProbsProductActiveOnly',  
                                'type'  => 'bool', 
                                'value' => true
                                ),
                        array(
                                'group' => 'OXPROBS_ARTICLESETTINGS', 
                                'name'  => 'bOxProbsProductTimeActive',  
                                'type'  => 'bool', 
                                'value' => true
                                ),
                        array(
                                'group' => 'OXPROBS_ORDERSETTINGS', 
                                'name'  => 'sOxProbsOrderPaidLater',  
                                'type'  => 'str', 
                                'value' => 'oxidinvoice,oxidcashondel'
                                ),
                        array(
                                'group' => 'OXPROBS_ORDERSETTINGS', 
                                'name'  => 'sOxProbsOrderPaidbyCIA',  
                                'type'  => 'str', 
                                'value' => 'oxidpayadvance'
                                ),
                        array(
                                'group' => 'OXPROBS_ORDERSETTINGS', 
                                'name'  => 'sOxProbsOrderPaidbyInvoice',  
                                'type'  => 'str', 
                                'value' => 'oxidinvoice'
                                ),
                        array(
                                'group' => 'OXPROBS_ORDERSETTINGS', 
                                'name'  => 'sOxProbsOrderIgnoredRemark',  
                                'type'  => 'str', 
                                'value' => 'Hier k%nnen Sie uns noch etwas mitteilen.'
                                ),
                        array(
                                'group' => 'OXPROBS_PICTURESETTINGS', 
                                'name'  => 'sOxProbsPictureDirs',  
                                'type'  => 'select', 
                                'value' => 'master',
                                'constrains' => 'master|generated', 
                                'position' => 0 
                                ),
                        array(
                                'group' => 'OXPROBS_INCLUDESETTINGS', 
                                'name'  => 'aOxProbsArticleIncludeFiles', 
                                'type'  => 'arr', 
                                'value' => array(), 
                                'position' => 1
                                ),
                        array(
                                'group' => 'OXPROBS_INCLUDESETTINGS', 
                                'name'  => 'aOxProbsGroupIncludeFiles', 
                                'type'  => 'arr', 
                                'value' => array(), 
                                'position' => 1
                                ),
                        array(
                                'group' => 'OXPROBS_INCLUDESETTINGS', 
                                'name'  => 'aOxProbsDeliveryIncludeFiles', 
                                'type'  => 'arr', 
                                'value' => array(), 
                                'position' => 1
                                ),
                        array(
                                'group' => 'OXPROBS_INCLUDESETTINGS', 
                                'name'  => 'aOxProbsUsersIncludeFiles', 
                                'type'  => 'arr', 
                                'value' => array(), 
                                'position' => 1
                                ),
                        array(
                                'group' => 'OXPROBS_INCLUDESETTINGS', 
                                'name'  => 'aOxProbsOrdersIncludeFiles', 
                                'type'  => 'arr', 
                                'value' => array(), 
                                'position' => 1
                                ),
                        array(
                                'group' => 'OXPROBS_INCLUDESETTINGS', 
                                'name'  => 'aOxProbsPicturesIncludeFiles', 
                                'type'  => 'arr', 
                                'value' => array(), 
                                'position' => 1
                                ),
                        array(
                                'group' => 'OXPROBS_DOWNLOAD', 
                                'name'  => 'bOxProbsHeader', 
                                'type'  => 'bool', 
                                'value' => 'true'
                                ),
                        array(
                                'group' => 'OXPROBS_DOWNLOAD', 
                                'name'  => 'sOxProbsSeparator', 
                                'type'  => 'select', 
                                'value' => 'comma',
                                'constrains' => 'comma|semicolon|tab|pipe|tilde', 
                                'position' => 0 
                                ),
                        array(
                                'group' => 'OXPROBS_DOWNLOAD', 
                                'name'  => 'bOxProbsQuote', 
                                'type'  => 'bool', 
                                'value' => 'true'
                                ),
                        array(
                                'group' => 'OXPROBS_DOWNLOAD', 
                                'name'  => 'bOxProbsStripTags', 
                                'type'  => 'bool', 
                                'value' => 'true'
                                )
                        )
    );

?>
