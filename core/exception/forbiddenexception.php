<?php
namespace core\exception;

class Forbiddenexception extends \Exception {
    protected $message = "You don't have permission to access this page";
    protected $code = 403;
}
