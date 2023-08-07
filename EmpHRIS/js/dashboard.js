
/**********time in and time out button function**************/
// const timeInButton = document.getElementById('prev_time_in');
// const timeOutButton = document.getElementById('next_time_out');
// const circle = document.querySelector('.circle');
// const indicator = document.querySelector('.indicator');
// const firstBtnContent = document.querySelector('.firstbtn_content');
// const secondBtnContent = document.querySelector('.secondbtn_content');

// circle.style.display = 'none';
// timeOutButton.style.display = 'none';

// timeInButton.addEventListener('click', function() {
//   circle.style.display = 'inline-flex';
//   circle.style.left = '50%';
//   indicator.style.width = '50%';
//   timeOutButton.style.display = 'inline-flex';
//   firstBtnContent.style.display = 'block';
//   secondBtnContent.style.display = 'none';
// });

// timeOutButton.addEventListener('click', function() {
//   indicator.style.width = '100%';
//   circle.style.left = 'calc(100%)';
//   circle.style.position = 'absolute';
//   secondBtnContent.style.display = 'block';
//   firstBtnContent.style.display = 'block';
// });

// firstBtnContent.addEventListener('click', function() {
//   firstBtnContent.style.display = 'none';
//   circle.style.display = 'inline-flex';
//   circle.style.left = '50%';
//   indicator.style.width = '50%';
//   timeOutButton.style.display = 'inline-flex';
// });

// secondBtnContent.addEventListener('click', function() {
//   secondBtnContent.style.display = 'none';
//   circle.style.display = 'inline-flex';
//   indicator.style.width = '100%';
//   circle.style.left = 'calc(100%)';
//   circle.style.position = 'absolute';
// });

const timeInButton = document.getElementById('prev_time_in');
const timeOutButton = document.getElementById('next_time_out');
const circle = document.querySelector('.circle');
const indicator = document.querySelector('.indicator');
const firstBtnContent = document.querySelector('.firstbtn_content');
const secondBtnContent = document.querySelector('.secondbtn_content');

circle.style.display = 'none';
timeOutButton.style.display = 'inline-flex'; // Display timeOutButton initially

timeInButton.addEventListener('click', function() {
    circle.style.display = 'inline-flex';
    circle.style.left = '50%';
    indicator.style.width = '50%';
    firstBtnContent.style.display = 'block';
    secondBtnContent.style.display = 'none';
    timeOutButton.disabled = false;
});

timeOutButton.addEventListener('click', function() {
    indicator.style.width = '100%';
    circle.style.left = 'calc(100%)';
    circle.style.position = 'absolute';
    secondBtnContent.style.display = 'block';
    firstBtnContent.style.display = 'block';
    timeInButton.disabled = true;
});

firstBtnContent.addEventListener('click', function() {
    firstBtnContent.style.display = 'none';
    circle.style.display = 'inline-flex';
    circle.style.left = '50%';
    indicator.style.width = '50%';
});

secondBtnContent.addEventListener('click', function() {
    secondBtnContent.style.display = 'none';
    circle.style.display = 'inline-flex';
    indicator.style.width = '100%';
    circle.style.left = 'calc(100%)';
    circle.style.position = 'absolute';
});




/**********Current Manila time script**************/
  function displayTime() {
    // get the current time in Manila timezone
    const manilaTime = new Date().toLocaleString("en-US", {
      timeZone: "Asia/Manila",
      hour12: true,
      hour: 'numeric',
      minute: 'numeric',
      second: 'numeric',
    });

    // display the time in the dashboard
    const currentTimeElem = document.getElementById("current_time");
    currentTimeElem.textContent = `Current Time in Manila: ${manilaTime}`;
  }

  // call the displayTime function every second to update the time
  setInterval(displayTime, 1000);

  
// Get the current date in Manila, Philippines
let currentDate = new Date().toLocaleString('en-PH', {
  timeZone: 'Asia/Manila',
  year: 'numeric',
  month: 'long',
  day: 'numeric'
});

// Display the current date above the progress-container
document.getElementById("current_date").textContent = currentDate;




  // Function to get the current time in Manila paste in modal
  function getCurrentTimeInManila() {
    var now = new Date();
    var utcOffset = 8; // Manila is UTC+8
    var utcTime = now.getTime() + (now.getTimezoneOffset() * 60000);
    var manilaTime = new Date(utcTime + (3600000 * utcOffset));
    var hours = manilaTime.getHours();
    var minutes = manilaTime.getMinutes();
    var seconds = manilaTime.getSeconds();
    return hours + ":" + padZero(minutes) + ":" + padZero(seconds);
  }

  // Function to pad single digits with leading zero
  function padZero(num) {
    return num < 10 ? "0" + num : num;
  }

  // Update the <h4> tag with the current time in Manila
  document.addEventListener('DOMContentLoaded', function() {
    var currentTimeElement = document.getElementById('currentTime');
    if (currentTimeElement) {
      currentTimeElement.innerText =  getCurrentTimeInManila();
    }
  });



