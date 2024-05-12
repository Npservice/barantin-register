<?php

namespace App\Helpers;

class JsonFilterHelper
{
    public static function filter(mixed $data, mixed $filter)
    {
        return collect($data)->filter(function ($item) use ($filter) {
            return is_array($filter) ? in_array($item['id'], $filter) : $item['id'] === $filter;
        });
    }


}
