<?php
use Db\Model;
require_once '../Databases/Model.php';
class TimeIn extends Model{
    protected $table = 'attendance_time_in';
    protected $primaryKey = 'time_in_personId';
}
