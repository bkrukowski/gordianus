<?php

declare(strict_types=1);

namespace bkrukowski\TransparentEmail\Tests;

use bkrukowski\TransparentEmail\Emails\Email;
use bkrukowski\TransparentEmail\Emails\EmailInterface;
use bkrukowski\TransparentEmail\ServiceCollector;
use bkrukowski\TransparentEmail\ServiceCollectorInterface;
use bkrukowski\TransparentEmail\Services\TlenPl;
use bkrukowski\TransparentEmail\TransparentEmail;
use bkrukowski\TransparentEmail\TransparentEmailFactory;
use PHPUnit\Framework\TestCase;

class TransparentEmailTest extends TestCase
{
    /**
     * @dataProvider providerGetPrimaryEmail
     *
     * @param TransparentEmail $transparentEmail
     * @param string $email
     * @param string $expectedEmail
     * @param bool $caseSensitive
     */
    public function testGetPrimaryEmail(
        TransparentEmail $transparentEmail,
        string $email,
        string $expectedEmail,
        bool $caseSensitive = false
    ) {
        $this->assertEquals($expectedEmail, $transparentEmail->getPrimaryEmail(new Email($email, $caseSensitive)));
    }

    public function providerGetPrimaryEmail() : array
    {
        $emptyServiceCollector = $this->createServiceCollector();
        $tlenServiceCollector = $this->createServiceCollector([TlenPl::class]);

        return [
            [
                new TransparentEmail($tlenServiceCollector),
                'john.doe+alias@gmail.com',
                'john.doe+alias@gmail.com'
            ],
            [(new TransparentEmailFactory())->createDefault(), 'john.doe+alias@gmail.com', 'johndoe@gmail.com'],
            [new TransparentEmail($emptyServiceCollector), 'John.Doe@example.com', 'john.doe@example.com'],
            [new TransparentEmail($emptyServiceCollector), 'John.Doe@example.com', 'John.Doe@example.com', true],
            [(new TransparentEmailFactory())->createDefault(), 'John.Doe@gmail.com', 'johndoe@gmail.com', true],
            [(new TransparentEmailFactory())->createDefault(), 'Jane.Doe+receipts@hotmail.com', 'jane.doe@hotmail.com'],
            [(new TransparentEmailFactory())->createDefault(), 'Jane.Doe-receipts@yahoo.com', 'jane.doe@yahoo.com'],
        ];
    }

    /**
     * @dataProvider providerDefault
     *
     * @param EmailInterface $inputEmail
     * @param string $expectedEmail
     */
    public function testDefault(EmailInterface $inputEmail, string $expectedEmail)
    {
        $outputEmail = ((new TransparentEmailFactory())->createDefault())->getPrimaryEmail($inputEmail);
        $this->assertEquals($expectedEmail, $outputEmail);
    }

    public function providerDefault() : array
    {
        return [
            [new Email('John.Doe+spam@gmail.com', true), 'johndoe@gmail.com'],
            [new Email('Jane.Doe+spam@outlook.com', true), 'jane.doe@outlook.com'],
            [new Email('John.Doe@tlen.pl', true), 'john.doe@o2.pl'],
            [new Email('ALIAS@janedoe.33mail.com', true), 'janedoe@janedoe.33mail.com'],
            [new Email('John.Doe-facebook@yahoo.com', true), 'john.doe@yahoo.com'],
        ];
    }

    private function createServiceCollector(array $classes = []) : ServiceCollectorInterface
    {
        $collector = new ServiceCollector();
        foreach ($classes as $class) {
            $collector->addService(new $class());
        }

        return $collector;
    }
}