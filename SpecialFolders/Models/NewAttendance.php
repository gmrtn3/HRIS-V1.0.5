<?php
use Db\Model;
require_once '../Databases/Model.php';
class NewAttendance extends Model{
    protected $table = 'new_attendance';
    protected $primaryKey = 'empid';
}
