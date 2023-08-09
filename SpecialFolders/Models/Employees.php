<?php
use Db\Model;
require_once '/hris-wews/HRISv1/SpecialFolders/Databases/Model.php';
class Employees extends Model{
    protected $table = 'employee_tb';
    protected $primaryKey = 'id';
}
