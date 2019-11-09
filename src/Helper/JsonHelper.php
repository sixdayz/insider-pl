<?php

namespace App\Helper;

class JsonHelper
{
    public static function encode($data): string
    {
        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    public static function decode(string $json): array
    {
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }
}