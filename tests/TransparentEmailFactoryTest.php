<?php

declare(strict_types=1);

namespace bkrukowski\TransparentEmail\Tests;

use bkrukowski\TransparentEmail\Emails\Email;
use bkrukowski\TransparentEmail\Emails\EmailInterface;
use bkrukowski\TransparentEmail\TransparentEmailFactory;
use PHPUnit\Framework\TestCase;

class TransparentEmailFactoryTest extends TestCase
{
    /**
     * @dataProvider providerExpectedEmail
     *
     * @param EmailInterface $inputEmail
     * @param string $expectedEmail
     */
    public function testExpectedEmail(EmailInterface $inputEmail, string $expectedEmail)
    {
        $outputEmail = (new TransparentEmailFactory())->createDefault()->getPrimaryEmail($inputEmail);
        $this->assertSame($expectedEmail, (string) $outputEmail);
    }

    public function providerExpectedEmail() : array
    {
        return [
            [new Email('John.Doe+spam@gmail.com', true), 'johndoe@gmail.com'],
            [new Email('Jane.Doe+spam@outlook.com', true), 'jane.doe@outlook.com'],
            [new Email('John.Doe@tlen.pl', true), 'john.doe@o2.pl'],
            [new Email('ALIAS@janedoe.33mail.com', true), 'janedoe@janedoe.33mail.com'],
            [new Email('John.Doe-facebook@yahoo.com', true), 'john.doe@yahoo.com'],
        ];
    }
}