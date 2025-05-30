<?php


namespace App\Service;


class TokenGeneratorService
{
    public function generateToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }
}

