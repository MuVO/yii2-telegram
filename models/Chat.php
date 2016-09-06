<?php namespace muvo\yii\telegram\models;

use yii\base\Object;

class Chat extends Object
{
    public $id;
    public $type;
    public $title;
    public $username;
    public $first_name;
    public $last_name;

    public function getIsPrivate(){
        return $this->type === 'private';
    }
}
