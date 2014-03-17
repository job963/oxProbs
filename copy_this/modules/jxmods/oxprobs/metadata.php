<?php

/**
 * Metadata version
 */
$sMetadataVersion = '1.1';
 
/**
 * Module information
 */
$aModule = array(
    'id'           => 'oxprobs',
    'title'        => 'OxProbs - Data Problem Analysis',
    'description'  => array(
                        'de'=>'Analyse-Modul zum Auffinden problematischer Shop-Daten.',
                        'en'=>'Analysis module for finding problematical shop data.'
                        ),
    'thumbnail'    => 'oxprobs.png',
    'version'      => '0.7',
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
        'oxprobs_pictures' => 'jxmods/oxprobs/application/controllers/admin/oxprobs_pictures.php'
                        ),
    'templates'    => array(
        'oxprobs_articles.tpl' => 'jxmods/oxprobs/views/admin/tpl/oxprobs_articles.tpl',
        'oxprobs_delivery.tpl' => 'jxmods/oxprobs/views/admin/tpl/oxprobs_delivery.tpl',
        'oxprobs_groups.tpl'   => 'jxmods/oxprobs/views/admin/tpl/oxprobs_groups.tpl',
        'oxprobs_users.tpl'    => 'jxmods/oxprobs/views/admin/tpl/oxprobs_users.tpl',
        'oxprobs_pictures.tpl' => 'jxmods/oxprobs/views/admin/tpl/oxprobs_pictures.tpl'
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
                                'group' => 'OXPROBS_PICTURESETTINGS', 
                                'name'  => 'sOxProbsPictureDirs',  
                                'type'  => 'select', 
                                'value' => 'master',
                                'constrains' => 'master|generated', 
                                'position' => 0 
                                ),
                        array(
                                'group' => 'OXPROBS_INCLUDESETTINGS', 
                                'name'  => 'sOxProbsArticleIncludeFiles',  
                                'type'  => 'str', 
                                'value' => ''
                                ),
                        array(
                                'group' => 'OXPROBS_INCLUDESETTINGS', 
                                'name'  => 'sOxProbsGroupIncludeFiles',  
                                'type'  => 'str', 
                                'value' => ''
                                ),
                        array(
                                'group' => 'OXPROBS_INCLUDESETTINGS', 
                                'name'  => 'sOxProbsDeliveryIncludeFiles',  
                                'type'  => 'str', 
                                'value' => ''
                                ),
                        array(
                                'group' => 'OXPROBS_INCLUDESETTINGS', 
                                'name'  => 'sOxProbsUsersIncludeFiles',  
                                'type'  => 'str', 
                                'value' => ''
                            ),
                        array(
                                'group' => 'OXPROBS_INCLUDESETTINGS', 
                                'name'  => 'sOxProbsPicturesIncludeFiles',  
                                'type'  => 'str', 
                                'value' => ''
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
                        )
    );

?>
