if the login as admin does not work. 

input in the hris_db -> user_tb a Credentials

the role as admin is a must. The role in the user_tb must be all lowercase and the value is admin. 


<script>
var frequencySelect = document.getElementById('frequencyInput');
var startDateInput = document.getElementById('startDate');
var endDateInput = document.getElementById('endDate');
var saveButton = document.getElementById('btn_save');

function handleFrequencyChange() {
  var selectedFrequency = frequencySelect.value;

  if (selectedFrequency === 'Daily') {
    endDateInput.readOnly = true;
    endDateInput.value = startDateInput.value;
  } else {
    endDateInput.readOnly = false;
  }

  validateEndDate();
}

function validateEndDate() {
  var startDate = new Date(startDateInput.value);
  var endDate = new Date(endDateInput.value);

  if (frequencySelect.value === 'Weekly' && startDate.getTime() === endDate.getTime()) {
    saveButton.disabled = true;

    var errorMessage = document.getElementById('error-msg');

    if (!errorMessage) {
      errorMessage = document.createElement('div');
      errorMessage.id = 'error-msg';
      errorMessage.className = 'alert alert-danger mt-2';
      errorMessage.innerText = 'Start Date and End Date cannot be the same for Weekly frequency';

      var endDateDiv = document.getElementById('endDate').parentNode;
      endDateDiv.parentNode.insertBefore(errorMessage, endDateDiv);
    }
  } else {
    saveButton.disabled = false;

    var errorMessage = document.getElementById('error-msg');

    if (errorMessage) {
      errorMessage.remove();
    }
  }
}

frequencySelect.addEventListener('change', handleFrequencyChange);
startDateInput.addEventListener('change', function () {
  if (frequencySelect.value === 'Daily') {
    endDateInput.value = startDateInput.value;
  }
  validateEndDate();
});
endDateInput.addEventListener('change', validateEndDate);

handleFrequencyChange();
</script>