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
}