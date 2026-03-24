<?php

namespace App\Services;

class DataNormalizer
{
    public static function normalize(string $data): string
    {
        $data = trim($data);
        $data = str_replace("\t", " ", $data);
        return preg_replace('/\s+/', ' ', $data);
    }
}
