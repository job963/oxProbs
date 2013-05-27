<?php

/**
 * Metadata version
 */
$sMetadataVersion = '1.0';
 
/**
 * Module information
 */
$aModule = array(
    'id'           => 'oxprobs',
    'title'        => 'OxProbs - OXID Data Problem Analysis',
    'description'  => array(
                        'de'=>'Analyse-Modul zum Auffinden problematischer Shop-Daten.',
                        'en'=>'Analysis module for finding problematical shop data.'
                        ),
    'thumbnail'    => 'oxprobs.png',
    'version'      => '0.5',
    'author'       => 'Joachim Barthel',
    'url'          => 'https://github.com/job963/oxProbs',
    'email'        => 'jbarthel@qualifire.de',
    'extend'       => array(
        'oxadmindetails' => array('oxprobs/application/controllers/admin/oxprobs_articles',
                                  'oxprobs/application/controllers/admin/oxprobs_delivery',
                                  'oxprobs/application/controllers/admin/oxprobs_groups',
                                  'oxprobs/application/controllers/admin/oxprobs_users',
                                  'oxprobs/application/controllers/admin/oxprobs_pictures'
                                  )
                        ),
    'templates' => array(
                        'oxprobs_articles.tpl' => 'oxprobs/views/admin/tpl/oxprobs_articles.tpl',
                        'oxprobs_delivery.tpl' => 'oxprobs/views/admin/tpl/oxprobs_delivery.tpl',
                        'oxprobs_groups.tpl' => 'oxprobs/views/admin/tpl/oxprobs_groups.tpl',
                        'oxprobs_users.tpl' => 'oxprobs/views/admin/tpl/oxprobs_users.tpl',
                        'oxprobs_pictures.tpl' => 'oxprobs/views/admin/tpl/oxprobs_pictures.tpl'
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
                            'group' => 'OXPROBS_PICTURESETTINGS', 
                            'name'  => 'sOxProbsPictureDirs',  
                            'type'  => 'select', 
                            'value' => 'master',
                            'constrains' => 'master|generated', 
                            'position' => 0 
                            ),
                        /*array(
                            'group' => 'OXPROBS_USERSETTINGS', 
                            'name'  => 'sOxProbsNameLength', 
                            'type'  => 'str', 
                            'value' => '4',
                            ),*/
                        )
    );

?>
