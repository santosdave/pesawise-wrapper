<?php

namespace Santosdave\PesawiseWrapper\DataTypes;

class TransferType
{
    const BANK = 'BANK';
    const B2B = 'B2B';
    const B2C = 'B2C';
    const BUSINESS_PAY_BILL = 'BUSINESS_PAY_BILL';
    const BUSINESS_BUY_GOODS = 'BUSINESS_BUY_GOODS';
    const COUNTER_PARTY_TRANSFER = 'COUNTER_PARTY_TRANSFER';

    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        if (!in_array($type, [self::BANK, self::B2B, self::B2C, self::BUSINESS_PAY_BILL, self::BUSINESS_BUY_GOODS, self::COUNTER_PARTY_TRANSFER])) {
            throw new \InvalidArgumentException("Invalid transfer type: $type");
        }
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
