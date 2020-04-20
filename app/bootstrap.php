<?php
//load config

use MVCPHP\helpers\AppSessionHandler;
use MVCPHP\libraries\Database;
use MVCPHP\libraries\Registry;

require_once 'config'.DIRECTORY_SEPARATOR.'config.php';
//load Libraries


require_once 'helpers/session.php';


// auto loader read all classes in app directory

require_once 'libraries'.DS.'Autoload.php';

$session = new AppSessionHandler();// this session has session handler and oppots session for project in folder session  in helper directory
$session->start();

$db =new Database();
$registry = new Registry();
$registry->set('db',$db);

require_once 'helpers/URL_helper.php';
