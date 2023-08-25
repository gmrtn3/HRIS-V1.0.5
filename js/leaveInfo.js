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

    function updateEmployeeDropdown() {
        var deptSelect = document.getElementById("deptSelect");
        var empSelect = document.getElementById("empSelect");
        var selectedDept = deptSelect.options[deptSelect.selectedIndex].value;

        // Fetch the employees based on the selected department using AJAX or other techniques
        // Replace the URL below with the appropriate endpoint that retrieves the employees
        var url = "Data Controller/Leave Information/get_employees.php?department=" + encodeURIComponent(selectedDept);

        // Clear the existing options
        empSelect.innerHTML = "";

        // Fetch the employees from the server
        fetch(url)
            .then(response => response.json())
            .then(data => {
                // Generate the options for the employee dropdown
                data.forEach(employee => {
                    var option = document.createElement("option");
                    option.value = employee.empid;
                    option.text = employee.empid + " - " + employee.fname + " " + employee.lname;
                    empSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error("Error:", error);
            });
    }

    // Call the function on page load if there's a pre-selected department
    document.addEventListener("DOMContentLoaded", function () {
        updateEmployeeDropdown();
    });
    
    

// function vldty(){
//   let set_V_crdt  = document.getElementById("id_v_crdt").value;
//   let toset_V_crdt  = document.getElementById("id_Tv_crdt").value;


//   if(toset_V_crdt > set_V_crdt){
//     document.getElementById("id_btnUpdate").style.backgroundColor = '#3A3939';
//     document.getElementById("id_btnUpdate").style.cursor = "no-drop";
//     document.getElementById("id_btnUpdate").disabled = true;
//     alert('You cannot deduct a credit that is greater than added');
//   }
//   else {
//     document.getElementById("id_btnUpdate").style.backgroundColor = '#787BDB';
//     document.getElementById("id_btnUpdate").disabled = false;
//     document.getElementById("id_Ts_crdt").disabled = false;
//     document.getElementById("id_btnUpdate").style.cursor = "pointer";
//   }
// }

// function vldt1(){
//   let set_S_crdt  = document.getElementById("id_s_crdt").value;
//   let toset_S_crdt  = document.getElementById("id_Ts_crdt").value;


//   if(toset_S_crdt > set_S_crdt){
    
//     document.getElementById("id_btnUpdate").style.backgroundColor = '#3A3939';
//     document.getElementById("id_btnUpdate").style.cursor = "no-drop";
//     document.getElementById("id_btnUpdate").disabled = true;
//     alert('You cannot deduct a credit that is greater than added');


//   }
//   else {
//     document.getElementById("id_btnUpdate").style.backgroundColor = '#787BDB';
//     document.getElementById("id_btnUpdate").disabled = false;
//     document.getElementById("id_TB_crdt").disabled = false;
//     document.getElementById("id_btnUpdate").style.cursor = "pointer";
//   }
// }

// function vldt2(){
//   let set_B_crdt  = document.getElementById("id_B_crdt").value;
//   let toset_B_crdt  = document.getElementById("id_TB_crdt").value;


//   if(toset_B_crdt > set_B_crdt){
    
//     document.getElementById("id_btnUpdate").style.backgroundColor = '#3A3939';
//     document.getElementById("id_btnUpdate").style.cursor = "no-drop";
//     document.getElementById("id_btnUpdate").disabled = true;
//     alert('You cannot deduct a credit that is greater than added');
//   }
//   else {
//     document.getElementById("id_btnUpdate").style.backgroundColor = '#787BDB';
//     document.getElementById("id_btnUpdate").disabled = false;
//     document.getElementById("id_btnUpdate").style.cursor = "pointer";
//   }
// }