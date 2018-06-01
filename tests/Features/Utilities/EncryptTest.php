<?php
/**
 * @package     Qodehub\Bitgo
 * @link        https://github.com/qodehub/bitgo-php
 *
 * @author      Ariama O. Victor (ovac4u) <victorariama@qodehub.com>
 * @link        http://www.ovac4u.com
 *
 * @license     https://github.com/qodehub/bitgo-php/blob/master/LICENSE
 * @copyright   (c) 2018, QodeHub, Ltd
 */

namespace Qodehub\Bitgo\Tests\Features\Utilities;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Qodehub\Bitgo\Bitgo;
use Qodehub\Bitgo\Config;
use Qodehub\Bitgo\Exception\MissingParameterException;
use Qodehub\Bitgo\Utilities;
use Qodehub\Bitgo\Utilities\Encrypt;
use Qodehub\Bitgo\Utility\BitgoHandler;

class EncryptTest extends TestCase
{
    /**
     * The bearer token that will be used by this API
     * @var string
     */
    protected $token = 'existing-token';

    /**
     * This will determine if HTTP(S) will be used
     * @var boolean
     */
    protected $secure = true;

    /**
     * This is the host on which the Bitgo API is running.
     * @var string
     */
    protected $host = 'some-host.com';

    /**
     * The configuration instance.
     * @var Config
     */
    protected $config;

    /**
     * This is the ID of the wallet used in this test
     * @var string
     */
    protected $walletId = 'existing-wallet-id';

    /**
     * This is the ID of the address used in this test
     * @var string
     */
    protected $addressId = 'existing-address';

    /**
     * Setup the test environment viriables
     * @return [type] [description]
     */
    public function setup()
    {
        $this->config = new Config($this->token, $this->secure, $this->host);
    }

    public function a_call_to_the_run_methd_should_return_an_error_if_the_input_is_missing()
    {
        /**
         * Mock the execute method in the Encrypt to intercept calls to the server
         */
        $mock = $this->getMockBuilder(Encrypt::class)
            ->setMethods(['execute'])
            ->getMock();

        $mock->method('execute')->will($this->returnValue(null));

        $this->expectException(MissingParameterException::class);
        $mock->password('hello')->run();
    }

    public function a_call_to_the_run_methd_should_return_an_error_if_the_password_is_missing()
    {
        /**
         * Mock the execute method in the Encrypt to intercept calls to the server
         */
        $mock = $this->getMockBuilder(Encrypt::class)
            ->setMethods(['execute'])
            ->getMock();

        $mock->method('execute')->will($this->returnValue(null));

        $this->expectException(MissingParameterException::class);
        $mock->input('hello')->run();
    }

    /** @test */
    public function test_that_a_call_to_the_server_will_be_successful_if_all_is_right()
    {
        /**
         * Setup the Handler and middlewares interceptor to intercept the call to the server
         */
        $container = [];

        $history = Middleware::history($container);

        $httpMock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], json_encode(['X-Foo' => 'Bar'])),
        ]);

        $handlerStack = (new BitgoHandler($this->config, HandlerStack::create($httpMock)))->createHandler();

        $handlerStack->push($history);

        /**
         * Listen to the Encrypt class method and use the interceptor
         *
         * Intercept all calls to the server from the createHandler method
         */
        $mock = $this->getMockBuilder(Encrypt::class)
            ->setMethods(['createHandler'])
            ->getMock();

        $mock->expects($this->once())->method('createHandler')->will($this->returnValue($handlerStack));

        /**
         * Inject the configuration and use the
         */
        $mock
            ->injectConfig($this->config)

            ->input('hello')

            ->password('hi')
        ;

        /**
         * Run the call to the server
         */
        $result = $mock->run();

        /**
         * Run assertion that call reached the Mock Server
         */
        $this->assertEquals($result, json_decode(json_encode(['X-Foo' => 'Bar'])));

        /**
         * Grab the requests and test that the request parameters
         * are correct as expected.
         */
        $request = $container[0]['request'];

        $this->assertEquals($request->getMethod(), 'POST', 'it should be a post request.');
        $this->assertEquals($request->getUri()->getHost(), 'some-host.com', 'Hostname should be some-host.com');
        $this->assertEquals($request->getHeaderLine('User-Agent'), Bitgo::CLIENT . ' v' . Bitgo::VERSION);

        $this->assertEquals($request->getUri()->getScheme(), 'https', 'it should be a https scheme');

        $this->assertContains(
            "https://some-host.com/api/v2/encrypt",
            $request->getUri()->__toString()
        );
    }
}
