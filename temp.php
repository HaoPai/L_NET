<?php
    require_once __DIR__ . "/qcloudsms_php-master/src/index.php";

    use Qcloud\Sms\SmsSingleSender;

    // 短信应用SDK AppID
    $appid = 1400200250; // 1400开头
    // 短信应用SDK AppKey
    $appkey = "70be3f9092c3f02112afccff5b502312";
    // 需要发送短信的手机号码
    $phoneNumbers = ["15975062318"];
    // 签名
    $smsSign = "光辉岁月"; // NOTE: 这里的签名只是示例，请使用真实的已申请的签名，签名参数使用的是`签名内容`，而不是`签名ID`
    // 单发短信
    try {
        $ssender = new SmsSingleSender($appid, $appkey);
        $result = $ssender->send(0, "86", $phoneNumbers[0],
            "【L_NET.CN】您的验证码是: 888888888888", "", "");
        $rsp = json_decode($result);
        echo $result;
    } catch(\Exception $e) {
        echo var_dump($e);
    }
    echo "\n";

      //定义数据库连接信息
	// $dbc=mysql_connect("localhost:3310","root","hunan2010");
 //    mysql_select_db("haobai_web",$dbc);
	// mysql_close($dbc);



?>