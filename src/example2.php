<?php
namespace WioForms;

require_once 'vendor/autoload.php';

$GoogleReCaptchaSiteKey = 'ABC';
$GoogleReCaptchaSecretKey = 'CBA';

include '../googleApi.keys';


$localSettings = [];

$wioForms = new WioForms([]);
$wioFormHtml = $wioForms->showForm('Example2');
$wioHeaders = $wioForms->getHeaders();


?>
<!doctype HTML>
<html>
<head>
  <script src="../assets/js/jquery-1.12.3.min.js"></script>
  <meta charset="utf-8">
  <?= implode($wioHeaders, "\n"); ?>
</head>
<body>
  <?= $wioFormHtml; ?>
</body>
</html>
