<?php
namespace RingCentral\Tests\Psr7;

error_reporting(E_ALL);

require __DIR__ . '/../pool/libs/composer/vendor/autoload.php';

class HasToString
{
    public function __toString() {
        return 'foo';
    }
}
