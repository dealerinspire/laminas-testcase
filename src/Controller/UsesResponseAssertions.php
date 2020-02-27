<?php

namespace DiCommonTest\Controller;

use Exception;
use PHPUnit\Framework\Assert;

trait UsesResponseAssertions
{
    /**
     * Assert response status code is what we expect it to be.
     *
     * @param int $expected
     * @return void
     */
    protected function assertResponseStatusCode($expected)
    {
        $actual = $this->getResponseStatusCode();

        $message = sprintf(
            'Looking for response code "%s." Actual status code is "%s" (%s).',
            $expected,
            $actual,
            $this->getResponse()->getReasonPhrase()
        );

        Assert::assertEquals($expected, $actual, $message);
    }

    /**
     * Assert response content type is what we expect it to be.
     *
     * @param string $expected
     * @return void
     */
    protected function assertResponseContentType($expected)
    {
        $this->assertResponseHeaderContains('Content-Type', $expected);
    }

    /**
     * Assert the response body is JSON.
     *
     * @return void
     */
    protected function assertResponseIsJson()
    {
        Assert::assertTrue(
            $this->isResponseJson(),
            'Response is not JSON.'
        );
    }

    /**
     * Assert the response body is JSON.
     *
     * @return void
     */
    protected function assertResponseIsNotJson()
    {
        Assert::assertFalse(
            $this->isResponseJson(),
            'Response is JSON but should not be.'
        );
    }

    /**
     * Asserts the JSON response body has a key (or nested key).
     * If the value is provided, an additional assertion is made that the value of the key matches
     * the expected value.
     *
     * Keys should be in dot notation, for example: vehicle.model_code
     *
     * @param string $jsonPath
     * @param mixed $value
     * @return void
     */
    protected function assertJsonResponseContains(string $jsonPath, $value = 'some_value_that_should_never_be_encountered')
    {
        $json = $this->getJsonResponse();

        $containsPath = true;

        $loc = &$json;
        foreach (explode('.', $jsonPath) as $step) {
            if (!isset($loc[$step]) && ($loc[$step] !== null)) {
                $containsPath = false;
                break;
            }
            $loc = &$loc[$step];
        }

        Assert::assertTrue(
            $containsPath,
            'Response does not contain the provided path: ' . $jsonPath
        );

        if (!$containsPath) {
            return;
        }

        if ($value !== 'some_value_that_should_never_be_encountered') {
            Assert::assertEquals(
                $value,
                $loc
            );
        }
    }

    /**
     * Assert the response matches the given regex pattern
     *
     * @param string $pattern
     * @return void
     */
    protected function assertResponseContentMatches($pattern)
    {
        Assert::assertTrue(
            $this->contentRegexMatch($pattern),
            'Response does not match the regex.'
        );
    }

    /**
     * Assert the response does not match the given regex pattern
     *
     * @param string $pattern
     * @return void
     */
    protected function assertResponseContentNotMatches($pattern)
    {
        Assert::assertFalse(
            $this->contentRegexMatch($pattern),
            'Response matches the regex, but should not.'
        );
    }

    /**
     * Checks a regex pattern against the response body
     *
     * @param string $pattern
     * @return bool
     */
    private function contentRegexMatch($pattern): bool
    {
        return (bool)preg_match($pattern, $this->getResponse()->getContent());
    }

    /**
     * Assert the response body contains the given string
     *
     * @param string $needle
     * @return void
     */
    protected function assertResponseContains($needle)
    {
        Assert::assertTrue(
            $this->contentContainsString($needle),
            sprintf('Response does not contain the provided string: %s', $needle)
        );
    }

    /**
     * Assert the response body does not contain the given string
     *
     * @param string $needle
     * @return void
     */
    protected function assertResponseNotContains($needle)
    {
        Assert::assertFalse(
            $this->contentContainsString($needle),
            sprintf('Response contains the provided string, but should not: %s', $needle)
        );
    }

    /**
     * Checks a that the response body contains a given string
     *
     * @param string $needle
     * @return bool
     */
    private function contentContainsString($needle): bool
    {
        return strpos($this->getResponse()->getContent(), $needle) !== false;
    }

    /**
     * Determines if the response is JSON
     *
     * @return bool
     */
    private function isResponseJson(): bool
    {
        $isJson = true;

        try {
            $this->getJsonResponse();
        } catch (Exception $exception) {
            $isJson = false;
        }

        return $isJson;
    }

    /**
     * Get the response as a JSON object
     *
     * @return array
     */
    protected function getJsonResponse(): array
    {
        $responseContent = $this->getResponse()->getContent();
        $json = json_decode($responseContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Response was not JSON");
        }

        return $json;
    }
}
