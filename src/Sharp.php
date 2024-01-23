<?php
namespace carry0987\Sharp;

use carry0987\Sharp\Utils\HTTPUtil;
use carry0987\Sharp\Exceptions\SharpException;

class Sharp
{
    protected $signatureKey;
    protected $signatureSalt;
    protected $sourceKey;

    /**
     * Sharp constructor.
     * @param string $signatureKey Hexadecimal signature key.
     * @param string $signatureSalt Hexadecimal signature salt.
     * @param string $sourceKey Hexadecimal source key.
     */
    public function __construct(string $signatureKey, string $signatureSalt, string $sourceKey)
    {
        $this->signatureKey = hex2bin($signatureKey);
        $this->signatureSalt = hex2bin($signatureSalt);
        $this->sourceKey = hex2bin($sourceKey);
    }

    /**
     * Generate an encrypted URL to be used with Sharp-API.
     * @param string $originalImageUrl The original URL of the image.
     * @param int $weight The weight of the image.
     * @param int $height The height of the image.
     * @return string The signed encrypted URL.
     * @throws SharpException If encryption fails or binary signature cannot be generated.
     */
    public function generateEncryptedUrl(string $originalImageUrl, int $weight = 0, int $height = 0)
    {
        $encryptedBinaryUrl = self::encryptData($originalImageUrl, $this->sourceKey);
        $encryptedUrl = HTTPUtil::base64UrlEncode($encryptedBinaryUrl);
        $encryptedPath = "/rs:{$weight}:{$height}/enc/{$encryptedUrl}";

        $binarySignature = hash_hmac('sha256', $this->signatureSalt.$encryptedPath, $this->signatureKey, true);
        if ($binarySignature === false) {
            throw new SharpException('Could not generate binary signature.');
        }
        $signature = HTTPUtil::base64UrlEncode($binarySignature);

        return sprintf("/%s%s", $signature, $encryptedPath);
    }

    /**
     * Encrypts the given data with the specified key.
     * @param string $data The data to be encrypted.
     * @param string $key The encryption key.
     * @return string The encrypted data.
     * @throws SharpException If encryption fails.
     */
    private static function encryptData(string $data, string $key)
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        if ($encrypted === false) {
            throw new SharpException('Encryption failed.');
        }

        return $iv.$encrypted;
    }
}
