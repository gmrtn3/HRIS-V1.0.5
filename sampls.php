<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css">

        <!-- skydash -->

    <link rel="stylesheet" href="skydash/feather.css">
    <link rel="stylesheet" href="skydash/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="skydash/vendor.bundle.base.css">

    <link rel="stylesheet" href="skydash/style.css">

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
   
    <title>Document</title>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center mt-5">
        <div class="form-group w-50">
            <label for="">Basic Salary</label><br>
            <input type="text" id="basic_salary" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);">
            <div style="color: red; display:none">Theres an error in your input</div>
            <br>

            <label for="">Working Days</label><br>
            <input type="text" id="working_days" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);">
            <div style="color: red; display:none">Theres an error in your input</div>
            <br>

            <label for="">Daily Rate</label><br>
            <input type="text" id="daily_rate" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);">
            <div style="color: red; display:none">Theres an error in your input</div>
        </div>
    </div>

    <script>
        // Function to calculate daily rate when basic salary and working days are entered
        function calculateDailyRate() {
            const basicSalary = parseFloat(document.getElementById('basic_salary').value);
            const workingDays = parseInt(document.getElementById('working_days').value);

            if (!isNaN(basicSalary) && !isNaN(workingDays) && workingDays !== 0) {
                const dailyRate = basicSalary / workingDays;
                document.getElementById('daily_rate').value = dailyRate.toFixed(2);
            } else {
                document.getElementById('daily_rate').value = '';
            }
        }

        // Function to calculate basic salary when working days and daily rate are entered
        function calculateBasicSalary() {
            const workingDays = parseInt(document.getElementById('working_days').value);
            const dailyRate = parseFloat(document.getElementById('daily_rate').value);

            if (!isNaN(workingDays) && !isNaN(dailyRate) && workingDays !== 0) {
                const basicSalary = workingDays * dailyRate;
                document.getElementById('basic_salary').value = basicSalary.toFixed(2);
            } else {
                document.getElementById('basic_salary').value = '';
            }
        }

        // Attach the calculate functions to the input fields
        document.getElementById('basic_salary').addEventListener('input', calculateDailyRate);
        document.getElementById('working_days').addEventListener('input', calculateDailyRate);
        document.getElementById('working_days').addEventListener('input', calculateBasicSalary);
        document.getElementById('daily_rate').addEventListener('input', calculateBasicSalary);
    </script>
</body>
</html>