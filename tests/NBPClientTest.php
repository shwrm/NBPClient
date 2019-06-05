<?php declare(strict_types=1);

namespace NBPClient\Tests;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use NBPClient\NBPClient;
use NBPClient\Test\WebTestCase;
use NBPClient\ValueObject\NBPRate;

class NBPClientTest extends WebTestCase
{
    public function testFetchRateWithOkResponse()
    {
        $guzzle = $this->getGuzzle([
                new GuzzleResponse(
                    200,
                    ['Content-Type' => 'application/json'],
                    $this->getFixtures('nbp_single_rate.json')
                ),
            ]
        );

        $client = new NBPClient($guzzle);
        $rate   = $client->fetchRate('EUR', new \DateTime());

        $this->assertInstanceOf(NBPRate::class, $rate);
        $this->assertEquals('EUR', $rate->getCurrency());
        $this->assertEquals('021/A/NBP/2019', $rate->getTableNo());
        $this->assertEquals(new \DateTime('2019-01-30 00:00:00.000000'), $rate->getTableDate());
        $this->assertEquals('4.2952', $rate->getRatio());
    }

    public function testFetchRateWithNotFoundResponse()
    {
        $guzzle = $this->getGuzzle([
                new GuzzleResponse(
                    404,
                    ['Content-Type' => 'application/json'],
                    $this->getFixtures('nbp_single_rate.json')
                ),
            ]
        );

        $client = new NBPClient($guzzle);
        $rate   = $client->fetchRate('', new \DateTime());

        $this->assertNull($rate);
    }

    public function testFetchRateWithBadRequestResponse()
    {
        $guzzle = $this->getGuzzle([
                new GuzzleResponse(
                    400,
                    ['Content-Type' => 'application/json'],
                    $this->getFixtures('nbp_single_rate.json')
                ),
            ]
        );

        $this->expectException(ClientException::class);

        $client = new NBPClient($guzzle);
        $client->fetchRate('', new \DateTime());
    }
}
