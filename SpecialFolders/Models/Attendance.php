<?php
use Db\Model;
require_once '../Databases/Model.php';
class Attendance extends Model{
    protected $table = 'attendance_tb';
    protected $primaryKey = 'id';
}
