<?php
namespace WioForms;

require_once('vendor/autoload.php');

$localSettings = [
    'DatabaseConnections'=> [
        'Main' => [
            'dbName' => "System2"
        ]
    ]
];

$WioForm = new WioForms( $localSettings );
$WioForm->showForm( 'Form1' );

?>
