<?php

namespace bkrukowski\Gordianus\Tests\Services;

use bkrukowski\Gordianus\Services\MultiDomain;

class MultiDomainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerIsDomainSupported
     *
     * @param MultiDomain $mock
     * @param string $domain
     * @param bool $expected
     */
    public function testIsDomainSupported(MultiDomain $mock, string $domain, bool $expected)
    {
        $this->assertSame($expected, $mock->isDomainSupported($domain));
    }

    public function providerIsDomainSupported()
    {
        return [
            [$this->getMultiDomainMock('foo.bar', ['foo.bar']), 'gmail.com', false],
            [$this->getMultiDomainMock('foo.bar', ['foo.bar', 'gmail.com']), 'gmail.com', true],
        ];
    }

    /**
     * @dataProvider providerGetPrimaryDomain
     *
     * @param MultiDomain $mock
     * @param string $email
     * @param string $expectedEmail
     */
    public function testGetPrimaryDomain(MultiDomain $mock, string $email, string $expectedEmail)
    {
        $this->assertSame($expectedEmail, $mock->getPrimaryEmail($email));
    }

    public function providerGetPrimaryDomain()
    {
        return [
            [$this->getMultiDomainMock('foo.bar', ['foo.bar', 'foo.bar2']), 'name@foo.bar', 'name@foo.bar'],
            [$this->getMultiDomainMock('foo.bar', ['foo.bar', 'foo.bar2']), 'name@foo.bar2', 'name@foo.bar'],
        ];
    }

    private function getMultiDomainMock(string $primaryDomain, array $supportedDomains) : MultiDomain
    {
        return new class ($primaryDomain, $supportedDomains) extends MultiDomain {
            private $primaryDomain;

            private $supportedDomains;

            public function __construct(string $primaryDomain, array $supportedDomains)
            {
                $this->primaryDomain = $primaryDomain;
                $this->supportedDomains = $supportedDomains;
            }

            protected function getDomainList() : array
            {
                return $this->supportedDomains;
            }

            protected function getPrimaryDomain() : string
            {
                return $this->primaryDomain;
            }
        };
    }
}