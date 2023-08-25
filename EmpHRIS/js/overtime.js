function min_hours() {
  // Get the time values
  var time_from_id = document.getElementById("time_from_id").value;
  var time_to_id = document.getElementById("time_to_id").value;

  // Convert time1 to a Date object
  var date1 = new Date("2000-01-01T" + time_from_id + ":00Z");

  // Convert time2 to a Date object
  var date2 = new Date("2000-01-01T" + time_to_id + ":00Z");

  // Calculate the time difference in milliseconds
  if (date2 < date1) {
    date2.setDate(date2.getDate() + 1);
  }

  // Compute the time difference in milliseconds
  var time_diff_ms = date2.getTime() - date1.getTime();

  // Convert the time difference into hours and minutes
  var time_diff_hours = Math.floor(time_diff_ms / (1000 * 60 * 60));
  var time_diff_minutes = Math.floor((time_diff_ms / (1000 * 60)) % 60);

  // Format the total overtime as HH:MM
  var total_overtime = (time_diff_hours < 10 ? "0" : "") + time_diff_hours + ":" + (time_diff_minutes < 10 ? "0" : "") + time_diff_minutes;

  // Set the value of the total overtime input field
  document.getElementById("ot_id").value = total_overtime;
}

//   function min_hours() {
//   var start_time = document.getElementById("time_from_id").value;
//   var end_time = document.getElementById("time_to_id").value;

//   // Parse the start and end time into Date objects
//   var start_date = new Date("1970-01-01T" + start_time);
//   var end_date = new Date("1970-01-02T" + end_time);

//   // If the end time is earlier than the start time, it means that the end time is on the next day
//   if (end_date < start_date) {
//     end_date.setDate(end_date.getDate() + 1);
//   }

//   // Compute the time difference in milliseconds
//   var time_diff_ms = end_date.getTime() - start_date.getTime();

//   // Convert the time difference into hours and minutes
//   var time_diff_hours = Math.floor(time_diff_ms / (1000 * 60 * 60));
//   var time_diff_minutes = Math.floor((time_diff_ms / (1000 * 60)) % 60);

//   // Format the total overtime as HH:MM
//   var total_overtime = (time_diff_hours < 10 ? "0" : "") + time_diff_hours + ":" + (time_diff_minutes < 10 ? "0" : "") + time_diff_minutes;

//   // Set the value of the total overtime input field
//   document.getElementById("ot_id").value = total_overtime;
// }