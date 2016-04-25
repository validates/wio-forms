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
    ],
    "SrcDir" => ''
];

$wioForm = new WioForms($localSettings);
$wioFormHtml = $wioForm->showForm('Form1');
$wioHeaders = $wioForm->getHeaders();


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
