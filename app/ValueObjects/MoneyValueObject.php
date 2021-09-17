<?php

namespace App\ValueObjects;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use NumberFormatter;

class MoneyValueObject implements CastsAttributes
{
    private ?string $currency;
    private ?float $amount;
    private int $points;

    public function __construct(?float $amount = 0, ?string $currency = "USD", int $points = 2)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->points = $points;
    }


    public function isEqual(?float $amount): bool
    {
        if (!$amount) return false;
        return $this->getAmount() === $this->getAmount($amount);
    }

    /**
     * @param null $amount
     * @return float
     */
    public function getAmount($amount = null): float
    {
        return round($amount ?: $this->amount, $this->getPoints());
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * @param int $points
     */
    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    public function getFormattedMoney(): string
    {
        $fmt = numfmt_create('en_US', NumberFormatter::CURRENCY);
        return numfmt_format_currency($fmt, $this->getAmount(), $this->getCurrency());
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function isValid(): bool
    {
        return $this->amount != null;
    }

    public function get($model, string $key, $value, array $attributes): float
    {
        return $this->getAmount($value);
    }

    public function set($model, string $key, $value, array $attributes): float
    {
        $this->setAmount((float)$value);
        return $this->getAmount();
    }
}

