<?php

declare(strict_types=1);

namespace bkrukowski\TransparentEmail\Tests\Services;

use bkrukowski\TransparentEmail\Emails\Email;
use bkrukowski\TransparentEmail\Services\MultiDomain;
use PHPUnit\Framework\TestCase;

class MultiDomainTest extends TestCase
{
    /**
     * @dataProvider providerIsSupported
     *
     * @param MultiDomain $mock
     * @param string $domain
     * @param bool $expected
     */
    public function testIsSupported(MultiDomain $mock, string $domain, bool $expected)
    {
        $this->assertSame($expected, $mock->isSupported(new Email('Jane.Doe@' . $domain, true)));
    }

    public function providerIsSupported() : array
    {
        return [
            [$this->getMultiDomainMock('foo.bar', ['foo.bar']), 'gmail.com', false],
            [$this->getMultiDomainMock('foo.bar', ['foo.bar', 'gmail.com']), 'gmail.com', true],
            [$this->getMultiDomainMock('foo.bar', ['foo.bar', 'gmail.com']), 'GMAIL.COM', true],
            [$this->getMultiDomainMock('foo.bar', ['foo.bar', 'gmail.com'], true), 'GMAIL.COM', true],
        ];
    }

    /**
     * @dataProvider providerGetPrimaryDomain
     *
     * @param MultiDomain $mock
     * @param string $email
     * @param string $expectedEmail
     */
    public function testGetPrimaryDomain(MultiDomain $mock, string $email, string $expectedEmail) {
        $this->assertEquals($expectedEmail, $mock->getPrimaryEmail(new Email($email, true)));
    }

    public function providerGetPrimaryDomain() : array
    {
        return [
            [$this->getMultiDomainMock('foo.bar', ['foo.bar', 'foo.bar2']), 'name@foo.bar', 'name@foo.bar'],
            [$this->getMultiDomainMock('foo.bar', ['foo.bar', 'foo.bar2'], true), 'Name@foo.bar', 'Name@foo.bar'],
            [$this->getMultiDomainMock('foo.bar', ['foo.bar', 'foo.bar2'], true), 'Name@FOO.bar', 'Name@foo.bar'],
            [$this->getMultiDomainMock('foo.bar', ['foo.bar', 'foo.bar2']), 'name@foo.bar2', 'name@foo.bar'],
        ];
    }

    private function getMultiDomainMock(
        string $primaryDomain,
        array $supportedDomains,
        bool $caseSensitive = false) : MultiDomain
    {
        return new class ($primaryDomain, $supportedDomains, $caseSensitive) extends MultiDomain {

            private $primaryDomain;

            private $supportedDomains;

            private $caseSensitive;

            public function __construct(string $primaryDomain, array $supportedDomains, bool $caseSensitive)
            {
                $this->primaryDomain = $primaryDomain;
                $this->supportedDomains = $supportedDomains;
                $this->caseSensitive = $caseSensitive;
            }

            protected function getDomainList() : array
            {
                return $this->supportedDomains;
            }

            protected function getPrimaryDomain() : string
            {
                return $this->primaryDomain;
            }

            protected function isCaseSensitive() : bool
            {
                return $this->caseSensitive;
            }
        };
    }
}