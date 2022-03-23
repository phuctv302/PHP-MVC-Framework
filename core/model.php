<?php
namespace core;

abstract class Model{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';

    public function loadData($data){
        foreach ($data as $key => $value){
            if (property_exists($this, $key)){
                $this->{$key} = $value;
            }
        }
    }

    abstract public function rules(): array;

    public array $errors = [];

    public function validate(){
        foreach ($this->rules() as $attribute => $rules){
            $value = $this->{$attribute};
            foreach ($rules as $rule){
                $rule_name = $rule;
                if (!is_string($rule_name)){
                    $rule_name = $rule[0];
                }
                if ($rule_name === self::RULE_REQUIRED && !$value){
                    $this->addError($attribute, self::RULE_REQUIRED);
                }
                if ($rule_name === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($attribute, self::RULE_EMAIL);
                }
                if ($rule_name == self::RULE_MIN && strlen($value) < $rule['min']){
                    $this->addError($attribute, self::RULE_MIN, $rule);
                }
                if ($rule_name == self::RULE_MAX && strlen($value) > $rule['max']){
                    $this->addError($attribute, self::RULE_MAX, $rule);
                }
                if ($rule_name == self::RULE_MATCH && $value !== $this->{$rule['match']}){
                    $this->addError($attribute, self::RULE_MATCH, $rule);
                }
                if ($rule_name === self::RULE_UNIQUE){
                    $class_name = $rule['class'];
                    $uniqueAttr = $rule['attribute'] ?? $attribute;
                    $table_name = $class_name::tableName();
                    $statement = Application::$app->db->prepare("SELECT * FROM $table_name WHERE $uniqueAttr = :attr");
                    $statement->bindValue(":attr", $value);
                    $statement->execute();
                    $record = $statement->fetchObject();
                    if ($record){
                        $this->addError($attribute, self::RULE_UNIQUE, ['field' => $attribute]);
                    }
                }

            }
        }

        return empty($this->errors); // errors is empty means NO ERROR
    }

    public function addError(string $attribute, string $rule, $params = []){
        $message = $this->errorMessages()[$rule] ?? '';
        foreach($params as $key => $value){
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function errorMessages(){
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'This field must be valid email address',
            self::RULE_MIN => 'Min length of this field must be {min}',
            self::RULE_MAX => 'Max length of this field must be {max}',
            self::RULE_MATCH => 'This field must be the same as {match}',
            self::RULE_UNIQUE => 'Record with this {field} already exists'
        ];
    }

    public function hasError($attribute){
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute){
        return $this->errors[$attribute][0] ?? false;
    }
}
