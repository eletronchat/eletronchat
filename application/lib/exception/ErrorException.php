<?php
 /*
	* 服务器内部错误
	*
	*/

namespace app\lib\exception;

class ErrorException extends BaseException
{
     public $code      = 500;
     public $msg       = "something has gone wrong on the web site's server";
}
