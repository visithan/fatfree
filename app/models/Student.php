<?php

namespace app\models;
//use app\models\Model;

class Student extends \DB\SQL\Mapper
{
    //static protected $_tableName = 'students_sm'; // database table name
    public function __construct(\DB\SQL $db)
    {
        parent::__construct($db, 'students_sm');
    }

    public function all()
    {
        $this->load();
        return $this->query;
    }

    public function getById($id)
    {
        $this->load(array('std_id=?', $id));
        return $this->query;
    }

    // public function add()
    // {
    //     $this->copyFrom('POST');
    //     $this->save();
    // }

    // public function edit($id)
    // {
    //     $this->load(array('id=?', $id));
    //     $this->copyFrom('POST');
    //     $this->update();
    // }

    // public function delete($id)
    // {
    //     $this->load(array('id=?', $id));
    //     $this->erase();
    // }
}
