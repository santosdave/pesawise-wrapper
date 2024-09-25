<?php

namespace Santosdave\PesawiseWrapper\DataTypes;

class PaymentStatus
{
    const SUCCESS = 'SUCCESS';
    const FAILED = 'FAILED';
    const PENDING = 'PENDING';
    const NOT_FOUND = 'NOT_FOUND';
    const INVALID_API_KEYS = 'INVALID_API_KEYS';
    const API_NOT_ENABLED = 'API_NOT_ENABLED';

    /**
     * @var string
     */
    private $status;

    /**
     * @param string $status
     */
    public function __construct(string $status)
    {
        if (!in_array($status, [self::SUCCESS, self::FAILED, self::PENDING, self::NOT_FOUND, self::INVALID_API_KEYS, self::API_NOT_ENABLED])) {
            throw new \InvalidArgumentException("Invalid payment status: $status");
        }
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
