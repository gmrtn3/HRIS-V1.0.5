<?php
use Db\Model;
require_once '../Databases/Model.php';
class Classification extends Model{
    protected $table = 'classification_tb';
    protected $primaryKey = 'id';
}
