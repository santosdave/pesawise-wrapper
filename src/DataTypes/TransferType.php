<?php

namespace Santosdave\PesawiseWrapper\DataTypes;

class TransferType
{
    public const BANK = 'BANK';
    public const B2B = 'B2B';
    public const B2C = 'B2C';
    public const BUSINESS_PAY_BILL = 'BUSINESS_PAY_BILL';
    public const BUSINESS_BUY_GOODS = 'BUSINESS_BUY_GOODS';
    public const COUNTER_PARTY_TRANSFER = 'COUNTER_PARTY_TRANSFER';

    private string $type;

    public function __construct(string $type)
    {
        if (!in_array($type, self::getValidTypes())) {
            throw new \InvalidArgumentException("Invalid transfer type: $type");
        }
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public static function getValidTypes(): array
    {
        return [
            self::BANK,
            self::B2B,
            self::B2C,
            self::BUSINESS_PAY_BILL,
            self::BUSINESS_BUY_GOODS,
            self::COUNTER_PARTY_TRANSFER,
        ];
    }
}