<?php
namespace carry0987\Sharp\Utils;

class HTTPUtil
{
    public static function base64UrlEncode(string $data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
