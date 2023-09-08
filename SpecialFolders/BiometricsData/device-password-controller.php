<?php
    namespace BiometricsData;
    require 'company-info.php';

if(isset($_POST['old-password']) && $_POST['new-password']){
    $url = '192.168.0.143:8090';
    $pass = $_POST['old-password'];
    $device = new CompanyInfo($url, $pass);

    $newPass = $_POST['new-password'];
    $res = $device->changeDevicePassword($pass, $newPass);
    Results::setResult($res);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Change</title>
    <style>
        .result{
            font-size: large;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 600;
        }
        .back-btn{
            padding: 10px;
            background-color: purple;
            border: none;
            border-radius: 50%;
            font-size: larger;
        }
    </style>
</head>
<body>
    <h1 class="result">
        <?php
           echo Results::getResult()
        ?>
    </h1>
    <button onclick="goBack()" class="back-btn">
        Back
    </button>
</body>
<script>
    goBack = () => {
        window.history.back();
    }
</script>
</html>
