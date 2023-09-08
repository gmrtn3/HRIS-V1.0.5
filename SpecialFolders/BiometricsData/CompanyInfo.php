<?php
namespace BiometricsData;
class CompanyInfo{

    private $password;
    private $url;
    function __construct($url, $password){
        $this->password = $password;
        $this->url = $url;
    }

    public function changeLogo($b64Image){
        $api = $this->url.'/changeLogo';
        $params = array(
            'pass' => $this->password,
            'imgBase64' => $b64Image
        );
        $endpoint = $api.'?'.http_build_query($params);
        $curl = curl_init($endpoint);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($curl);
        $res = json_decode($data, true);

        // var_dump($res);
        return $res['msg'];
    }

    public function changeDevicePassword($oldPass, $newPass){
        $api = $this->url.'/setPassWord';
        $params = array(
            'oldPass' => $oldPass,
            'newPass' => $newPass
        );

        $endpoint = $api.'?'.http_build_query($params);
        // echo $endpoint;
        $curl = curl_init($endpoint);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($curl);
        $res = json_decode($data, true);

        // var_dump($res['msg']);
        // echo $res['msg'];
        return $res['msg'] ? $res['msg'] : '';
    }
}


class Results{
    private static $result;
    public static function setResult($res){
        self::$result = $res;
    }
    public static function getResult(){
        return self::$result;
    }
}
