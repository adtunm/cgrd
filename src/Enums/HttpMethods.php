<?php

namespace App\Enums;

enum HttpMethods
{
    case GET;
    case POST;
    case DELETE;
    case PUT;

    public static function getFromValue(string $name): self
    {
        foreach (self::cases() as $case) {
            if($case->name === $name) {
                return $case;
            }
        }

        throw new \Exception('Method not defined.');
    }
}
