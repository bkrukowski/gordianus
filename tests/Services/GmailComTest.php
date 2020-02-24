<?php

declare(strict_types=1);

namespace bkrukowski\TransparentEmail\Tests\Services;

use bkrukowski\TransparentEmail\Emails\Email;
use bkrukowski\TransparentEmail\Services\GmailCom;
use PHPUnit\Framework\TestCase;

class GmailComTest extends TestCase
{
    /**
     * @dataProvider providerGetPrimaryEmail
     *
     * @param string $inputEmail
     * @param string $outputEmail
     */
    public function testGetPrimaryEmail(string $inputEmail, string $outputEmail)
    {
        $this->assertEquals($outputEmail, (new GmailCom())->getPrimaryEmail(new Email($inputEmail)));
    }

    public function providerGetPrimaryEmail() : array
    {
        return [
            ['foo.bar@gmail.com', 'foobar@gmail.com'],
            ['Foo.Bar@GMAIL.COM', 'foobar@gmail.com'],
            ['foobar+alias@gmail.com', 'foobar@gmail.com'],
            ['fo.ob.ar+alias@gmail.com', 'foobar@gmail.com'],
            ['JaneDoe@googlemail.com', 'janedoe@gmail.com'],
        ];
    }

    /**
     * @dataProvider providerIsSupported
     *
     * @param string $domain
     * @param bool $result
     */
    public function testIsSupported(string $domain, bool $result)
    {
        $this->assertSame($result, (new GmailCom())->isSupported(new Email('Jane.Doe@' . $domain)));
    }

    public function providerIsSupported() : array
    {
        return [
            ['gmail.com', true],
            ['gmail.COM', true],
            ['google.com', false],
            ['test.gmailcom', false],
            ['g.mail.com', false],
            ['googlemail.com', true],
            ['GoogleMail.Com', true],
        ];
    }
}