<?php
use Db\Model;
require_once '/hris-wews/HRISv1/SpecialFolders/Databases/Model.php';
class Classification extends Model{
    protected $table = 'classification_tb';
    protected $primaryKey = 'id';
}
