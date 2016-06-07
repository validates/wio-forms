<?php
namespace WioForms;

require_once('../../../autoload.php');

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
    ],
    "GoogleMapsApi" => [
        "Key" => $GoogleMapsApiKey
    ],
    "SrcDir" => ''
];

$wioForms = new WioForms($localSettings);
$wioFormHtml = $wioForms->showForm('Form1');
$wioHeaders = $wioForms->getHeaders();


?>
<!doctype HTML>
<html>
<head>
  <meta charset="utf-8">
  <?= implode($wioHeaders,"\n"); ?>
</head>
<body>
  <?= $wioFormHtml; ?>
</body>
</html>
