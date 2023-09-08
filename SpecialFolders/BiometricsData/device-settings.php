<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.7);
    }
    .modal-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 400px;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
.openModal-btn{
    padding: 10px;
    margin: 10px;
    border-radius: 20px;
}
.openModal-btn:hover{
    background-color: #888;
    color: #fff;
}
.submit-btn{
    padding: 10px;
    margin: 10px;
    border-radius: 20px;
    background-color: blue;
    color:#fff;
    border: none;
}
.submit-btn:hover{
    background-color: white;
    color:black;
    border: none;
}
</style>


<button id="openModal" class="openModal-btn">Device Settings</button>
<div id="device-settings-modal" class="modal">
    <div class="modal-content">
        <!-- Settings Window -->
        <span class="close">&times;</span>

        <h3>Device Settings</h3>

        <!-- Change Password -->
        <form action="biometrics-data/device-password-controller.php" method="POST">
            <label for="old-password">Old Password: </label>
            <input type="password" name="old-password" id="">
            <label for="new-password">New Password: </label>
            <input type="password" name="new-password" id="">
            <input type="submit" value="Update Password" class="submit-btn">
        </form>

        <form action="">
            <label for=""></label>
        </form>

        <form action="">
            <label for=""></label>
        </form>
    </div>
</div>

<script type="text/javascript">
   var modalBtn = document.getElementById('openModal')

    modalBtn.addEventListener('click', () => {
        var modal = document.getElementById('device-settings-modal')
        modal.style.display = 'block';
    })

    var closeBtn = document.getElementsByClassName('close')[0]

    closeBtn.addEventListener('click', () => {
        var modal = document.getElementById('device-settings-modal')
        modal.style.display = 'none';
    });

    // window.addEventListener('click', (e) => {
    //     var modal = document.getElementById('device-settings-modal')
    //     if(e.target == modal){
    //         modal.style.display ='none';
    //     }
    // })
</script>
