<?php

namespace inputs;

// Reuse input form many times
class InputField {

    public static function render($label, $type, $attribute, $placeholder, $model){
        return sprintf("
        <div class=\"form__group\">
            <label>%s</label>
            <input type='%s' name='%s' placeholder='%s' required value='%s' class='%s'>
            <div class=\"invalid-feedback\">%s</div>
        </div>",
            $label,
            $type,
            $attribute,
            $placeholder,
            $model->{$attribute} ?? '',
            $model->hasError($attribute) ? 'is-invalid' : '',
            $model->getFirstError($attribute) ?? ''
        );
    }

    public static function renderForEdit($label, $sub_label, $type, $attribute, $model, $is_disabled = false){
        return sprintf("
        <div class='form__group'>
            <div class='form__label-container'>
                <p class='form__label'>%s</p>
                <p class='form__sub-label'>%s</p>
            </div>
            <input type='%s' name='%s' value='%s'
            class='%s' %s>
            <div class='invalid-feedback'>%s</div>
        </div>
        ",
            $label,
            $sub_label,
            $type,
            $attribute,
            $model->{$attribute} ?? '',
            $model->hasError($attribute) ? 'is-invalid' : '',
            $is_disabled ? "disabled" : "",
            $model->getFirstError($attribute) ?? ''
        );
    }
}
