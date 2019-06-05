<?php declare(strict_types=1);

namespace NBPClient;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use NBPClient\ValueObject\NBPRate;

class NBPClient
{
    /** @var ClientInterface */
    private $guzzle;

    public function __construct(ClientInterface $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    public function fetchRate(string $currency, \DateTime $date): ?NBPRate
    {
        try {
            $response = $this->guzzle->request(
                'GET',
                sprintf('api/exchangerates/rates/a/%s/%s/', $currency, $date->format('Y-m-d'))
            );
        } catch (ClientException $exception) {
            if (404 === $exception->getCode()) {
                return null;
            }

            throw $exception;
        }

        $rate = json_decode((string)$response->getBody(), true);

        return new NBPRate(
            $rate['code'],
            $rate['rates'][0]['no'],
            new \DateTime($rate['rates'][0]['effectiveDate']),
            (string)$rate['rates'][0]['mid']
        );
    }
}
