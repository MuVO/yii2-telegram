<?php namespace muvo\yii\telegram\models;

use yii\base\InvalidParamException;
use yii\base\Object;
use yii\helpers\Json;

class Update extends Object
{
    public $update_id;
    public $message;
    public $edited_message;
    public $inline_query;
    public $chosen_inline_result;
    public $callback_query;

    public static function parse($json=null){
        $update = null;
        try{
            $update = Json::decode($json,false);
        } catch(InvalidParamException $e){
            \Yii::error($e->getMessage(),__METHOD__);
            error_log($e->getMessage(),__METHOD__);
        }

        return $update
            ? \Yii::createObject(self::className(),[$update])
            : false;
    }

    public function init(){
        if(!empty($this->message))
            $this->message = new Message($this->message);
    }
}
