<?php
use Db\Model;
require_once '/hris-wews/HRISv1/SpecialFolders/Databases/Model.php';
class Attendance extends Model{
    protected $table = 'attendance_tb';
    protected $primaryKey = 'id';
}
