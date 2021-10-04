<?php

// Kickstart the framework
$f3 = require('lib/base.php');
require('app/models/rb-mysql.php');
require('app/models/idiorm.php'); //ORM




$f3->set('DEBUG', 1);
if ((float)PCRE_VERSION < 8.0)
	trigger_error('PCRE version is out of date');

// Load configuration
$f3->config('config.ini');
$f3->set('AUTOLOAD', 'app/models/');
$f3->config('app/routes.ini');

$f3->set('DB', new DB\SQL(
	'mysql:host=localhost;port=3306;dbname=test',
	'root',
	''
));

ORM::configure(array(
    'connection_string' => 'mysql:host=localhost;dbname=test',
    'username' => 'root',
    'password' => ''
));


$f3->route(
	'GET|POST /',
	function ($f3) {
		$result = $f3->get('DB')->exec('SELECT * FROM students_sm ORDER BY std_id' );
		$f3->set('students', $result);

		//$std_table = ORM::for_table('students_sm')->find_array();
		/*$std_table = ORM::for_table('students_sm')
						->raw_query('SELECT * FROM students_sm')
						->find_array();*/
		ORM::configure('id_column', 'std_id');
		$std_table = ORM::for_table('students_sm')->find_one(1);
		$f3->set('students2', $std_table);

		//$std_table = new DB\SQL\Mapper($f3->get('DB'), 'students_sm');
		//$std_table->load();
		//$std_table->paginate(2, 5);
		//$f3->set('students2', $std_table);

		//R::setup('mysql:host=localhost;dbname=test', 'root', '');
		//$std_table = R::findAll('students_sm');

		//$result = $std_table;
		//$result = Student::count();
		//$f3->set('std_table', $result);
		$f3->set('content', 'welcome.htm');
		echo View::instance()->render('layout.htm');
		//echo Template::instance()->render('layout.htm');
	}
);

$f3->route(
	'GET|POST /addstudent',
	function ($f3) {
		if ($f3->get('POST')) {

			/*$std_save = new DB\SQL\Mapper($f3->get('DB'), 'students_sm');
			$std_save->std_regno =  $f3->get('POST.std_regno');
			$std_save->std_name = $f3->get('POST.std_name');
			$std_save->std_email = $f3->get('POST.std_email');
			$std_save->std_dob = $f3->get('POST.std_dob');
			$std_save->save();*/

			$std_save = ORM::for_table('students_sm')->create();
			$std_save->std_regno =  $f3->get('POST.std_regno');
			$std_save->std_name = $f3->get('POST.std_name');
			$std_save->std_email = $f3->get('POST.std_email');
			$std_save->std_dob = $f3->get('POST.std_dob');
			$std_save->save();


			$f3->reroute($f3->get('BASEURL'));
		}
		$f3->set('content', 'addstudent.htm');
		echo View::instance()->render('layout.htm');
	}
);

$f3->route(
	'GET|POST /editstudent/@id',
	function ($f3) {
		$id = $f3->get('PARAMS.id');

		/*$std_edit = new DB\SQL\Mapper($f3->get('DB'), 'students_sm');
		$std_edit->load("std_id = $id");
		$f3->set('stdata', $std_edit);
		if ($f3->get('POST')) {
			$std_edit->std_regno =  $f3->get('POST.std_regno');
			$std_edit->std_name = $f3->get('POST.std_name');
			$std_edit->std_email = $f3->get('POST.std_email');
			$std_edit->std_dob = $f3->get('POST.std_dob');
			$std_edit->save();
			$f3->reroute($f3->get('BASEURL'));
		}*/
		ORM::configure('id_column', 'std_id');
		$std_edit = ORM::for_table('students_sm')->find_one($id);
		$f3->set('stdata', $std_edit);
		if($f3->get('POST')){
			$std_edit->std_regno =  $f3->get('POST.std_regno');
			$std_edit->std_name = $f3->get('POST.std_name');
			$std_edit->std_email = $f3->get('POST.std_email');
			$std_edit->std_dob = $f3->get('POST.std_dob');
			// Syncronise the object with the database
			$std_edit->save();	
			$f3->reroute($f3->get('BASEURL'));		
		}


		$f3->set('content', 'addstudent.htm');
		echo View::instance()->render('layout.htm');
	}
);

$f3->route(
	'GET /deletestudent/@id',
	function ($f3) {
		$id = $f3->get('PARAMS.id');

		/*$std_del = new DB\SQL\Mapper($f3->get('DB'), 'students_sm');
		$std_del->load("std_id = $id");
		$std_del->erase();*/
		ORM::configure('id_column', 'std_id');
		$std_del = ORM::for_table('students_sm')->find_one($id);
		$std_del->delete();

		$f3->reroute($f3->get('BASEURL'));
	}
);

$f3->route(
	'GET /apistds',
	function ($f3) {
		//$result = $f3->get('DB')->exec('SELECT * FROM students_sm');

		R::setup('mysql:host=localhost;dbname=test', 'root', '');
		//$result = R::getAll('SELECT * FROM students_sm');
		//$result = R::exec('SELECT * FROM students_sm');
		//$result = R::getAssoc('SELECT * FROM students_sm');
		$result = R::findAll('students_sm');
		echo json_encode($result);
	}
);


$f3->route(
	'GET /userref',
	function ($f3) {
		$f3->set('content', 'userref.htm');
		echo View::instance()->render('layout.htm');
	}
);

$f3->run();
