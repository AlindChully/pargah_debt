<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class CryptoHelper
{
    public static function encryptStrong(string $plainText): string
    {
        $key = base64_decode(str_replace('base64:', '', env('SECRET_ENCRYPTION_KEY')));
        $iv = random_bytes(12);
        $tag = '';

        $cipherText = openssl_encrypt(
            $plainText,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        return base64_encode($iv . $tag . $cipherText);
    }

    public static function decryptStrong(string $encryptedText): string
    {
        $key = base64_decode(str_replace('base64:', '', env('SECRET_ENCRYPTION_KEY')));
        $data = base64_decode($encryptedText);

        $iv  = substr($data, 0, 12);
        $tag = substr($data, 12, 16);
        $cipherText = substr($data, 28);

        return openssl_decrypt(
            $cipherText,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );
    }
}