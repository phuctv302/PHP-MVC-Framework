<?php

namespace core;

abstract class Usermodel extends Dbmodel {
    abstract public function getDisplayName(): string;
}
