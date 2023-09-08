<?php
//namespace BiometricsData;
class Employee{

    private $url;
    private $password;

    public function __construct($password, $url)
    {
        $this->password = $password;
        $this->url = $url;
    }

    public function Create($name, $userPass, $id, $contactNum = null){
        // echo $this->password;
        $api = $this->url.'/person/create';
        //echo $api;
        $personData = array(
            'id' => $id,
            'name' => $name,
            'facePermission' => '2',
            'fingerPermission' => '2',
            'passwordPermission' => '2',
            'phone' => $contactNum != null ? $contactNum : '',
            'password' => $userPass,
        );

        $jsonEncoded_personData = json_encode($personData);
        //$urlEncoded_personData = urlencode($jsonEncoded_personData);

        //echo $urlEncoded_personData;

        $params = array(
            'pass' => $this->password,
            'person' => $jsonEncoded_personData
        );

        $endpoint = $api.'?'.http_build_query($params);
        //echo $endpoint;
        $curl = curl_init($endpoint);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($curl);

        $res = json_decode($data);
        return isset($res->msg) ? $res->msg : 'Something went wrong, please try again';
    }

    public function GetAllRecords(){

    }

    public function IsEmployeeExist($empID){
        //echo $this->password;
        $api = $this->url."/person/find?pass=$this->password&id=$empID";

        try{
            $curl = curl_init($api);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($curl);

            $res = json_decode($data);
        }
        catch (Exception $e){
            return null;
        }
        // if($res == null) return null;
        return $res->success ? true : false;
    }
}
