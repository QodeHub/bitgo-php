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

namespace Qodehub\Bitgo\Tests\Usability\Wallet;

use PHPUnit\Framework\TestCase;
use Qodehub\Bitgo\Bitgo;
use Qodehub\Bitgo\Config;
use Qodehub\Bitgo\Wallet;

class UpdateWalletTest extends TestCase
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
     * This is the coin type used for this test. Can be changed for other coin tests.
     * @var string
     */
    protected $coin = 'tbtc';

    /**
     * The Human-readable wallet name
     * used in this test.
     *
     * @var string
     */
    protected $label = 'A-random-label';
    /**
     * Passphrase to decrypt the wallet’s
     * private key used in this test.
     *
     * @var string
     */
    protected $passphrase = 'SecureWalletPassword$%#';

    /**
     * Values of other optional parameters
     * used in this test.
     */
    protected $disableTransactionNotifications = true;
    protected $tokenFlushThresholds = 1000;
    protected $approvalsRequired = 10;

    /**
     * Setup the test environment viriables
     * @return [type] [description]
     */
    public function setup()
    {
        $this->config = new Config($this->token, $this->secure, $this->host);
    }

    /** @test */
    public function it_can_update_a_wallet_expressively()
    {
        /**
         * This expression uses the update
         * method from the wallet
         * instance.
         */
        $instance1 =

        Bitgo::{$this->coin}($this->config)
            ->wallet($this->walletId)->update()
            ->label($this->label)
        /**
         * Even more optional parameters.
         * ==============================
         *
         * I percieve that this will be rarely used
         * so I have left them as conventional
         * accessors using set and get
         */
            ->setDisableTransactionNotifications($this->disableTransactionNotifications)
            ->setTokenFlushThresholds($this->tokenFlushThresholds)
            ->setApprovalsRequired($this->approvalsRequired)
        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkUpdateWalletInstanceValues($instance1);

        /**
         * This expression uses the updateWallet
         * method from the wallet
         * instance.
         */
        $instance2 =

        Bitgo::{$this->coin}($this->config)
            ->wallet($this->walletId)->updateWallet()
            ->label($this->label)
        // ==============================
            ->setDisableTransactionNotifications($this->disableTransactionNotifications)
            ->setTokenFlushThresholds($this->tokenFlushThresholds)
            ->setApprovalsRequired($this->approvalsRequired)
        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkUpdateWalletInstanceValues($instance2);

        /**
         * This expression uses the updateWallet
         * method from the magic coinType method.
         */
        $instance3 =

        Bitgo::{$this->coin}($this->config)
            ->wallet($this->walletId)->updateWallet()
            ->label($this->label)
        // ==============================
            ->setDisableTransactionNotifications($this->disableTransactionNotifications)
            ->setTokenFlushThresholds($this->tokenFlushThresholds)
            ->setApprovalsRequired($this->approvalsRequired)
        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkUpdateWalletInstanceValues($instance3);
    }

    /** @test */
    public function getting_a_single_address_using_massassignment()
    {
        /**
         * This expression uses the update
         * method from the wallet
         * instance.
         */
        $instance1 =

        Bitgo::{$this->coin}($this->config)
            ->wallet($this->walletId)->update([

            'label' => $this->label,

            /**
             * Even more optional parameters.
             * ==============================
             *
             * I percieve that this will be rarely used
             * so I have left them as conventional
             * accessors using set and get
             */
            'tokenFlushThresholds' => $this->tokenFlushThresholds,
            'approvalsRequired' => $this->approvalsRequired,
            'disableTransactionNotifications' => $this->disableTransactionNotifications,
        ])
        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkUpdateWalletInstanceValues($instance1);

        /**
         * This expression uses the updateWallet
         * method from the wallet
         * instance.
         */
        $instance2 =

        Bitgo::{$this->coin}($this->config)
            ->wallet($this->walletId)->updateWallet([

            'label' => $this->label,
            // ==============================
            'tokenFlushThresholds' => $this->tokenFlushThresholds,
            'approvalsRequired' => $this->approvalsRequired,
            'disableTransactionNotifications' => $this->disableTransactionNotifications,

        ])
        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkUpdateWalletInstanceValues($instance2);

        $this->checkUpdateWalletInstanceValues($instance1);

        /**
         * This expression uses the updateWallet
         * method from the magic coinType method.
         */
        $instance3 =

        Bitgo::{$this->coin}($this->config)
            ->wallet($this->walletId)->updateWallet([

            'label' => $this->label,
            // ==============================
            'tokenFlushThresholds' => $this->tokenFlushThresholds,
            'approvalsRequired' => $this->approvalsRequired,
            'disableTransactionNotifications' => $this->disableTransactionNotifications,

        ])
        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkUpdateWalletInstanceValues($instance3);
    }

    protected function checkUpdateWalletInstanceValues($instance)
    {

        $this->assertSame(
            $instance->getCoinType(),
            $this->coin,
            'Must have a coin type'
        );

        $this->assertEquals(
            $this->config,
            $instance->getConfig(),
            'It should match the config that was passed into the static currency.'
        );

        $this->assertEquals(
            $this->label,
            $instance->getLabel(),
            'The label should match ' . $this->label . ' for this test'
        );

        $this->assertEquals(
            $this->tokenFlushThresholds,
            $instance->getTokenFlushThresholds(),
            'tokenFlushThresholds is Optional but should match ' . $this->tokenFlushThresholds . ' for this test'
        );

        $this->assertEquals(
            $this->approvalsRequired,
            $instance->getApprovalsRequired(),
            'approvalsRequired is Optional but should match ' . $this->approvalsRequired . ' for this test'
        );

        $this->assertEquals(
            $this->disableTransactionNotifications,
            $instance->getDisableTransactionNotifications(),
            'disableTransactionNotifications is Optional but ' . $this->disableTransactionNotifications . ' for this test'
        );
    }
}
