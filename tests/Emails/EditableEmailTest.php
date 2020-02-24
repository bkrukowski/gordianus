<?php

namespace bkrukowski\TransparentEmail\Tests\Emails;

use bkrukowski\TransparentEmail\Emails\EditableEmail;
use bkrukowski\TransparentEmail\Emails\Email;
use bkrukowski\TransparentEmail\Emails\EmailInterface;
use PHPUnit\Framework\TestCase;

class EditableEmailTest extends TestCase
{
    /**
     * @dataProvider providerRemoveFromLocalPart
     *
     * @param EmailInterface $email
     * @param string $toRemove
     */
    public function testRemoveFromLocalPart(EmailInterface $email, string $toRemove)
    {
        $editable = new EditableEmail($email);
        $new = $editable->removeFromLocalPart($toRemove);
        $this->assertNotSame($editable, $new);
        $this->assertNotContains((string) $new, $toRemove);
        $this->assertSame($email->getDomain(), $new->getDomain());
    }

    public function providerRemoveFromLocalPart() : array
    {
        return [
            [new Email('jane.doe.1990@gmail.com'), '.'],
            [new Email('janedoe1990@gmail.com'), '.'],
        ];
    }

    /**
     * @dataProvider providerRemoveSuffixAlias
     *
     * @param EmailInterface $email
     * @param string $delimiter
     * @param string $expected
     */
    public function testRemoveSuffixAlias(EmailInterface $email, string $delimiter, string $expected)
    {
        $editable = new EditableEmail($email);
        $new = $editable->removeSuffixAlias($delimiter);
        $this->assertNotSame($editable, $new);
        $this->assertEquals($expected, $new);
        $this->assertSame($email->getDomain(), $editable->getDomain());
    }

    public function providerRemoveSuffixAlias() : array
    {
        return [
            [new Email('John.Doe+alias@gmail.com', true), '+', 'John.Doe@gmail.com'],
            [new Email('JohnDoe-alias@gmail.com'), '-', 'johndoe@gmail.com'],
            [new Email('JohnDoe@gmail.com'), '-', 'johndoe@gmail.com'],
        ];
    }

    /**
     * @dataProvider providerSetDomain
     *
     * @param EmailInterface $email
     * @param string $domain
     */
    public function testSetDomain(EmailInterface $email, string $domain)
    {
        $editable = new EditableEmail($email);
        $new = $editable->setDomain($domain);
        $this->assertNotSame($editable, $new);
        $this->assertSame($domain, $new->getDomain());
        $this->assertSame($email->getLocalPart(), $new->getLocalPart());
    }

    public function providerSetDomain() : array
    {
        return [
            [new Email('jane.doe@foo.bar'), 'gmail.com'],
            [new Email('jane.doe@foo.bar'), 'foo.bar'],
            [new Email('jane.doe@gmail.com'), 'foo.bar'],
        ];
    }

    /**
     * @dataProvider providerSetLocalPart
     *
     * @param EmailInterface $email
     * @param string $localPart
     */
    public function testLocalPart(EmailInterface $email, string $localPart)
    {
        $editable = new EditableEmail($email);
        $new = $editable->setLocalPart($localPart);
        $this->assertNotSame($editable, $new);
        $this->assertSame($localPart, $new->getLocalPart());
        $this->assertSame($email->getDomain(), $new->getDomain());
    }

    public function providerSetLocalPart() : array
    {
        return [
            [new Email('jane.doe@foo.bar'), 'jane'],
            [new Email('jane.doe@foo.bar'), 'john'],
            [new Email('jane.doe@gmail.com'), 'jane.doe'],
        ];
    }

    /**
     * @dataProvider providerLowerCaseLocalPartIf
     *
     * @param EmailInterface $email
     * @param bool $condition
     * @param bool $isLowerCase
     */
    public function testLowerCaseLocalPartIf(EmailInterface $email, bool $condition, bool $isLowerCase)
    {
        $editable = new EditableEmail($email);
        $new = $editable->lowerCaseLocalPartIf($condition);
        $this->assertSame(!$condition, $editable === $new);
        $this->assertSame($isLowerCase, strtolower($editable->getLocalPart()) === $new->getLocalPart());
        $this->assertSame($email->getDomain(), $new->getDomain());
    }

    public function providerLowerCaseLocalPartIf() : array
    {
        return [
            [new Email('john.doe@example.com', true), false, true],
            [new Email('John.doe@example.com', true), false, false],
            [new Email('John.doe@example.com', true), true, true],
        ];
    }
}