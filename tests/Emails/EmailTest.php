<?php

declare(strict_types=1);

namespace bkrukowski\TransparentEmail\Tests\Emails;

use bkrukowski\TransparentEmail\Emails\Email;
use bkrukowski\TransparentEmail\Emails\InvalidEmailException;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    /**
     * @dataProvider providerConstructor
     *
     * @param string $email
     * @param bool $expectedException
     * @param string $localPart
     * @param string $domain
     * @param bool $caseSensitive
     */
    public function testConstructor(
        string $email,
        bool $expectedException,
        string $localPart = '',
        string $domain = '',
        bool $caseSensitive = false
    ) {
        if ($expectedException) {
            $this->expectException(InvalidEmailException::class);
        }
        $object = new Email($email, $caseSensitive);
        $this->assertSame($localPart, $object->getLocalPart());
        $this->assertSame($domain, $object->getDomain());
    }

    public function providerConstructor() : array
    {
        return [
            ['john doe@example.com', true],
            ['.johndoe@example.com', true],
            ['Jane.Doe@Example.COM', false, 'Jane.Doe', 'example.com', true],
            ['Jane.Doe@Example.COM', false, 'jane.doe', 'example.com'],
        ];
    }
}