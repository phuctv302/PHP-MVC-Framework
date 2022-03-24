<?php

namespace core\exception;

class Notfoundexception extends \Exception{
    protected $code = 404;
    protected $message = 'Page not found';
}
