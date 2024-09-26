<?php

namespace Santosdave\PesawiseWrapper\Traits;

use InvalidArgumentException;

trait ValidationTrait
{
    protected function validateRequired(array $data, array $required)
    {
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new InvalidArgumentException("The {$field} field is required.");
            }
        }
    }

    protected function validateNumeric(array $data, array $fields)
    {
        foreach ($fields as $field) {
            if (isset($data[$field]) && !is_numeric($data[$field])) {
                throw new InvalidArgumentException("The {$field} must be numeric.");
            }
        }
    }

    protected function validateString(array $data, array $fields)
    {
        foreach ($fields as $field) {
            if (isset($data[$field]) && !is_string($data[$field])) {
                throw new InvalidArgumentException("The {$field} must be a string.");
            }
        }
    }

    protected function validateEnum(array $data, string $field, array $allowedValues)
    {
        if (isset($data[$field]) && !in_array($data[$field], $allowedValues)) {
            $allowedValuesString = implode(', ', $allowedValues);
            throw new InvalidArgumentException("The {$field} must be one of: {$allowedValuesString}.");
        }
    }

    protected function validateEnumValue(string $field, $value, array $allowedValues): void
    {
        if (!in_array($value, $allowedValues)) {
            $allowedValuesString = implode(', ', $allowedValues);
            throw new InvalidArgumentException("The {$field} must be one of: {$allowedValuesString}.");
        }
    }

    protected function validateUrl(array $fields, array $values): void
    {
        foreach ($fields as $index => $field) {
            if (isset($values[$index]) && !filter_var($values[$index], FILTER_VALIDATE_URL)) {
                throw new InvalidArgumentException("The {$field} must be a valid URL.");
            }
        }
    }

    protected function validatePhone(array $fields, array $values): void
    {
        foreach ($fields as $index => $field) {
            if (isset($values[$index]) && !preg_match('/^\+?[1-9]\d{1,14}$/', $values[$index])) {
                throw new InvalidArgumentException("The {$field} must be a valid phone number.");
            }
        }
    }
}