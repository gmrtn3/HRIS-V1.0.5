

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