<?php
use Db\Model;
require_once '../Databases/Model.php';
class Employees extends Model{
    protected $table = 'employee_tb';
    protected $primaryKey = 'id';
}
