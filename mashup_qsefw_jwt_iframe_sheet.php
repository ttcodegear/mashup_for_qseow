<?php
//require_once './vendor/autoload.php';
require_once 'C:\home\tts\php-7.4.30-x64\vendor\autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$privateKey = file_get_contents('c:\temp\MashupSample\jwt\privatekey.pem');

$iat = time();
$exp = $iat + 3600; //Expires 3600 seconds after the issue date/time.
$payload = [
  'jti' => uniqid(), // random string(Optional)
  'aud' => '112adams',
  'iat' => $iat,
  'exp' => $exp,
  'userid' => 'ttakahashi',
  'userdirectory' => 'JPTOK-TTS-R2VM',
  //'name' => 'ttakahashi',
  //'email' => 'test@test.com',
  //'groups' => ['Administrators', 'Sales', 'Marketing']
];

$jwt = JWT::encode($payload, $privateKey, 'RS256');
?>

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <!--<script src="https://jptok-tts-r2vm/jwt/resources/assets/external/requirejs/require.js"></script>-->
  <!--<link rel="stylesheet" href="https://jptok-tts-r2vm/jwt/resources/autogenerated/qlik-styles.css">-->
</head>

<body>

<iframe id="QV01" src="" style="width:75%;height:75%;"></iframe>

<script>
var server = {
  host: 'jptok-tts-r2vm',    // Qlik Sense sever name
  port: 443,                 // Qlik Sense port
  prefix: '/jwt/',           // Virtual Proxy prefix(set SameSite=None in QMC)
  isSecure: true,            // true=https, false=http
  jwt: '<?php echo($jwt) ?>' // JWT
};

//認証済みかのチェック
function connect() {
  var tenant = (server.isSecure ? "https://" : "http://")+server.host+(server.port ? ":"+server.port : "");
  //ログイン状態チェック用URL: http(s)://[tenant]/[prefix]/hub/
  fetch(tenant + server.prefix + "hub/", {
    method: "GET",
    mode: "cors",           // no-cors, cors, same-origin
    credentials: "include", // include, same-origin, omit
    headers: {
      'Authorization':'Bearer '+server.jwt
    }
  }).then(response => {
    if(response.status===401) {
      alert('Failed to login via JWT');
    }
    else {
      var script = document.createElement('script');
      script.onload = function () {
        script.onload = null;
        var style = document.createElement('link');
        style.href = tenant + server.prefix + 'resources/autogenerated/qlik-styles.css';
        style.type = 'text/css';
        style.rel = 'stylesheet';
        document.getElementsByTagName('head')[0].append(style);
        start();
      };
      script.src = tenant + server.prefix + 'resources/assets/external/requirejs/require.js';
      document.head.appendChild(script);
    }
  }).catch(error => {
    alert(error);
  });
}
connect(); //認証済みかのチェック

function start() {
  var sheet = "https://jptok-tts-r2vm/jwt/single/?appid=9de2ab76-e358-4f0c-8a93-f32ea36cd3db&sheet=GjqPnx&lang=ja-JP&opt=currsel%2Cctxmenu&theme=breeze";
  document.getElementById("QV01").src = sheet;
}
</script>

</body>
</html>
