function min_hours() {
  // Get the time values
  var time_from_id = document.getElementById("time_from_id").value;
  var time_to_id = document.getElementById("time_to_id").value;

  // Convert time1 to a Date object
  var date1 = new Date("2000-01-01T" + time_from_id + ":00Z");

  // Convert time2 to a Date object
  var date2 = new Date("2000-01-01T" + time_to_id + ":00Z");

  // Calculate the time difference in milliseconds
  var timeDifference = date2.getTime() - date1.getTime();

  // Convert the time difference back to a time string
  var hours = Math.floor(timeDifference / 3600000);
  var minutes = Math.floor((timeDifference % 3600000) / 60000);
  var total = (hours < 10 ? "0" : "") + hours + ":" + (minutes < 10 ? "0" : "") + minutes;

  // Set the value of the total input
  document.getElementById("ot_id").value = total;
}