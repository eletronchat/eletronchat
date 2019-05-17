<?php

/**
*异常接管处理类
*/

namespace app\lib\exception;

use Exception;
use think\exception\Handle;
use app\lib\exception\BaseException;
use think\facade\Config;
use think\facade\Log;
use think\facade\Request;

class ExceptionHandler extends Handle
{
    private $code;      //the statu code for http response
    private $msg;
    private $errorCode; //the custom code
    private $url;       //the request url


    /**
     * the  processing for the exception 
     * 
     * @return complex
     */
    public function render(\Exception $e)
    {
        if ($e instanceof BaseException){
            //to assign the custom exctpion data;
            $this->code      = $e->code;
            $this->msg       = $e->msg;
            $this->errorCode = $e->errorCode;
        }else{
            //return the frendly exception when the debug mode is true  
            if (Config::get('app.app_debug')) {
                return  parent::render($e);
            } else {
                $this->code      = 500;
                $this->msg       = 'sorry，we make a mistake. (^o^)Y';
                $this->errorCode = 999;
                $this->recordErrorLog($e); 
            }
        }
        return json([
            'msg'       => $this->msg,
            'url'       => Request::url(),
            'errorCode' => $this->errorCode
        ], $this->code);
    }


    /**
     * record the error info online 
     *
     */
    private function recordErrorLog($e)
    {
        Log::init([
            "type"  => "File",
            "path"  => Config::get('app.log.path'),
            "level" => ["error"]
         ]);
        Log::record($e->getMessage(), 'error'); 
    }
}


