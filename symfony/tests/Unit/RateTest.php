<?php

namespace Tests\Unit;

use App\Domain\Entity\Rate;
use App\Domain\ValueObject\Code;
use App\Domain\ValueObject\DateImmutable;
use App\Domain\ValueObject\Nominal;
use App\Domain\ValueObject\Value;
use Codeception\Test\Unit;
use Tests\Support\UnitTester;

class RateTest extends Unit
{
    protected UnitTester $tester;

    public function testRateCalculation(): void
    {
        $rateUSD = new Rate(
            Code::createRUR(),
            Code::create('USD'),
            new DateImmutable(),
            new DateImmutable(),
            Value::create(50),
            Nominal::create(1)
        );

        $rateEUR = new Rate(
            Code::createRUR(),
            Code::create('EUR'),
            new DateImmutable(),
            new DateImmutable(),
            Value::create(100),
            Nominal::create(1)
        );

        $crossRateEURBase = $rateUSD->calculateCrossRate($rateEUR);
        $crossRateUSDBase = $rateEUR->calculateCrossRate($rateUSD);

        $this->assertEquals(2.0, $crossRateEURBase->value->value);
        $this->assertEquals(0.5, $crossRateUSDBase->value->value);
    }

    public function test

    public function testRateDiff(): void
    {
        $today = new DateImmutable();
        $yesterday = (new DateImmutable())->sub(new \DateInterval('P1D'));

        $rateToday = new Rate(
            Code::createRUR(),
            Code::create('USD'),
            $today,
            $today,
            Value::create(50),
            Nominal::create(1)
        );

        $rateYesterday = new Rate(
            Code::createRUR(),
            Code::create('USD'),
            $yesterday,
            $yesterday,
            Value::create(45),
            Nominal::create(1)
        );

        $rateDiff = $rateToday->calculateRateDiff($rateYesterday);
        $rateDiffInversed = $rateYesterday->calculateRateDiff($rateToday);

        $this->assertEquals(5.0, $rateDiff);
        $this->assertEquals(-5.0, $rateDiffInversed);
    }
}
