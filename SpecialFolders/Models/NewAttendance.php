<?php
use Db\Model;
require_once '/hris-wews/HRISv1/SpecialFolders/Databases/Model.php';
class NewAttendance extends Model{
    protected $table = 'new_attendance';
    protected $primaryKey = 'empid';
}
