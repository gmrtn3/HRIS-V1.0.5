const frequencyDropdown = document.getElementById('frequency');
const cutoffDropdown = document.getElementById('cutoff');

frequencyDropdown.addEventListener('change', () => {
  cutoffDropdown.innerHTML = ''; // clear the cutoff options

  if (frequencyDropdown.value === 'Monthly') {
    // add one cutoff option
    const option = document.createElement('option');
    option.value = '1';
    option.text = '1';
    cutoffDropdown.add(option);
  } else if (frequencyDropdown.value === 'Semi-Month') {
    // add two cutoff options
    for (let i = 1; i <= 2; i++) {
      const option = document.createElement('option');
      option.value = i.toString();
      option.text = i.toString();
      cutoffDropdown.add(option);
    }
  } else if (frequencyDropdown.value === 'Weekly') {
    // add four cutoff options
    for (let i = 1; i <= 4; i++) {
      const option = document.createElement('option');
      option.value = i.toString();
      option.text = i.toString();
      cutoffDropdown.add(option);
    }
  }
});

//para sa selection box (ALL EMPLOYEE)
const dropdownBtn = document.querySelector('.dropdown-btn');
const dropdownContent = document.querySelector('.dropdown-content');
const selectedItemsInput = document.querySelector('#items_EMP');

dropdownBtn.addEventListener('click', () => {
  dropdownContent.classList.toggle('show');
});

window.addEventListener('click', (event) => {
  if (!event.target.matches('.dropdown-btn')) {
    const dropdowns = document.querySelectorAll('.dropdown-content');
    dropdowns.forEach((dropdown) => {
      if (dropdown.classList.contains('show')) {
        dropdown.classList.remove('show');
      }
    });
  }

});

document.querySelectorAll('.emp_lblchckbox').forEach((checkbox) => {
  checkbox.addEventListener('change', () => {
    const selectedItems = Array.from(document.querySelectorAll('.emp_lblchckbox:checked')).map((checkbox) => {
      return checkbox.nextSibling.textContent.trim();
    });
    selectedItemsInput.value = selectedItems.join(', ');
  });
});