<?php declare(strict_types=1);

namespace NBPClient\Test;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use PHPUnit\Framework\TestCase;

class WebTestCase extends TestCase
{
    /** @var array */
    private $localCache = [];

    public function getFixtures(string $name): string
    {
        if (isset($this->localCache[$name])) {
            return $this->localCache[$name];
        }

        $path = sprintf('%s/../../tests/Fixtures/%s', __DIR__, $name);

        if (false === file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('File "%s" not exists.', $path));
        }

        return $this->localCache[$name] = file_get_contents($path);
    }

    public function getGuzzle(array $responses): ClientInterface
    {
        return new GuzzleClient(
            [
                'handler' => HandlerStack::create(new MockHandler($responses)),
            ]
        );
    }
}
