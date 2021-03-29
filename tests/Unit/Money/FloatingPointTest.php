<?php

namespace Tests\Unit\Money;

use PHPUnit\Framework\TestCase;

class FloatingPointTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testMoneyFormatterReturnExectedNumberFormat()
    {
        $moenyTable = [
            "203.532",
            "2000",
            "25325.639",
            "1",
            "0",
            "2000000000.66666",
            "0.9999999",
            "32",
            "19999.997",
            "1a"
        ];
        $expectedFormatedTables = [
            203.53,
            2000.00,
            25325.64,
            1.00,
            0.00,
            2000000000.67,
            1.0,
            32.00,
            20000.00,
            1.00
        ];
        foreach ($moenyTable as $key => $value) {
            $formated = moneyFormatter($value);
            $this->assertIsFloat($formated);
            $this->assertEquals($formated,$expectedFormatedTables[$key]);
        } 
    }
}
