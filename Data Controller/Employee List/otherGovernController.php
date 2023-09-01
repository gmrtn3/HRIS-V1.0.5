<?php
$conn = mysqli_connect("localhost", "root", "", "hris_db");

$result = mysqli_query($conn, "SELECT * FROM employee_tb WHERE empid = '".$_POST['empid']."'");
$row = mysqli_fetch_assoc($result);

if (count($_POST) > 0) {
    $id_emp = $_POST['id_emp'];
    $other_govern = $_POST['other_govern'];
    $govern_amount = $_POST['govern_amount'];

    foreach ($id_emp as $key => $value) {
        $description = $other_govern[$key];
        $amount = $govern_amount[$key];

        // Check if description and amount are empty
        if (!empty($description) && !empty($amount)) {
            $submit = "INSERT INTO governdeduct_tb (id_emp, other_govern, govern_amount)
                       VALUES ('".$value."', '".$description."', '".$amount."')";

            $query = mysqli_query($conn, $submit);
        }
    }

    header("Location: ../../editempListForm.php?empid=".$row['empid']);
}

mysqli_close($conn);
?>
