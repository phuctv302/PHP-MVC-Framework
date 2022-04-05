<?php

namespace forms;

use core\Model;

class ImageForm extends Model {

    public $photo = '';

    public function rules(): array{
        return [];
    }
}
