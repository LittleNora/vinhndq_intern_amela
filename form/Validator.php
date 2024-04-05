<?php

class Validator
{
    const MIN_LENGTH = 6;
    const MAX_LENGTH = 255;
    private array $errors = [];

    private array $data = [];

    private $messages = [
        'required' => 'The :attribute field is required',
        'email' => 'The :attribute field is not a valid email address',
        'min' => 'The :attribute field must be at least :min characters',
        'max' => 'The :attribute field must not exceed :max characters',
        'regex' => 'The :attribute field is invalid'
    ];

    private $fieldNames = [];

    public function validate($rules, $messages = [], $fields = []): void
    {
        $this->messages = array_merge($this->messages, $messages);

        if (!empty($fields)) {
            $this->fieldNames = $fields;
        }

        foreach ($rules as $field => $rule) {
            if (!isset($this->data[$field])) {
                continue;
            }

            if (!is_array($rule)) {
                $rules = explode('|', $rule);
            } else {
                $rules = $rule;
            }

            foreach ($rules as $rule) {
                $this->validateRule($field, $rule, $this->data[$field]);
            }
        }
    }

    public function make($data = [])
    {
        $this->data = $data;
        return $this;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function hasErrors($field = null)
    {
        if (empty($this->errors)) {
            return false;
        }

        return $field ? isset($this->errors[$field]) && count($this->errors[$field]) > 0 : count($this->errors) > 0;
    }

    public function getFirstError($field)
    {
        $firstKey = array_key_first($this->errors[$field]);

        return $this->errors[$field][$firstKey] ?? null;
    }

    public function getError($field)
    {
        return $this->errors[$field] ?? [];
    }

    public function getValidatedData()
    {
        return $this->data;
    }

    private function validateRule($field, $rule, $value)
    {
        $rules = explode(':', $rule, 2);

        $rule = $rules[0];

        $valueValidate = $rules[1] ?? null;

        $method = 'validate' . ucfirst($rule);

        if (method_exists($this, $method)) {
            $this->$method($rule, $field, $value, $valueValidate);
        } else {
            throw new Exception("Method $rule not found");
        }
    }

    private function validateRequired($rule, $field, $value, ...$args)
    {
        if (empty($value)) {
            $this->errors[$field][$rule] = $this->getMessage($rule, $field, $value);
        }
    }

    private function validateEmail($rule, $field, $value, ...$args)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][$rule] = $this->getMessage($rule, $field, $value);
        }
    }

    private function validateMin($rule, $field, $value, $min = null)
    {
        $min = $min ?? self::MIN_LENGTH;
        if (mb_strlen($value, 'UTF-8') < $min) {
            $this->errors[$field][$rule] = $this->getMessage($rule, $field, $min);
        }
    }

    private function validateMax($rule, $field, $value, $max)
    {
        $max = $max ?? self::MAX_LENGTH;
        if (mb_strlen($value, 'UTF-8') > $max) {
            $this->errors[$field][$rule] = $this->getMessage($rule, $field, $max);
        }
    }

    private function validateRegex($rule, $field, $value, $pattern)
    {
        if (!preg_match($pattern, $value)) {
            $this->errors[$field][$rule] = $this->getMessage($rule, $field, $pattern);
        }
    }

    private function getMessage($rule, $field, $value)
    {
        $msg = $this->messages["{$field}.{$rule}"] ?? $this->messages[$rule];

        $msg = preg_replace('/:attribute/i', $this->fieldNames[$field] ?? ucfirst($field), $msg);

        return preg_replace('/:' . $rule . '/i', $value, $msg);
    }
}