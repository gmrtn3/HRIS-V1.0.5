<?php
    $empid = isset($_GET['empid']) ? $_GET['empid'] : '';
?>

<div>
    <?php
        echo
        "<button onclick='addFace($empid)'}'>Add Face</button>
        <button onclick='addFingerprint($empid)'>Add Fingerprint</button>";
    ?>
</div>

<!-- <script type="text/javascript">
    function addFace(empid){
        console.log(`face for: ${empid}`);
    }
    function addFingerprint(empid){
        console.log(`fingerprint for: ${empid}`);
    }
</script> -->
