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

namespace Qodehub\Bitgo\Wallet;

use Qodehub\Bitgo\Api\Api;
use Qodehub\Bitgo\Coin;
use Qodehub\Bitgo\Utility\CanCleanParameters;
use Qodehub\Bitgo\Utility\MassAssignable;
use Qodehub\Bitgo\Wallet;

/**
 * Transactions Class
 *
 * This will be the base for all wallet related transaction
 *
 * This class will require that a walletId is present. Examples are attaches
 *
 * @example Bitgo::btc($config)->wallet($walletId)->transactions()->get();
 * @example Bitgo::btc($config)->wallet($walletId)->transactions($transactionId)->get();
 */
class Transactions extends Api
{
    use WalletTrait;
    use MassAssignable;
    use CanCleanParameters;
    use Coin;

    /**
     * {@inheritdoc}
     */
    protected $parametersRequired = [
        'walletId',
    ];

    /**
     * {@inheritdoc}
     */
    protected $parametersOptional = [
        'prevId',
        'allTokens',
        'transactionId',
    ];

    /**
     * Construct for creating a new instance of this class
     *
     * @param array|string $data An array with assignable Parameters or the
     *                           transactionID
     */
    public function __construct($data = null)
    {
        if (is_string($data)) {
            $this->setTransactionId($data);
        }

        if (is_array($data)) {
            $this->massAssign($data);
        }

        return $this;
    }

    /**
     * Build a new transaction
     *
     * @param  any ...$args this will pass all arguments to the
     *                      BuildTransaction constructor and make
     *                      a new instance.
     * @return \Qodehub\Bitgo\Wallet\BuildTransaction A new BuildTransaction instance
     */
    public function build(...$args)
    {
        return (new BuildTransaction(...$args))
            ->coinType($this->coinType)
            ->wallet($this->walletId)
            ->injectConfig($this->config);
    }

    /**
     * Sign a new transaction
     *
     * @param  any ...$args this will pass all arguments to the
     *                      SignTransaction constructor and make
     *                      a new instance.
     * @return \Qodehub\Bitgo\Wallet\SignTransaction A new SignTransaction instance
     */
    public function sign(...$args)
    {
        return (new SignTransaction(...$args))
            ->coinType($this->coinType)
            ->wallet($this->walletId)
            ->injectConfig($this->config);
    }

    /**
     * Send a new transaction
     *
     * @param  any ...$args this will pass all arguments to the
     *                      SendTransaction constructor and make
     *                      a new instance.
     * @return \Qodehub\Bitgo\Wallet\SendTransaction A new SendTransaction instance
     */
    public function send(...$args)
    {
        return (new SendTransaction(...$args))
            ->coinType($this->coinType)
            ->wallet($this->walletId)
            ->injectConfig($this->config);
    }

    /**
     * The ID of a single transaction. This will need to be set
     * in order to get a single transaction.
     *
     * @var srring
     */
    protected $transactionId;

    /**
     * ontinue iterating from this prevId (provided
     * by nextBatchPrevId in the previous list)
     *
     * @var string
     */
    protected $prevId;
    /**
     * Gets transfers of all tokens associated
     * with this wallet. Only valid for eth/teth.
     *
     * @var boolean
     */
    protected $allTokens;

    /**
     * Find a single transacton adminst all the transactions
     * from the server.
     *
     * @param  string $transactionId This will be an existing
     *                               transaction id
     * @return self
     */
    public function find($transactionId)
    {
        return $this->setTransactionId($transactionId);
    }

    /**
     * @param string $prevId
     *
     * @return self
     */
    public function prevId($prevId)
    {
        return $this->setprevId($prevId);
    }

    /**
     * @param boolean $allTokens
     *
     * @return self
     */
    public function allTokens($allTokens = null)
    {
        return $this->setallTokens(is_null($allTokens) ? true : $allTokens);
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param string $transactionId
     *
     * @return self
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrevId()
    {
        return $this->prevId;
    }

    /**
     * @param string $prevId
     *
     * @return self
     */
    public function setPrevId($prevId)
    {
        $this->prevId = $prevId;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getAllTokens()
    {
        return $this->allTokens;
    }

    /**
     * @param boolean $allTokens
     *
     * @return self
     */
    public function setAllTokens($allTokens)
    {
        $this->allTokens = $allTokens;

        return $this;
    }

    /**
     * The method places the call to the Bitgo API
     *
     * @return Object
     */
    public function run()
    {
        $this->propertiesPassRequired();

        return $this->_get('/wallet/{walletId}/tx/{transactionId}', $this->propertiesToArray());
    }
}
