//Start Date and End Date Validation
function datevalidate() {
  let start_date = document.getElementById('start_date').value;
  let end_date = document.getElementById('end_date').value;

  if (start_date === '') {
    alert('Please fill out the Start Date field first and Take note Start Date is lower than End Date.');
    document.getElementById('start_date').focus();
    document.getElementById('submit-btn').disabled = true;
  } else if (start_date > end_date) {
    alert('End Date must be greater than Start Date');
    document.getElementById('submit-btn').disabled = true;
  } else {
    document.getElementById('submit-btn').disabled = false;
  }
}


//Start time and End time Validation
function timevalidate() {
  let start_time = document.getElementById('start_time').value;
  let end_time = document.getElementById('end_time').value;

  if (start_time === '') {
    alert('Please fill out the Start Time field first and Take note Start time is lower than End Time');
    document.getElementById('start_time').focus();
    document.getElementById('submit-btn').disabled = true;
  } else if (start_time > end_time) {
    alert('End Time must be greater than Start Time');
    document.getElementById('submit-btn').disabled = true;
  } else {
    document.getElementById('submit-btn').disabled = false;
  }
}


document.querySelector('form').addEventListener('submit', function (event) {
  let start_time = document.getElementById('start_time').value;
  let end_time = document.getElementById('end_time').value;

  if (start_time > end_time) {
    alert('End Time must be greater than Start Time');
    event.preventDefault();
  }
});


