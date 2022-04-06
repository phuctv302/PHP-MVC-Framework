<?php

namespace core\exceptions;

// For accessing routes is not authenticated (not logged in yet)
class ForbiddenException extends \Exception {
    protected $message = "You don't have permission to access this page";
    protected $code = 403;
}
