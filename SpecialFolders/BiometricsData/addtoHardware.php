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
.form-flex-box{
        display: flex;
        flex-direction: row;
        align-content: space-between;
        justify-content: center;
    }
</style>


<script type="text/javascript">

            addBiometrics = (empid) => {
                var modal = document.getElementById('device-settings-modal');
                modal.style.display = 'block';

                // fetch(`biometrics-data/personDetails.php?empid=${empid}`)
                //     .then(res => res.text())
                //     .then(resData => {
                //         console.log(resData);
                //         document.getElementById('form-details').innerHTML = resData;
                //     })
                //     .catch(err => {
                //         console.error(err);
                //     })
            }

            var closeBtn = document.getElementsByClassName('close')[0]

            closeBtn.addEventListener('click', () => {
                var modal = document.getElementById('device-settings-modal')
                modal.style.display = 'none';
                console.log('closed');
            });

        window.addEventListener('click', (e) => {
            var modal = document.getElementById('device-settings-modal')
            if(e.target == modal){
                modal.style.display ='none';
            }
        })

        closeModal = () => {
            var modal = document.getElementById('device-settings-modal')
            modal.style.display = 'none';
            console.log('closed');
        }
</script>

<!-- <button id="openModal" class="openModal-btn">Device Settings</button> -->
<div id="device-settings-modal" class="modal">
    <div class="modal-content">
        <!-- Settings Window -->
        <span class="close">&times;</span>

        <h3>Add to Biometrics</h3>

        <!-- Change Password -->
        <form action="biometrics-data/biometrics-controller.php" id="form-details" class="form-flex-box" method="POST">


        </form>
    </div>
</div>


