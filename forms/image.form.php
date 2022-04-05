<?php

namespace forms;

use core\Application;
use core\Model;
use models\User;

class ImageForm extends Model {

    public $photo = '';

    public function rules(): array{
        return [];
    }
}
