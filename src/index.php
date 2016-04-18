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

$wioForm = new WioForms( $localSettings );
?>
<!doctype HTML>
<html>
<head>
  <meta charset="utf-8">
</head>
<body>
  <?php $wioForm->showForm( 'Form1' ); ?>
</body>
</html>
