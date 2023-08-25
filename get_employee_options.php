<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=1">
<title>Home</title>
<link rel="stylesheet" type="text/css" href="css/virtual-select.min.css">
<!-- <link rel="stylesheet" type="text/css" href="sample.css"> -->
</head>
<body>
    <style>
        #multi_options{
	max-width: 100%;
	width: 100%;
}
    </style>

<?php
if (isset($_POST['department'])) {
    include 'config.php';
    
    $selectedDepartment = mysqli_real_escape_string($conn, $_POST['department']);

    if ($selectedDepartment == 'All') {
        $sqla = "SELECT * FROM employee_tb WHERE `classification` != 3";
        $resulta = mysqli_query($conn, $sqla);
        
        $optionsa = "";
        while ($rowa = mysqli_fetch_assoc($resulta)) {
            $optionsa .= '<option value="' . $rowa['empid'] . '">' . $rowa['empid'] . " - " . $rowa['fname'] . " " . $rowa['lname'] . '</option>';
        }
        
        echo '<select class="approver-dd" name="cuttOff_emp[]" id="multi_options" multiple placeholder="Select Employee" data-silent-initial-value-set="false" style="display:flex; width: 380px;"> ' . $optionsa . ' </select>';
    } else {
        $sql = "SELECT * FROM employee_tb WHERE department_name = '$selectedDepartment' AND classification != 3";
        $result = mysqli_query($conn, $sql);
        
        $options = "";
        while ($row = mysqli_fetch_assoc($result)) {
            $options .= '<option value="' . $row['empid'] . '">' . $row['empid'] . " - " . $row['fname'] . " " . $row['lname'] . '</option>';
        }
        
        echo '<select class="approver-dd" name="cuttOff_emp[]" id="multi_options" multiple  placeholder="Select Employee" data-silent-initial-value-set="false" style="display:flex; width: 380px;"> ' . $options . ' </select>';
    }
}
?>

<script type="text/javascript" src="js/virtual-select.min.js"></script>
<script type="text/javascript">
	VirtualSelect.init({ 
	  ele: '#multi_options' 
	});
</script>
</body>
</html>
