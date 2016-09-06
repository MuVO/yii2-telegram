<?php namespace muvo\yii\telegram;

use linslin\yii2\curl\Curl;
use yii\base\Component;
use yii\base\InvalidParamException;
use yii\helpers\Json;
use yii\helpers\VarDumper;

class Bot extends Component
{
    public $token;
    private $curl;

    public function init(){
        $this->curl = new Curl();
    }

    public function call($method,$data=array()){
        $url = sprintf('https://api.telegram.org/bot%s/%s',$this->token,$method);
        $this->curl->reset();

        if(!empty($data)){
            $requestBody = is_array($data)
                ? http_build_query($data)
                : $data;

            $this->curl
                ->setOption(CURLOPT_POSTFIELDS,$requestBody);

            error_log(VarDumper::dumpAsString($requestBody));
            \Yii::trace(VarDumper::dumpAsString($requestBody),__METHOD__);

            $response = $this->curl->post($url);
        } else $response = $this->curl->get($url);

        $result = null;
        try{
            $result = Json::decode($response,false);
        }catch (InvalidParamException $e){
            \Yii::error($e->getMessage(),__METHOD__);
            error_log($e->getMessage());
        }

        return $result;
    }

    public function getMe(){
        return $this->call('getMe');
    }
}
