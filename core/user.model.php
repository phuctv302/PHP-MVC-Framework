<?php

namespace core;

use core\db\DbModel;

abstract class UserModel extends DbModel{
    abstract public function getDisplayName();
}
