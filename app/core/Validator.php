<?php

class Validator
{
    private $data;
    private $errors = [];
    
    // Custom messages
    private $messages = [
        'required' => 'The :field field is required.',
        'email' => 'Please enter a valid email address.',
        'min' => 'The :field must be at least :param characters.',
        'max' => 'The :field must not exceed :param characters.',
        'match' => 'The :field must match :param.',
        'unique' => 'The :field is already taken.',
        'in' => 'The selected :field is invalid.'
    ];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function validate($rules)
    {
        foreach ($rules as $field => $ruleset) {
            $rulesArray = explode('|', $ruleset);
            
            foreach ($rulesArray as $rule) {
                $params = [];
                
                // Parse rule:param
                if (strpos($rule, ':') !== false) {
                    list($ruleName, $paramStr) = explode(':', $rule, 2);
                    $params = explode(',', $paramStr);
                } else {
                    $ruleName = $rule;
                }

                $method = 'check' . ucfirst($ruleName);
                
                if (method_exists($this, $method)) {
                    // Check if field exists in data, allow null if not required (unless it IS required)
                    $value = isset($this->data[$field]) ? $this->data[$field] : null;

                    if (!$this->$method($field, $value, $params)) {
                        // Error found
                        $this->addError($field, $ruleName, $params);
                        // Stop validating this field if required failed? 
                        // Usually we want all errors, but if 'required' fails, others might crash (like min length of null).
                        if ($ruleName === 'required') break; 
                    }
                }
            }
        }

        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function hasError($field)
    {
        return isset($this->errors[$field]);
    }

    public function getError($field)
    {
        return isset($this->errors[$field]) ? $this->errors[$field][0] : '';
    }

    private function addError($field, $rule, $params)
    {
        $message = isset($this->messages[$rule]) ? $this->messages[$rule] : 'Invalid value.';
        $message = str_replace(':field', str_replace('_', ' ', ucfirst($field)), $message);
        
        if (!empty($params)) {
            $message = str_replace(':param', $params[0], $message);
        }

        $this->errors[$field][] = $message;
    }

    // Rules
    
    private function checkRequired($field, $value, $params)
    {
        return !empty(trim($value));
    }

    private function checkEmail($field, $value, $params)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function checkMin($field, $value, $params)
    {
        return strlen($value) >= $params[0];
    }

    private function checkMax($field, $value, $params)
    {
        return strlen($value) <= $params[0];
    }

    private function checkMatch($field, $value, $params)
    {
        $targetField = $params[0];
        return $value === $this->data[$targetField];
    }

    private function checkIn($field, $value, $params)
    {
        return in_array($value, $params);
    }

    // Unique check requires Database access. 
    // We can pass a callback or a model, but for simplicity let's handle "unique" 
    // outside or pass a callback closure as a custom rule?
    // For now, let's keep it simple and handle "unique" via custom callback or keep it manual in controller for DB calls.
    // Or we can inject a check function.
}
