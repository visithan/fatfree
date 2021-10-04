<?php

namespace app\controllers;

use app\controllers\Controller;
use app\models\Student;

class StudentController extends Controller
{

    public function getStudent()
    {
        //$r = Student::getById(1);
        //print_r($r);
        $std = new Student($this->db);
        $stds = $std->all();
        $this->f3->set('std', $stds);
        echo \Template::instance()->render('student.htm');
    }
}
