<?php
session_start();
if (isset($_POST['isChecked'])) {
  $_SESSION['hide_piece'] = ($_POST['isChecked'] == 'true');
}
