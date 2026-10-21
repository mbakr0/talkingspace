<?php

class Validator
{
    public function isRequired($fields)
    {
        foreach ((array)$fields as $field) {
            if (trim((string)($_POST[$field] ?? '')) === '') return false;
        }
        return true;
    }

    public function isValidEmail($email)
    {
        return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function passwordMatch($pw1, $pw2)
    {
        return hash_equals((string)$pw1, (string)$pw2);
    }
}
