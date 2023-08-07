// // search bar 


// function leavetype() {
//   let leavetype_id = document.getElementById("leavetype_id").value;

//   if (leavetype_id === 'Vacation Leave') {
//     document.getElementById("id_leavePeriod").disabled = false;
//   }
//   else if (leavetype_id === 'Sick Leave') {
//     document.getElementById("id_leavePeriod").disabled = false;
//   }
//   else if (leavetype_id === 'Bereavement Leave') {
//     document.getElementById("id_leavePeriod").disabled = false;
//   }
//    else {
//     document.getElementById("id_leavePeriod").disabled = true;
//   }
// }

//   function endvalidate() {
//     let id_inpt_strTime1 = new Date(document.getElementById("id_inpt_strdate").value);
//     let id_inpt_endTime1 = new Date(document.getElementById("id_inpt_enddate").value);
//     let id_leavePeriod = document.getElementById("id_leavePeriod");
//     let leavePeriodText = id_leavePeriod.options[id_leavePeriod.selectedIndex].text;
  
//     if (leavePeriodText === 'Half Day') {
//             if (id_inpt_strTime1.getTime() !== id_inpt_endTime1.getTime()) {
//               alert("For half-day leaves, the start and end dates must be the same.");
//               document.getElementById("id_btnsubmit").style.cursor = "no-drop";
//               document.getElementById("id_btnsubmit").disabled = true;
//             } else {
//               if (id_inpt_strTime1.getTime() > id_inpt_endTime1.getTime()) {
//                 alert("Please set the End Date not before the Start Date");
//                 document.getElementById("id_btnsubmit").style.cursor = "no-drop";
//                 document.getElementById("id_btnsubmit").disabled = true;
//               } else {
//                 document.getElementById("id_btnsubmit").style.cursor = "pointer";
//                 document.getElementById("id_btnsubmit").disabled = false;
//               }
//             }
//     } else { //if fullday
//               if (id_inpt_strTime1.getTime() === id_inpt_endTime1.getTime()) {
//                 alert("For Full-day leaves, the start and end dates must NOT be the same.");
//                 document.getElementById("id_btnsubmit").style.cursor = "no-drop";
//                 document.getElementById("id_btnsubmit").disabled = true;
//               }else{
//             //else
//             if (id_inpt_strTime1.getTime() > id_inpt_endTime1.getTime()) {
//               alert("Please set the End Date not before the Start Date");
//               document.getElementById("id_btnsubmit").style.cursor = "no-drop";
//               document.getElementById("id_btnsubmit").disabled = true;
//             } else {
//               document.getElementById("id_btnsubmit").style.cursor = "pointer";
//               document.getElementById("id_btnsubmit").disabled = false;
//             }
//               }
      
//     }
//   }



//     //PARA ISA LANG MA CHECK SA FIRST AND SECOND HALF AND UNLOCK THE STARTDATE
//         function halfdaysides(){

//           const firstHalfCheckbox = document.querySelector('input[name="firstHalf"]');
//           const secondHalfCheckbox = document.querySelector('input[name="secondHalf"]');

//           let id_leavePeriod = document.getElementById('id_leavePeriod').value;

//           if (id_leavePeriod === 'Full Day') {
//             document.getElementById("id_inpt_strdate").disabled = false;
//             document.getElementById('id_chckfirsthalf').style.display = "none";
//             document.getElementById('id_chckSecondhalf').style.display = "none";
//             document.getElementById('id_inpt_strdate').value= "";
//             document.getElementById('id_inpt_enddate').value= "";

//             firstHalfCheckbox.checked = this.checked;
//             secondHalfCheckbox.checked = this.checked;
            
//           }
//           else if(id_leavePeriod === 'Half Day'){
//             document.getElementById("id_inpt_strdate").disabled = true;
//             document.getElementById('id_chckfirsthalf').style.display = "flex";
//             document.getElementById('id_chckSecondhalf').style.display = "flex";
//             document.getElementById('id_inpt_strdate').value= "";
//             document.getElementById('id_inpt_enddate').value= "";
//           }
//         }
        


//         const firstHalfCheckbox = document.querySelector('input[name="firstHalf"]');
//         const secondHalfCheckbox = document.querySelector('input[name="secondHalf"]');
//         firstHalfCheckbox.addEventListener('click', function() {
//             secondHalfCheckbox.checked = !this.checked;
//             document.getElementById("id_inpt_strdate").disabled = false;
//         });
//         secondHalfCheckbox.addEventListener('click', function() {
//             firstHalfCheckbox.checked = !this.checked;
//             document.getElementById("id_inpt_strdate").disabled = false;
//         });


//     //PARA ISA LANG MA CHECK SA FIRST AND SECOND HALF (END)


