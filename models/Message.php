<?php namespace muvo\yii\telegram\models;

use yii\base\Object;
use yii\helpers\VarDumper;

class Message extends Object
{
    public $message_id;
    public $from;
    public $date;
    public $chat;
    public $forward_from;
    public $forward_from_chat;
    public $forward_date;
    public $reply_to_message;
    public $edit_date;
    public $text;
    public $entities;
    public $audio;
    public $document;
    public $photo;
    public $sticker;
    public $video;
    public $voice;
    public $caption;
    public $contact;
    public $location;
    public $venue;
    public $new_chat_member;
    public $left_chat_member;
    public $new_chat_title;
    public $new_chat_photo;
    public $delete_chat_photo;
    public $group_chat_created;
    public $supergroup_chat_created;
    public $channel_chat_created;
    public $migrate_to_chat_id;
    public $migrate_from_chat_id;
    public $pinned_message;

    public function init(){
        if(!empty($this->date))
            $this->date = (new \DateTime())
                ->setTimestamp($this->date);

        if(!empty($this->forward_date))
            $this->forward_date = (new \DateTime())
                ->setTimestamp($this->forward_date);

        if(!empty($this->from))
            $this->from = new User($this->from);

        if(!empty($this->forward_from))
            $this->forward_from = new User($this->forward_from);

        if(!empty($this->chat))
            $this->chat = new Chat($this->chat);

        if(!empty($this->new_chat_member))
            $this->new_chat_member = new User($this->new_chat_member);

        if(!empty($this->left_chat_member))
            $this->left_chat_member = new User($this->left_chat_member);
    }

    public function setNew_chat_participant($data){
        return $this->new_chat_member = $data;
    }

    public function setLeft_chat_participant($data){
        return $this->left_chat_member = $data;
    }

    public function getIsPrivate(){
        return $this->chat
            ? $this->chat->isPrivate
            : null;
    }

    public static function send($to,$text,$options=array()){
        $bot = \Yii::$app->get('telegram');

        $options['chat_id'] = $to;
        $options['text'] = $text;

        $response = $bot->call('sendMessage',$options);
        if($response->ok!==true){
            \Yii::warning(VarDumper::dumpAsString($response),__METHOD__);
            error_log(VarDumper::dumpAsString($response));
        }

        return $response->ok===true
            ? \Yii::createObject(self::className(),[$response->result])
            : false;
    }

    public function reply($text,$options=array()){
        $options['reply_to_message_id'] = $this->message_id;

        return self::send($this->chat->id,$text,$options);
    }

    public function forward($to,$options=array()){
        $bot = \Yii::$app->get('telegram');

        $options['chat_id'] = $to;
        $options['from_chat_id'] = $this->chat->id;
        $options['message_id'] = $this->message_id;

        $response = $bot->call('forwardMessage',$options);
        if($response->ok!==true)
            error_log(VarDumper::dumpAsString($response));

        return $response->ok===true
            ? \Yii::createObject(self::className(),[$response->result])
            : false;
    }
}
