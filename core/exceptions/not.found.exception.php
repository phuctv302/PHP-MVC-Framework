<?php

namespace core\exceptions;

use Exception;

// For accessing routes not exist
class NotFoundException extends Exception {
    protected $code = 404;
    protected $message = 'Page not found';
}
