<?php

namespace core;

use core\db\Dbmodel;

abstract class Usermodel extends Dbmodel {
    abstract public function getDisplayName(): string;
}
