<?php
namespace carry0987\Sharp;

use carry0987\Hash\Hash;
use carry0987\Sharp\Exceptions\SharpException;

class Sharp
{
    protected Hash $hash;
    protected $imageOption = [
        'width' => 0,
        'height' => 0,
        'suffix' => null
    ];
    protected $format = null;

    const ENCRYPT_ALGORITHM = 'sha256';
    const CIPHER = 'aes-256-gcm';

    /**
     * Sharp constructor.
     * @param string $signatureKey Hexadecimal signature key.
     * @param string $signatureSalt Hexadecimal signature salt.
     * @param string $sourceKey Hexadecimal source key.
     */
    public function __construct(string $signatureKey, string $signatureSalt, string $sourceKey)
    {
        $this->hash = new Hash($signatureKey, $signatureSalt);
        $this->hash->setSourceKey($sourceKey);
        $this->hash->setEncryptAlgorithm(self::ENCRYPT_ALGORITHM);
        $this->hash->setCipher(self::CIPHER);
    }

    /**
     * Set the width of the image.
     * @param int $width 
     * @return $this 
     */
    public function setWidth(int $width)
    {
        $this->imageOption['width'] = $width;

        return $this;
    }

    /**
     * Set the height of the image.
     * @param int $height 
     * @return $this 
     */
    public function setHeight(int $height)
    {
        $this->imageOption['height'] = $height;

        return $this;
    }

    /**
     * Set the suffix of the image.
     * @param string $suffix The suffix of the image.
     * @return $this
     */
    public function setSuffix(string $suffix = null)
    {
        $this->imageOption['suffix'] = $suffix;

        return $this;
    }

    /**
     * Set the image format
     * @param string $format The format of the image.
     * @return $this
     */
    public function setFormat(string $format = null)
    {
        if ($format !== null) {
            $format = strtolower($format);
        }
        $this->format = $format;

        return $this;
    }

    /**
     * Generate an encrypted URL to be used with Sharp-API.
     * @param string $originalImageUrl The original URL of the image.
     * @return string The signed encrypted URL.
     * @throws SharpException If encryption fails or binary signature cannot be generated.
     */
    public function generateEncryptedUrl(string $originalImageUrl)
    {
        $this->hash->setPathFormatter(function ($path) {
            $encryptedPath = '/rs:'.implode(':', $this->imageOption);
            $encryptedPath = rtrim($encryptedPath, ':');
            $encryptedPath .= '/enc/'.$path.'/'.$this->format;
            $encryptedPath = rtrim($encryptedPath, '/');
            return $encryptedPath;
        });

        return $this->hash->generateEncryptedUrl($originalImageUrl);
    }
}
