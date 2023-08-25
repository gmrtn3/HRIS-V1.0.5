// search bar 

const searchBar = document.getElementById("search_bar");
const dataTable = document.getElementById("data_table");
const headerRow = dataTable.querySelector("thead tr");

searchBar.addEventListener("input", () => {
  const searchTerm = searchBar.value.toLowerCase();
  const rows = dataTable.getElementsByTagName("tr");
  for (let i = 0; i < rows.length; i++) {
    if (rows[i] === headerRow) {
      // always display the header row
      headerRow.style.display = "";
    }else {
    const cells = rows[i].getElementsByTagName("td");
    let matchFound = false;
    for (let j = 0; j < cells.length; j++) {
      const cellText = cells[j].textContent.toLowerCase();
      if (cellText.indexOf(searchTerm) > -1) {
        matchFound = true;
        break;
      }
    }
    if (matchFound) {
      rows[i].style.display = "";
    } else {
      rows[i].style.display = "none";
    }
   }
  }
});



function strvalidate() {
    let today = new Date(); // Get the current date and time
    let id_inpt_strTime = new Date(document.getElementById("id_inpt_strdate").value); // Get the date input value as a Date object
    //let id_inpt_strTime = new Date(document.getElementById("id_inpt_strdate").value.replace(/-/g, '\/'));

    if (id_inpt_strTime.getTime() < today.getTime()) { // Compare the two dates using getTime()

        alert('You cannot select the date today and the past dates');
        document.getElementById("id_btnsubmit").style.cursor = "no-drop";
        document.getElementById("id_btnsubmit").disabled = true;      
        document.getElementById("id_inpt_enddate").disabled = true;
    } 

    else {
        document.getElementById("id_btnsubmit").style.cursor = "pointer";
        document.getElementById("id_btnsubmit").disabled = false;
        document.getElementById("id_inpt_enddate").disabled = false;
    }
  }

  function endvalidate() {
    let id_inpt_strTime1 = new Date(document.getElementById("id_inpt_strdate").value);
    let id_inpt_endTime1 = new Date(document.getElementById("id_inpt_enddate").value);
    let id_leavePeriod = document.getElementById("id_leavePeriod");
    let leavePeriodText = id_leavePeriod.options[id_leavePeriod.selectedIndex].text;
  
    if (leavePeriodText === 'Half Day') {
            if (id_inpt_strTime1.getTime() !== id_inpt_endTime1.getTime()) {
              alert("For half-day leaves, the start and end dates must be the same.");
              document.getElementById("id_btnsubmit").style.cursor = "no-drop";
              document.getElementById("id_btnsubmit").disabled = true;
            } else {
              if (id_inpt_strTime1.getTime() > id_inpt_endTime1.getTime()) {
                alert("Please set the End Date not before the Start Date");
                document.getElementById("id_btnsubmit").style.cursor = "no-drop";
                document.getElementById("id_btnsubmit").disabled = true;
              } else {
                document.getElementById("id_btnsubmit").style.cursor = "pointer";
                document.getElementById("id_btnsubmit").disabled = false;
              }
            }
    } else { //if fullday
              if (id_inpt_strTime1.getTime() === id_inpt_endTime1.getTime()) {
                alert("For Full-day leaves, the start and end dates must NOT be the same.");
                document.getElementById("id_btnsubmit").style.cursor = "no-drop";
                document.getElementById("id_btnsubmit").disabled = true;
              }else{
            //else
            if (id_inpt_strTime1.getTime() > id_inpt_endTime1.getTime()) {
              alert("Please set the End Date not before the Start Date");
              document.getElementById("id_btnsubmit").style.cursor = "no-drop";
              document.getElementById("id_btnsubmit").disabled = true;
            } else {
              document.getElementById("id_btnsubmit").style.cursor = "pointer";
              document.getElementById("id_btnsubmit").disabled = false;
            }
              }
      
    }
  }



    //PARA ISA LANG MA CHECK SA FIRST AND SECOND HALF
        function halfdaysides(){
          let halfday_side = document.getElementById('id_leavePeriod').value;

          if(halfday_side === 'Half Day'){
            document.getElementById('id_chckfirsthalf').style.display = "flex";
            document.getElementById('id_chckSecondhalf').style.display = "flex";
          }
          else{
            document.getElementById('id_chckfirsthalf').style.display = "none";
            document.getElementById('id_chckSecondhalf').style.display = "none";
          }
        }
        


        const firstHalfCheckbox = document.querySelector('input[name="firstHalf"]');
        const secondHalfCheckbox = document.querySelector('input[name="secondHalf"]');
        firstHalfCheckbox.addEventListener('click', function() {
            secondHalfCheckbox.checked = !this.checked;
        });
        secondHalfCheckbox.addEventListener('click', function() {
            firstHalfCheckbox.checked = !this.checked;
        });


    //PARA ISA LANG MA CHECK SA FIRST AND SECOND HALF (END)


//PARA MAG CHANGE SA TEXT NG CHECKBOX TO PAY AND WITHOUT PAY

    // Get the checkbox element
    var checkbox = document.getElementById('checkbox_wthPay');

    // Add event listener for checkbox change
    checkbox.addEventListener('change', function() {
        // Get the input element
        var inputValue = document.getElementById('chnge_val');

        // Update input value based on checkbox checked state
        if (this.checked) {
            inputValue.value = 'Leave With Pay';
            inputValue.style.color = '#ffffff';
            inputValue.style.backgroundColor = 'green';
        } else {
            inputValue.value = 'Leave Without Pay';
            inputValue.style.color = '#ffffff';
            inputValue.style.backgroundColor = 'red';
        }
    });

//PARA MAG CHANGE SA TEXT NG CHECKBOX TO PAY AND WITHOUT PAY (END)



  //PARA SA PAG LAGAY NG SORTING NG DATATABLES
/**
 * @param {HTMLTableElement} table The Table to sort
 * @param {number} column The index of the column to sort
 * @param {boolean} asc Deterrmines if teh sorting wil be in ascending
 */

function sorTableByColumn(table, column, asc = true){
  const dirModifier = asc ? 1: -1;
  const tBody = table.tBodies[0];
  const rows = Array.from(tBody.querySelectorAll("tr"));

  //sortss each row

  const sortedRows = rows.sort((a, b)=>{
   
   const aColText = a.querySelector(`td:nth-child(${column + 1})`).textContent.trim();
   const bColText = b.querySelector(`td:nth-child(${column + 1})`).textContent.trim();

   return aColText > bColText ? (1 * dirModifier) : (-1 * dirModifier);
 });
 //Remove all exisiting tr  from the table

 while (tBody.firstChild){
   tBody.removeChild(tBody.firstChild);  
 }

 //readd the newly sorted rows
tBody.append(...sortedRows);
 //Remember how the column is currently sorted
 table.querySelectorAll("th").forEach(th => th.classList.remove("th-sort-asc", "th-sort-desc"));
 table.querySelector(`th:nth-child(${column + 1})`).classList.toggle("th-sort-asc", asc);
 table.querySelector(`th:nth-child(${column + 1})`).classList.toggle("th-sort-desc", !asc);
}
document.querySelectorAll(".table-sortable th").forEach(headerCell => {
 headerCell.addEventListener("click", () => {
   const tableElement = headerCell.parentElement.parentElement.parentElement;
   const headerIndex = Array.prototype.indexOf.call(headerCell.parentElement.children ,headerCell);
   const currentIsAscending = headerCell.classList.contains("th-sort-asc");

   sorTableByColumn(tableElement, headerIndex, !currentIsAscending); 
 });
});