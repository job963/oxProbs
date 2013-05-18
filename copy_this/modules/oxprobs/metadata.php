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
                        'en'=>'Analysis module for finding problematical shp data.'
                        ),
    'thumbnail'    => 'oxprobs.png',
    'version'      => '0.4.1',
    'author'       => 'Joachim Barthel',
    'url'          => 'http://code.google.com/p/oxprobs/',
    'email'        => 'jbarthel@qualifire.de',
    'extend'       => array(
        'oxadmindetails' => array('oxprobs/application/controllers/admin/oxprobs_articles',
                                  'oxprobs/application/controllers/admin/oxprobs_delivery',
                                  'oxprobs/application/controllers/admin/oxprobs_groups'
                                  )
                        ),
    'templates' => array(
                        'oxprobs_articles.tpl' => 'oxprobs/views/admin/tpl/oxprobs_articles.tpl',
                        'oxprobs_delivery.tpl' => 'oxprobs/views/admin/tpl/oxprobs_delivery.tpl',
                        'oxprobs_groups.tpl' => 'oxprobs/views/admin/tpl/oxprobs_groups.tpl'
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
                        /*array(
                            'group' => 'OXPROBS_USERSETTINGS', 
                            'name'  => 'sOxProbsNameLength', 
                            'type'  => 'str', 
                            'value' => '4',
                            ),*/
                        )
    );

?>
