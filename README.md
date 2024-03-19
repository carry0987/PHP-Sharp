# PHP-Sharp
[![Packgist](https://img.shields.io/packagist/v/carry0987/sharp.svg?style=flat-square)](https://packagist.org/packages/carry0987/sharp)  
A PHP script for generating signed and encrypted URLs for image processing with **[Sharp-API](https://github.com/carry0987/Sharp-API/)**, using AES-256-GCM and HMAC-SHA256.

## Installation
Use Composer to install PHP-Sharp Image Encryptor in your project:

```shell
composer require carry0987/sharp
```

## Configuration
To use PHP-Sharp, you need to have a signature key, a signature salt, and a source key for encryption and hashing. These should be provided as hexadecimal strings.

## Usage
Below is an example demonstrating how to encrypt an image URL using PHP-Sharp Image Encryptor:

```php
require_once 'vendor/autoload.php';

use carry0987\Sharp\Sharp;

// Initialize the keys and salt. Replace these values with your actual keys and salt.
$signatureKey = 'your_hex_signature_key';
$signatureSalt = 'your_hex_signature_salt';
$sourceKey = 'your_hex_source_key';

// Create a new instance of PHP-Sharp Image Encryptor.
$imageEncryptor = new Sharp($signatureKey, $signatureSalt, $sourceKey);

// The URL of the image you want to encrypt.
$originalImageUrl = 'https://example.com/image.jpg';

try {
    // Generate the encrypted and signed URL.
    $signedUrl = $imageEncryptor->generateEncryptedUrl($originalImageUrl);
    echo 'Encrypted and signed URL: ' . $signedUrl;
} catch (\carry0987\Sharp\Exceptions\SharpException $e) {
    // Handle any exceptions during the encryption process.
    echo "Error occurred: " . $e->getMessage();
}
```

## Contributing
Contributions to PHP-Sharp Image Encryptor are welcome! Feel free to submit pull requests to improve the codebase.

## License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
