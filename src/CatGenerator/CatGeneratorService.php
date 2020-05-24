<?php


namespace App\CatGenerator;


use M6Web\Tornado\Adapter\Guzzle\CurlMultiClientWrapper;
use M6Web\Tornado\EventLoop;
use M6Web\Tornado\HttpClient;
use M6Web\Tornado\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpFoundation\Request;

class CatGeneratorService
{
    /** @var HttpClient */
    protected $httpClient;

    /** @var EventLoop */
    protected $eventLoop;

    public function __construct()
    {
        $this->eventLoop = new \M6Web\Tornado\Adapter\Amp\EventLoop();
        $this->httpClient = new \M6Web\Tornado\Adapter\Guzzle\HttpClient($this->eventLoop, new CurlMultiClientWrapper());
    }

    public function fetchCatUrls($count)
    {
        $urls = [];
        for ($i = 0; $i < $count; $i++) {
            $urls[] = $this->getCatUrl();
        }

        return $urls;
    }

    public function getCatUrl()
    {
        $result = file_get_contents('https://api.thecatapi.com/v1/images/search');
        $result = json_decode($result, true);

        return $result[0]['url'];
    }

    public function fetchCatUrlsAsync($count)
    {
        $urlPromises = [];
        for ($i = 0; $i < $count; $i++) {
            $urlPromises[] = $this->getCatUrlAsync();
        }

        return $this->eventLoop->wait(
            $this->eventLoop->promiseAll(...$urlPromises)
        );
    }

    private function getCatUrlAsync(): Promise
    {
        $request = new \GuzzleHttp\Psr7\Request(
            'GET',
            'http://api.thecatapi.com/v1/images/search',
            ['accept' => 'application/json']
        );

        return $this->eventLoop->async($this->getJsonResponseAsync($request));
    }

    private function getJsonResponseAsync(RequestInterface $request): \Generator
    {
        /** @var ResponseInterface $response */
        $response = yield $this->httpClient->sendRequest($request);
        $response = json_decode((string) $response->getBody(), true);

        return $response[0]['url'];
    }
}