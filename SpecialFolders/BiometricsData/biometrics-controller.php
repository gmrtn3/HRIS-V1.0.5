
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success!</title>
</head>
<body>
    <h1>
    <?php
        //use BiometricsData\Employee;
        require 'Employee.php';

        $employee = new Employee('12345', '192.168.0.143:8090');
        $userName = isset($_POST['username']) ? $_POST['username']: '';
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $contact = isset($_POST['contact']) ? $_POST['contact'] : '';

        $msg = $employee->Create($userName, $password, $id, $contact);

        echo "<script>alert('$msg');
            window.history.back();</script>";
    ?>
    </h1>
    <!-- <button onclick="back()">
        Back
    </button>
    <new-button></new-button> -->
</body>
<script>
    back = () => {
        window.history.back();
    }
    // var sample = document.getElement('button')
    // class NewButton extends HTMLElement{
    //     constructor(){
    //         super();
    //     }

    //     onclick = () => {
    //         console.log
    //     }

    // }
    // window.customElements.define('new-button', NewButton);
</script>
</html>
