<?php
namespace WioForms;

require_once('vendor/autoload.php');

$GoogleReCaptchaSiteKey = 'ABC';
$GoogleReCaptchaSecretKey = 'CBA';

include('../googleApi.keys');


$localSettings = [
    'DatabaseConnections'=> [
        'OtherConnection' => [
            'dbName' => "System2"
        ]
    ],
    "ReCaptcha" => [
        "SiteKey" => $GoogleReCaptchaSiteKey,
        "SecretKey" => $GoogleReCaptchaSecretKey
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
