<?php
declare(strict_types=1);

namespace SimpleVC\Test\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use SimpleVC\Application;
use SimpleVC\Error\ErrorRenderer;
use SimpleVC\TestCase\ResponseAssertionsTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollection;

#[CoversClass(Application::class)]
class ApplicationTest extends TestCase
{
    use ResponseAssertionsTrait;

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        putenv('DEBUG');
    }

    /**
     * @link \SimpleVC\Application::__construct()
     */
    #[Test]
    public function testLoadsRoutesFromExplicitFilePath(): void
    {
        $app = new Application(CONFIG . '/routes.php');

        $this->_response = $app->run(Request::create('/ok'));
        $this->assertResponseIsSuccessful();
    }

    /**
     * @link \SimpleVC\Application::__construct()
     */
    #[Test]
    public function testLoadsRoutesFromRouteCollection(): void
    {
        $routes = require CONFIG . '/routes.php';
        $app = new Application($routes);

        $this->_response = $app->run(Request::create('/ok'));
        $this->assertResponseIsSuccessful();
    }

    /**
     * @link \SimpleVC\Application::__construct()
     */
    #[Test]
    public function testThrowsExceptionWhenRoutesFileDoesNotExist(): void
    {
        $this->expectExceptionMessage('Routes file ' . CONFIG . '/missing_routes.php' . ' does not exist.');
        new Application(CONFIG . '/missing_routes.php');
    }

    /**
     * @link \SimpleVC\Application::__construct()
     */
    #[Test]
    public function testThrowsExceptionWhenRoutesFileReturnsInvalidValue(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'invalid_routes');

        $this->expectExceptionMessage("Routes file `{$file}` must return an instance of `" . RouteCollection::class . '`');
        new Application($file);
        unlink($file);
    }

    /**
     * @link \SimpleVC\Application::run()
     */
    #[Test]
    public function testValidRouteReturnsResponse(): void
    {
        $app = new Application();

        $this->_response = $app->run(Request::create('/ok'));
        $this->assertResponseIsSuccessful();
    }

    /**
     * @link \SimpleVC\Application::run()
     */
    #[Test]
    public function testReturns404WhenRouteIsNotFound(): void
    {
        $app = new Application();

        $this->_response = $app->run(Request::create('/does-not-exist'));
        $this->assertResponseError();
        $this->assertResponseContains('Error response: error 404');
    }

    /**
     * @link \SimpleVC\Application::run()
     */
    #[Test]
    public function testReturns404WhenRouteIsNotFoundWithDebugEnabled(): void
    {
        putenv('DEBUG=true');

        $errorRenderer = new ErrorRenderer(new NullLogger());

        $app = new Application(null, $errorRenderer);
        $this->_response = $app->run(Request::create('/does-not-exist'));
        $this->assertResponseError();
        $this->assertResponseContains('No routes found for "/does-not-exist".');
    }

    /**
     * @link \SimpleVC\Application::run()
     */
    #[Test]
    public function testReturns500WhenUnhandledExceptionOccurs(): void
    {
        $app = new Application();

        $this->_response = $app->run(Request::create('/boom'));
        $this->assertResponseFailure();
        $this->assertResponseContains('Error response: fatal 500');
    }

    /**
     * @link \SimpleVC\Application::run()
     */
    #[Test]
    public function testReturns500WhenUnhandledExceptionOccursWithDebugEnabled(): void
    {
        putenv('DEBUG=true');

        $errorRenderer = new ErrorRenderer(new NullLogger());

        $app = new Application(null, $errorRenderer);

        $this->_response = $app->run(Request::create('/boom'));
        $this->assertResponseContains('Error response: fatal 500 - Boom');
    }
}
