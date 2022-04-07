<?php

namespace core\exceptions;

// For accessing routes is not authenticated (not logged in yet)
/**
 * AT first will be @throwed in Application
 * When this exception occurs => redirect to login page
 * */
class ForbiddenException extends \Exception {
    protected $message = "You don't have permission to access this page";
    protected $code = 403;
}
