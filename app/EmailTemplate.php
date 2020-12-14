<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    public function parse($data) {
        $parsed = preg_replace_callback('/{{(.*?)}}/', function ($matches) use ($data) {
            list($shortCode, $index) = $matches;

            if (isset($data[$index])) {
                return $data[$index];
            } 
        }, $this->content);

        return $parsed;
    }
}
