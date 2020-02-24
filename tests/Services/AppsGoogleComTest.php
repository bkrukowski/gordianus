<?php

declare(strict_types=1);

namespace bkrukowski\TransparentEmail\Tests\Services;

use bkrukowski\TransparentEmail\Emails\Email;
use bkrukowski\TransparentEmail\Services\AppsGoogleCom;
use PHPUnit\Framework\TestCase;

class AppsGoogleComTest extends TestCase
{
    /**
     * @dataProvider providerIsSupported
     *
     * @param string $domain
     * @param bool $result
     */
    public function testIsSupported(string $domain, bool $result)
    {
        $this->assertSame($result, (new AppsGoogleCom())->isSupported(new Email('Jane.Doe@' . $domain, true)));
    }

    public function providerIsSupported() : array
    {
        return [
            ['example.com', false],
            ['EXAMPLE.COM', false],
            ['krukowski.me', true],
            ['KRUKOWSKI.ME', true],
        ];
    }
}