<?php


namespace core;


use models\Model;

abstract class DbModel extends Model
{
    abstract public function tableName();

    abstract public function attributes();

    public function save(){
        $tableName = $this->tableName();
        $attributes = $this->attributes();

        //Application::$app->db
    }

    public function prepare(){

    }
}