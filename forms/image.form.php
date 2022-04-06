<?php

namespace forms;

use core\Model;

// For uploading image from one click
class ImageForm extends Model {

    public $photo = '';

    public function rules(): array{
        return [];
    }
}
