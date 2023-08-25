<?php
use Db\Model;
require_once '/hris-wews/HRISv1/SpecialFolders/Databases/Model.php';
class TimeOut extends Model{
    protected $table = 'attendance_time_out';
    protected $primaryKey = 'time_out_personId';
}
