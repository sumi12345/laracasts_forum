<?php

namespace App;


class Spam
{
    public function detect($body)
    {
        if (mb_strpos($body, 'Yahoo') !== false) {
            throw new \Exception('Your reply contains spam.');
        }

        return false;
    }
}