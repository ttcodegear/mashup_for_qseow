<?php
require_once './vendor/autoload.php';
//require_once 'C:\home\tts\php-7.4.30-x64\vendor\autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$privateKey = file_get_contents('c:\temp\privatekey.pem');

$iat = time();
$exp = $iat + 3600; //Expires 3600 seconds after the issue date/time.
$payload = [
  'jti' => uniqid(), // random string(Optional)
  'aud' => '112adams',
  'iat' => $iat,
  'exp' => $exp,
  'userid' => 'ttakahashi',
  'userdirectory' => 'JPTOK-TTS-R2VM',
  'name' => 'ttakahashi',
  'email' => 'test@test.com',
  'groups' => ['Administrators', 'Sales', 'Marketing']
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

<table border="1" width="100%" height="100%">
  <tbody>
    <tr>
      <td colspan="3" width="100%" height="10%"><div id="QV01" style="width:100%;height:100%;"></div></td>
    </tr>
    <tr>
      <td width="30%" height="45%"><div id="QV02" style="width:100%;height:100%;"></div></td>
      <td colspan="2" rowspan="2" width="70%" height="80%"><div id="QV04" style="width:100%;height:100%;"></div></td>
    </tr>
    <tr>
      <td width="30%" height="45%"><div id="QV03" style="width:100%;height:100%;"></div></td>
    </tr>
  </tbody>
</table>

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
  var tenant = "https://"+server.host+(server.port ? ":"+server.port : "");
  //ログイン状態チェック用URL: https://[tenant]/[prefix]/hub/
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
require.config({
  // https://jptok-tts-r2vm:443/jwt/resources
  baseUrl: (server.isSecure ? "https://" : "http://") + server.host + (server.port ? ":"+server.port : "") + server.prefix + "resources"
});
require(["js/qlik"], function(qlik) {
  qlik.setLanguage('ja-JP');                           // 表示言語
  qlik.theme.apply('breeze');                          // Theme ID
  var app_id = '9de2ab76-e358-4f0c-8a93-f32ea36cd3db'; // App ID
  var app = qlik.openApp(app_id, server);
  app.getObject('QV01', 'CurrentSelections'); // 選択バー
  app.getObject('QV02', 'BDQru');             // フィルターパネル
  app.getObject('QV03', 'CzcvAzC');           // フィルターパネル
  app.getObject('QV04', 'pmGDmg');            // 棒チャート

  window.onbeforeunload = function() {
    app.close();
  }
});
}
</script>

</body>
</html>
