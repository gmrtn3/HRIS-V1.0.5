function calculate_hours() {
  var start_time = document.getElementById("start_time_id").value;
  var end_time = document.getElementById("end_time_id").value;

  if (start_time > end_time) {
    var diff_in_minutes = (new Date("1970-01-01T" + start_time + "Z") - new Date("1970-01-01T" + end_time + "Z")) / 60000;
    var undertime_hours = Math.floor(diff_in_minutes / 60);
    var undertime_minutes = diff_in_minutes % 60;
    var undertime = (undertime_hours < 10 ? "0" + undertime_hours : undertime_hours) + ":" + (undertime_minutes < 10 ? "0" + undertime_minutes : undertime_minutes);
    document.getElementById("undertime_id").value = undertime;
  } else {
    document.getElementById("undertime_id").value = "00:00";
  }
}