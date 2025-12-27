<?php
declare(strict_types=1);

namespace TestApp\TestCase;

use PHPUnit\Framework\TestCase;
use SimpleVC\TestCase\ResponseAssertionsTrait;
use Symfony\Component\HttpFoundation\Response;

/**
 * Fake test case class that uses the ResponseAssertionsTrait.
 *
 * This class is only there to facilitate testing against `ResponseAssertionsTrait`, as it exposes its methods publicly.
 */
class TestCaseWithResponseAssertionsTrait extends TestCase
{
    use ResponseAssertionsTrait;

    public function setResponse(?Response $response): self
    {
        $this->response = $response;

        return $this;
    }
}
