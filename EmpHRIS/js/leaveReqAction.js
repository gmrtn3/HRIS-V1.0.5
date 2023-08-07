function click_btnReject(){

    let leave_stats = document.getElementById('id_leaveStats').value;


    if(leave_stats === 'Approved'){
        alert('You cannot reject a request that is already rejected');
    }
    else if(leave_stats === 'Rejected'){
        alert('You cannot reject a request that is already approved.');
    }

}


function click_btnApproved(){
    let leave_stats = document.getElementById('id_leaveStats').value;
    if(leave_stats === 'Approved'){
        alert('You cannot approved a request that is already rejected');
    }
    else if(leave_stats === 'Rejected'){
        alert('You cannot approved a request that is already approved.');
    }

}