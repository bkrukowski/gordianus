<?php

declare(strict_types=1);

namespace bkrukowski\TransparentEmail\Tests\Services;

use bkrukowski\TransparentEmail\Emails\Email;
use bkrukowski\TransparentEmail\Services\Www33MailCom;
use PHPUnit\Framework\TestCase;

class Www33MailComTest extends TestCase
{
    /**
     * @dataProvider providerIsSupported
     *
     * @param string $domain
     * @param bool $isSupported
     */
    public function testIsSupported(string $domain, bool $isSupported)
    {
        $this->assertSame($isSupported, (new Www33MailCom())->isSupported(new Email('Jane.Doe@' . $domain, true)));
    }

    public function providerIsSupported() : array
    {
        return [
            ['foo.33mail.com', true],
            ['bar.33mail.com', true],
            ['bar.33Mail.Com', true],
            ['foo.34mail.com', false],
            ['foo33mail.com', false],
        ];
    }

    /**
     * @dataProvider providerGetPrimaryEmail
     *
     * @param string $inputEmail
     * @param string $expectedEmail
     */
    public function testGetPrimaryEmail(string $inputEmail, string $expectedEmail)
    {
        $this->assertEquals($expectedEmail, (new Www33MailCom())->getPrimaryEmail(new Email($inputEmail, true)));
    }

    public function providerGetPrimaryEmail() : array
    {
        return [
            ['qwerty@name.33mail.com', 'name@name.33mail.com'],
            ['lorem@ipsum.33mail.com', 'ipsum@ipsum.33mail.com'],
            ['lorem@Ipsum.33mail.com', 'ipsum@ipsum.33mail.com'],
        ];
    }
}