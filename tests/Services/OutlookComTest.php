<?php

declare(strict_types=1);

namespace bkrukowski\TransparentEmail\Tests\Services;

use bkrukowski\TransparentEmail\Emails\Email;
use bkrukowski\TransparentEmail\Services\OutlookCom;
use PHPUnit\Framework\TestCase;

class OutlookComTest extends TestCase
{
    /**
     * @dataProvider providerIsSupported
     *
     * @param string $domain
     * @param bool $result
     */
    public function testIsSupported(string $domain, bool $result)
    {
        $this->assertSame($result, (new OutlookCom())->isSupported(new Email('Jane.Doe@' . $domain, true)));
    }

    public function providerIsSupported() : array
    {
        return [
            ['outlook.com', true],
            ['Outlook.Com', true],
            ['hotmail.com', true],
            ['HotMail.COM', true],
            ['gmail.com', false],
            ['tlen.pl', false],
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
        $this->assertEquals($expectedEmail, (new OutlookCom())->getPrimaryEmail(new Email($inputEmail, true)));
    }

    public function providerGetPrimaryEmail() : array
    {
        return [
            ['janedoe@outlook.com', 'janedoe@outlook.com'],
            ['jane.doe@outlook.com', 'jane.doe@outlook.com'],
            ['Jane.Doe@Outlook.Com', 'jane.doe@outlook.com'],
            ['Jane.Doe+alias@OUTLOOK.COM', 'jane.doe@outlook.com'],
            ['Jane.Doe+Hotmail@hotmail.com', 'jane.doe@hotmail.com'],
        ];
    }
}