<?php

declare(strict_types=1);

namespace bkrukowski\TransparentEmail\Tests;

use bkrukowski\TransparentEmail\ServiceCollector;
use bkrukowski\TransparentEmail\Services\ServiceInterface;
use PHPUnit\Framework\TestCase;

class ServiceCollectorTest extends TestCase
{
    public function testAddService()
    {
        $service = $this->createMock(ServiceInterface::class);
        $collector = new ServiceCollector();
        $collector->addService($service);

        $contains = false;
        foreach ($collector->getIterator() as $currentService) {
            if ($currentService === $service) {
                $contains = true;
                break;
            }
        }

        $this->assertTrue($contains);
    }
}