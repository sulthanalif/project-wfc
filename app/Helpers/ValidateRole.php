<?php

namespace App\Helpers;

class ValidateRole
{
    public static function check($role)
    {
        return auth()->user()->hasRole($role);
    }
}
