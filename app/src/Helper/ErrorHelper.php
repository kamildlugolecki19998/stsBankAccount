<?php

namespace App\Helper;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ErrorHelper
{
    public static function normalizeErrors(ConstraintViolationListInterface $errors): array
    {
        $return = [];

        foreach ($errors as $error) {
            $return[] = [
                'errorMessage' => $error->getMessage()
            ];
        }

        return $return;
    }
}
