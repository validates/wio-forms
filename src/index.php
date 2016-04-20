<?php
namespace WioForms;

require_once('vendor/autoload.php');

$localSettings = [
    'DatabaseConnections'=> [
        'OtherConnection' => [
            'dbName' => "System2"
        ]
    ]
];

$wioForm = new WioForms( $localSettings );
?>
<!doctype HTML>
<html>
<head>
  <meta charset="utf-8">
</head>
<body>
  <?= $wioForm->showForm( 'Form1' ); ?>
</body>
</html>
