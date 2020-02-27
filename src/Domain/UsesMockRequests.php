<?php

namespace DiCommonTest\Domain;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response as HttpResponse;

trait UsesMockRequests
{
    /**
     * @param array $responses
     * @return GuzzleClient
     */
    protected function mockHttpClient($responses = [])
    {
        /*
        Examples of what can be in the $responses array
        $responses = [
            new HttpResponse(200, ['X-Foo' => 'Bar']),
            new HttpResponse(202, ['Content-Length' => 0]),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ];
        */

        if (empty($responses)) {
            $responses = [
                new HttpResponse(200, ['X-Foo' => 'Bar']),
            ];
        }

        $mockHandler = new MockHandler($responses);

        return new GuzzleClient([
            'handler' => HandlerStack::create($mockHandler),
        ]);
    }
}
