<?php

require_once 'NewRecordsHandler.php';
require '../Databases/CustomQuery.php';
require '../Models/TimeIn.php';
require '../Models/TimeOut.php';

$r = new TimeIn();
$d = $r->find('001');
var_dump($d['result']);
