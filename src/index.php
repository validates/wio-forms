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
?>
<!doctype HTML>
<html>
<head>
  <meta charset="utf-8">
</head>
<body>
  <?php $WioForm->showForm( 'Form1' ); ?>
</body>
</html>
