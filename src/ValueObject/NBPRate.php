<?php declare(strict_types=1);

namespace NBPClient\ValueObject;

class NBPRate
{
    /** @var string */
    private $currency;

    /** @var string */
    private $tableNo;

    /** @var \DateTime */
    private $tableDate;

    /** @var string */
    private $ratio;

    public function __construct(string $currency, string $tableNo, \DateTime $tableDate, string $ratio)
    {
        $this->currency  = $currency;
        $this->tableNo   = $tableNo;
        $this->tableDate = $tableDate;
        $this->ratio     = $ratio;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getTableNo(): string
    {
        return $this->tableNo;
    }

    public function getTableDate(): \DateTime
    {
        return $this->tableDate;
    }

    public function getRatio(): string
    {
        return $this->ratio;
    }
}
