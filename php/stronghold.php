<?php

namespace ccxt;

// PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
// https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code

use Exception; // a common import
use \ccxt\ExchangeError;
use \ccxt\ArgumentsRequired;
use \ccxt\NotSupported;

class stronghold extends Exchange {

    public function describe() {
        return $this->deep_extend(parent::describe (), array(
            'id' => 'stronghold',
            'name' => 'Stronghold',
            'country' => array( 'US' ),
            'rateLimit' => 1000,
            'version' => 'v1',
            'comment' => 'This comment is optional',
            'urls' => array(
                'logo' => 'https://user-images.githubusercontent.com/1294454/52160042-98c1f300-26be-11e9-90dd-da8473944c83.jpg',
                'api' => array(
                    'public' => 'https://api.stronghold.co',
                    'private' => 'https://api.stronghold.co',
                ),
                'www' => 'https://stronghold.co',
                'doc' => array(
                    'https://docs.stronghold.co',
                ),
            ),
            'requiredCredentials' => array(
                'apiKey' => true,
                'secret' => true,
                'password' => true,
            ),
            'has' => array(
                'cancelOrder' => true,
                'createDepositAddress' => true,
                'createOrder' => true,
                'fetchAccounts' => true,
                'fetchBalance' => true,
                'fetchDepositAddress' => false,
                'fetchCurrencies' => true,
                'fetchMarkets' => true,
                'fetchMyTrades' => true,
                'fetchOpenOrders' => true,
                'fetchOrderBook' => true,
                'fetchTicker' => false,
                'fetchTickers' => false,
                'fetchTime' => true,
                'fetchTrades' => true,
                'fetchTransactions' => true,
                'withdraw' => true,
            ),
            'api' => array(
                'public' => array(
                    'get' => array(
                        'utilities/time',
                        'utilities/uuid',
                        'venues',
                        'venues/{venueId}/assets',
                        'venues/{venueId}/markets',
                        'venues/{venueId}/markets/{marketId}/orderbook',
                        'venues/{venueId}/markets/{marketId}/trades',
                    ),
                    'post' => array(
                        'venues/{venueId}/assets',
                        'iam/credentials',
                        'identities',
                    ),
                    'patch' => array(
                        'identities',
                    ),
                    'put' => array(
                        'iam/credentials/{credentialId}',
                    ),
                    'delete' => array(
                        'iam/credentials/{credentialId}',
                    ),
                ),
                'private' => array(
                    'get' => array(
                        'venues',
                        'venues/{venueId}/accounts',
                        'venues/{venueId}/accounts/{accountId}',
                        'venues/{venueId}/accounts/{accountId}/payments/{paymentId}',
                        'venues/{venueId}/accounts/{accountId}/orders',
                        'venues/{venueId}/accounts/{accountId}/trades',
                        'venues/{venueId}/accounts/{accountId}/transactions',
                    ),
                    'post' => array(
                        'venues/{venueId}/accounts',
                        'venues/{venueId}/accounts/{accountId}/orders',
                        'venues/{venueId}/accounts/{accountId}/deposit',
                        'venues/{venueId}/accounts/{accountId}/withdrawal',
                        'venues/{venueId}/accounts/{accountId}/payments',
                        'venues/{venueId}/accounts/{accountId}/payments/{paymentId}/stop',
                        'venues/{venueId}/custody/accounts/{accountId}/operations/{operationId}/signatures',
                        'venues/{venueId}/anchor/withdrawal',
                        'venues/{venueId}/testing/friendbot',
                    ),
                    'delete' => array(
                        'venues/{venueId}/accounts/{accountId}/orders/{orderId}',
                    ),
                ),
            ),
            'options' => array(
                'accountId' => null,
                'venueId' => 'trade-public',
                'venues' => array(
                    'trade' => 'trade-public',
                    'sandbox' => 'sandbox-public',
                ),
                'paymentMethods' => array(
                    'ETH' => 'ethereum',
                    'BTC' => 'bitcoin',
                    'XLM' => 'stellar',
                    'XRP' => 'ripple',
                    'LTC' => 'litecoin',
                    'SHX' => 'stellar',
                ),
            ),
            'exceptions' => array(
                'CREDENTIAL_MISSING' => '\\ccxt\\AuthenticationError',
                'CREDENTIAL_INVALID' => '\\ccxt\\AuthenticationError',
                'CREDENTIAL_REVOKED' => '\\ccxt\\AccountSuspended',
                'CREDENTIAL_NO_IDENTITY' => '\\ccxt\\AuthenticationError',
                'PASSPHRASE_INVALID' => '\\ccxt\\AuthenticationError',
                'SIGNATURE_INVALID' => '\\ccxt\\AuthenticationError',
                'TIME_INVALID' => '\\ccxt\\InvalidNonce',
                'BYPASS_INVALID' => '\\ccxt\\AuthenticationError',
                'INSUFFICIENT_FUNDS' => '\\ccxt\\InsufficientFunds',
            ),
        ));
    }

    public function get_active_account() {
        if ($this->options['accountId'] !== null) {
            return $this->options['accountId'];
        }
        $this->load_accounts();
        $numAccounts = is_array($this->accounts) ? count($this->accounts) : 0;
        if ($numAccounts > 0) {
            return $this->accounts[0]['id'];
        }
        throw new ExchangeError($this->id . ' requires an accountId.');
    }

    public function fetch_accounts($params = array ()) {
        $request = array(
            'venueId' => $this->options['venueId'],
        );
        $response = $this->privateGetVenuesVenueIdAccounts (array_merge($request, $params));
        //
        //   array( { id => '34080200-b25a-483d-a734-255d30ba324d',
        //       venueSpecificId => '' } ... )
        //
        return $response['result'];
    }

    public function fetch_time($params = array ()) {
        $response = $this->publicGetUtilitiesTime ($params);
        //
        //     {
        //         "requestId" => "6de8f506-ad9d-4d0d-94f3-ec4d55dfcdb9",
        //         "timestamp" => 1536436649207281,
        //         "success" => true,
        //         "statusCode" => 200,
        //         "result" => {
        //             "timestamp" => "2018-09-08T19:57:29.207282Z"
        //         }
        //     }
        //
        return $this->parse8601($this->safe_string($response['result'], 'timestamp'));
    }

    public function fetch_markets($params = array ()) {
        $request = array(
            'venueId' => $this->options['venueId'],
        );
        $response = $this->publicGetVenuesVenueIdMarkets (array_merge($request, $params));
        $data = $response['result'];
        //
        //     array(
        //         array(
        //             id => 'SHXUSD',
        //             $baseAssetId => 'SHX/stronghold.co',
        //             counterAssetId => 'USD/stronghold.co',
        //             minimumOrderSize => '1.0000000',
        //             minimumOrderIncrement => '1.0000000',
        //             minimumPriceIncrement => '0.00010000',
        //             displayDecimalsPrice => 4,
        //             displayDecimalsAmount => 0
        //         ),
        //         ...
        //     )
        //
        $result = array();
        for ($i = 0; $i < count($data); $i++) {
            $entry = $data[$i];
            $marketId = $entry['id'];
            $baseId = $this->safe_string($entry, 'baseAssetId');
            $quoteId = $this->safe_string($entry, 'counterAssetId');
            $baseAssetId = explode('/', $baseId)[0];
            $quoteAssetId = explode('/', $quoteId)[0];
            $base = $this->safe_currency_code($baseAssetId);
            $quote = $this->safe_currency_code($quoteAssetId);
            $symbol = $base . '/' . $quote;
            $limits = array(
                'amount' => array(
                    'min' => $this->safe_float($entry, 'minimumOrderSize'),
                    'max' => null,
                ),
            );
            $precision = array(
                'price' => $this->safe_integer($entry, 'displayDecimalsPrice'),
                'amount' => $this->safe_integer($entry, 'displayDecimalsAmount'),
            );
            $result[$symbol] = array(
                'symbol' => $symbol,
                'id' => $marketId,
                'base' => $base,
                'quote' => $quote,
                'baseId' => $baseId,
                'quoteId' => $quoteId,
                'precision' => $precision,
                'info' => $entry,
                'limits' => $limits,
                'active' => null,
            );
        }
        return $result;
    }

    public function fetch_currencies($params = array ()) {
        $request = array(
            'venueId' => $this->options['venueId'],
        );
        $response = $this->publicGetVenuesVenueIdAssets (array_merge($request, $params));
        //
        //     array(
        //         array(
        //             id => 'XLM/native',
        //             alias => '',
        //             $code => 'XLM',
        //             name => '',
        //             displayDecimalsFull => 7,
        //             displayDecimalsSignificant => 2,
        //         ),
        //         ...
        //     )
        //
        $data = $response['result'];
        $result = array();
        $limits = array(
            'amount' => array(
                'min' => null,
                'max' => null,
            ),
            'price' => array(
                'min' => null,
                'max' => null,
            ),
            'cost' => array(
                'min' => null,
                'max' => null,
            ),
            'withdraw' => array(
                'min' => null,
                'max' => null,
            ),
        );
        for ($i = 0; $i < count($data); $i++) {
            $entry = $data[$i];
            $assetId = $this->safe_string($entry, 'id');
            $currencyId = $this->safe_string($entry, 'code');
            $code = $this->safe_currency_code($currencyId);
            $precision = $this->safe_integer($entry, 'displayDecimalsFull');
            $result[$code] = array(
                'code' => $code,
                'id' => $assetId,
                'precision' => $precision,
                'info' => $entry,
                'active' => null,
                'name' => null,
                'limits' => $limits,
                'fee' => null,
            );
        }
        return $result;
    }

    public function fetch_order_book($symbol, $limit = null, $params = array ()) {
        $this->load_markets();
        $marketId = $this->market_id($symbol);
        $request = array(
            'marketId' => $marketId,
            'venueId' => $this->options['venueId'],
        );
        $response = $this->publicGetVenuesVenueIdMarketsMarketIdOrderbook (array_merge($request, $params));
        //
        //     {
        //         $marketId => 'ETHBTC',
        //         bids => array(
        //             array( '0.031500', '7.385000' ),
        //             ...,
        //         ),
        //         asks => array(
        //             array( '0.031500', '7.385000' ),
        //             ...,
        //         ),
        //     }
        //
        $data = $response['result'];
        $timestamp = $this->parse8601($this->safe_string($response, 'timestamp'));
        return $this->parse_order_book($data, $timestamp);
    }

    public function fetch_trades($symbol, $since = null, $limit = null, $params = array ()) {
        $this->load_markets();
        $market = $this->market($symbol);
        $request = array(
            'marketId' => $market['id'],
            'venueId' => $this->options['venueId'],
        );
        $response = $this->publicGetVenuesVenueIdMarketsMarketIdTrades (array_merge($request, $params));
        //
        //     {
        //         "requestId" => "4d343700-b53f-4975-afcc-732ae9d3c828",
        //         "timestamp" => "2018-11-08T19:22:11.399543Z",
        //         "success" => true,
        //         "statusCode" => 200,
        //         "result" => {
        //             "marketId" => "",
        //             "trades" => array(
        //                 array( "0.9", "3.10", "sell", "2018-11-08T19:22:11.399547Z" ),
        //                 ...
        //             ),
        //         }
        //     }
        //
        return $this->parse_trades($response['result']['trades'], $market, $since, $limit);
    }

    public function parse_trade($trade, $market = null) {
        //
        // fetchTrades (public)
        //
        //      array( '0.03177000', '0.0643501', 'sell', '2019-01-27T23:02:04Z' )
        //
        // fetchMyTrades (private)
        //
        //     {
        //         $id => '9cdb109c-d035-47e2-81f8-a0c802c9c5f9',
        //         $orderId => 'a38d8bcb-9ff5-4c52-81a0-a40196a66462',
        //         $marketId => 'XLMUSD',
        //         $side => 'sell',
        //         size => '1.0000000',
        //         $price => '0.10440600',
        //         settled => true,
        //         maker => false,
        //         executedAt => '2019-02-01T18:44:21Z'
        //     }
        //
        $id = null;
        $takerOrMaker = null;
        $price = null;
        $amount = null;
        $cost = null;
        $side = null;
        $timestamp = null;
        $orderId = null;
        if (gettype($trade) === 'array' && count(array_filter(array_keys($trade), 'is_string')) == 0) {
            $price = floatval ($trade[0]);
            $amount = floatval ($trade[1]);
            $side = $trade[2];
            $timestamp = $this->parse8601($trade[3]);
        } else {
            $id = $this->safe_string($trade, 'id');
            $price = $this->safe_float($trade, 'price');
            $amount = $this->safe_float($trade, 'size');
            $side = $this->safe_string($trade, 'side');
            $timestamp = $this->parse8601($this->safe_string($trade, 'executedAt'));
            $orderId = $this->safe_string($trade, 'orderId');
            $marketId = $this->safe_string($trade, 'marketId');
            $market = $this->safe_value($this->markets_by_id, $marketId);
            $isMaker = $this->safe_value($trade, 'maker');
            $takerOrMaker = $isMaker ? 'maker' : 'taker';
        }
        if ($amount !== null && $price !== null) {
            $cost = $amount * $price;
        }
        $symbol = null;
        if ($market !== null) {
            $symbol = $market['symbol'];
        }
        return array(
            'id' => $id,
            'info' => $trade,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601($timestamp),
            'symbol' => $symbol,
            'type' => null,
            'order' => $orderId,
            'side' => $side,
            'price' => $price,
            'amount' => $amount,
            'cost' => $cost,
            'takerOrMaker' => $takerOrMaker,
            'fee' => array(
                'cost' => null,
                'currency' => null,
                'rate' => null,
            ),
        );
    }

    public function fetch_transactions($code = null, $since = null, $limit = null, $params = array ()) {
        $this->load_markets();
        $request = array_merge(array(
            'venueId' => $this->options['venueId'],
            'accountId' => $this->get_active_account(),
        ), $params);
        if (!$request['accountId']) {
            throw new ArgumentsRequired($this->id . " fetchTransactions requires either the 'accountId' extra parameter or exchange.options['accountId'] = 'YOUR_ACCOUNT_ID'.");
        }
        $response = $this->privateGetVenuesVenueIdAccountsAccountIdTransactions ($request);
        $currency = null;
        if ($code !== null) {
            $currency = $this->currency($code);
        }
        return $this->parse_transactions($response['result'], $currency, $since, $limit);
    }

    public function parse_transaction_status($status) {
        $statuses = array(
            'queued' => 'pending',
            'settling' => 'pending',
        );
        return $this->safe_string($statuses, $status, $status);
    }

    public function parse_transaction($transaction, $currency = null) {
        // {
        //     "$id" => "6408e003-0f14-4457-9340-ba608992ad5c",
        //     "$status" => "queued",
        //     "$direction" => "outgoing",
        //     "$amount" => "98.95000000",
        //     "$assetId" => "XLM/native",
        //     "sourceAccount" => array(
        //       "$id" => "774fa8ef-600b-4636-b9ed-cd6d23421915",
        //       "venueSpecificId" => "GC5FIBIQZTQRMJE34GYF5EKH77GEQ3OHFX3NIP5OKDIZFA6VERLZSHY6"
        //     ),
        //     "destinationAccount" => {
        //       "$id" => "f72b9fb5-9607-4dd3-b31f-6ded21337056",
        //       "venueSpecificId" => "GAOWV6CYBE7DEWSWPODXLMI5YB75VXXZJX5OYVQ2YLZH2TVA3TMMSNYW"
        //     }
        //   }
        $id = $this->safe_string($transaction, 'id');
        $assetId = $this->safe_string($transaction, 'assetId');
        $code = null;
        if ($assetId !== null) {
            $currencyId = explode('/', $assetId)[0];
            $code = $this->safe_currency_code($currencyId);
        } else {
            if ($currency !== null) {
                $code = $currency['code'];
            }
        }
        $amount = $this->safe_float($transaction, 'amount');
        $status = $this->parse_transaction_status($this->safe_string($transaction, 'status'));
        $feeCost = $this->safe_float($transaction, 'feeAmount');
        $feeRate = null;
        if ($feeCost !== null) {
            $feeRate = $feeCost / $amount;
        }
        $direction = $this->safe_string($transaction, 'direction');
        $datetime = $this->safe_string($transaction, 'requestedAt');
        $timestamp = $this->parse8601($datetime);
        $updated = $this->parse8601($this->safe_string($transaction, 'updatedAt'));
        $type = ($direction === 'outgoing' || $direction === 'withdrawal') ? 'withdrawal' : 'deposit';
        $fee = array(
            'cost' => $feeCost,
            'rate' => $feeRate,
        );
        return array(
            'id' => $id,
            'info' => $transaction,
            'currency' => $code,
            'amount' => $amount,
            'status' => $status,
            'fee' => $fee,
            'tag' => null,
            'type' => $type,
            'updated' => $updated,
            'address' => null,
            'txid' => null,
            'timestamp' => $timestamp,
            'datetime' => $datetime,
        );
    }

    public function create_order($symbol, $type, $side, $amount, $price = null, $params = array ()) {
        $this->load_markets();
        $market = $this->market($symbol);
        $request = array_merge(array(
            'venueId' => $this->options['venueId'],
            'accountId' => $this->get_active_account(),
            'marketID' => $market['id'],
            'type' => $type,
            'side' => $side,
            'size' => $this->amount_to_precision($symbol, $amount),
            'price' => $this->price_to_precision($symbol, $price),
        ), $params);
        if (!$request['accountId']) {
            throw new ArgumentsRequired($this->id . " createOrder requires either the 'accountId' extra parameter or exchange.options['accountId'] = 'YOUR_ACCOUNT_ID'.");
        }
        $response = $this->privatePostVenuesVenueIdAccountsAccountIdOrders ($request);
        return $this->parse_order($response, $market);
    }

    public function cancel_order($id, $symbol = null, $params = array ()) {
        $request = array_merge(array(
            'venueId' => $this->options['venueId'],
            'accountId' => $this->get_active_account(),
            'orderId' => $id,
        ), $params);
        if (!$request['accountId']) {
            throw new ArgumentsRequired($this->id . " cancelOrder requires either the 'accountId' extra parameter or exchange.options['accountId'] = 'YOUR_ACCOUNT_ID'.");
        }
        $response = $this->privateDeleteVenuesVenueIdAccountsAccountIdOrdersOrderId ($request);
        return $this->parse_order($response);
    }

    public function fetch_open_orders($symbol = null, $since = null, $limit = null, $params = array ()) {
        $this->load_markets();
        $market = null;
        if ($symbol !== null) {
            $market = $this->market($symbol);
        }
        $request = array_merge(array(
            'venueId' => $this->options['venueId'],
            'accountId' => $this->get_active_account(),
        ), $params);
        if (!$request['accountId']) {
            throw new ArgumentsRequired($this->id . " cancelOrder requires either the 'accountId' extra parameter or exchange.options['accountId'] = 'YOUR_ACCOUNT_ID'.");
        }
        $response = $this->privateGetVenuesVenueIdAccountsAccountIdOrders ($request);
        return $this->parse_orders($response['result'], $market, $since, $limit);
    }

    public function parse_order($order, $market = null) {
        // { $id => '178596',
        //   $marketId => 'XLMUSD',
        //   side => 'buy',
        //   size => '1.0000000',
        //   sizeFilled => '0',
        //   $price => '0.10000000',
        //   placedAt => '2019-02-01T19:47:52Z' }
        $marketId = $this->safe_string($order, 'marketId');
        if ($marketId !== null) {
            $market = $this->safe_value($this->marketsById, $marketId);
        }
        $symbol = null;
        if ($market !== null) {
            $symbol = $market['symbol'];
        }
        $id = $this->safe_string($order, 'id');
        $datetime = $this->safe_string($order, 'placedAt');
        $amount = $this->safe_float($order, 'size');
        $price = $this->safe_float($order, 'price');
        $filled = $this->safe_float($order, 'sizeFilled');
        $cost = null;
        $remaining = null;
        if ($amount !== null) {
            if ($filled !== null) {
                $remaining = $amount - $filled;
            }
            if ($price !== null) {
                $cost = $amount * $price;
            }
        }
        return array(
            'id' => $id,
            'clientOrderId' => null,
            'info' => $order,
            'symbol' => $symbol,
            'datetime' => $datetime,
            'timestamp' => $this->parse8601($datetime),
            'side' => $this->safe_string($order, 'side'),
            'amount' => $amount,
            'filled' => $filled,
            'remaining' => $remaining,
            'price' => $price,
            'cost' => $cost,
            'trades' => array(),
            'lastTradeTimestamp' => null,
            'status' => null,
            'type' => null,
            'average' => null,
            'fee' => null,
        );
    }

    public function nonce() {
        return $this->seconds();
    }

    public function set_sandbox_mode($enabled) {
        if ($enabled) {
            $this->options['venueId'] = $this->options['venues']['sandbox'];
        } else {
            $this->options['venueId'] = $this->options['venues']['trade'];
        }
    }

    public function fetch_balance($params = array ()) {
        $request = array_merge(array(
            'venueId' => $this->options['venueId'],
            'accountId' => $this->get_active_account(),
        ), $params);
        if (!(is_array($request) && array_key_exists('accountId', $request))) {
            throw new ArgumentsRequired($this->id . " fetchBalance requires either the 'accountId' extra parameter or exchange.options['accountId'] = 'YOUR_ACCOUNT_ID'.");
        }
        $response = $this->privateGetVenuesVenueIdAccountsAccountId ($request);
        $balances = $this->safe_value($response['result'], 'balances');
        $result = array( 'info' => $response );
        for ($i = 0; $i < count($balances); $i++) {
            $balance = $balances[$i];
            $assetId = $this->safe_string($balance, 'assetId');
            if ($assetId !== null) {
                $currencyId = explode('/', $assetId)[0];
                $code = $this->safe_currency_code($currencyId);
                $account = array();
                $account['total'] = $this->safe_float($balance, 'amount');
                $account['free'] = $this->safe_float($balance, 'availableForTrade');
                $result[$code] = $account;
            }
        }
        return $this->parse_balance($result);
    }

    public function fetch_my_trades($symbol = null, $since = null, $limit = null, $params = array ()) {
        $this->load_markets();
        $request = array_merge(array(
            'venueId' => $this->options['venueId'],
            'accountId' => $this->get_active_account(),
        ), $params);
        if (!$request['accountId']) {
            throw new ArgumentsRequired($this->id . " fetchMyTrades requires either the 'accountId' extra parameter or exchange.options['accountId'] = 'YOUR_ACCOUNT_ID'.");
        }
        $response = $this->privateGetVenuesVenueIdAccountsAccountIdTrades ($request);
        $market = null;
        if ($symbol !== null) {
            $market = $this->market($symbol);
        }
        return $this->parse_trades($response['result'], $market, $since, $limit);
    }

    public function create_deposit_address($code, $params = array ()) {
        $this->load_markets();
        $paymentMethod = $this->safe_string($this->options['paymentMethods'], $code);
        if ($paymentMethod === null) {
            throw new NotSupported($this->id . ' createDepositAddress requires $code to be BTC, ETH, or XLM');
        }
        $request = array_merge(array(
            'venueId' => $this->options['venueId'],
            'accountId' => $this->get_active_account(),
            'assetId' => $this->currency_id($code),
            'paymentMethod' => $paymentMethod,
        ), $params);
        if (!$request['accountId']) {
            throw new ArgumentsRequired($this->id . " createDepositAddress requires either the 'accountId' extra parameter or exchange.options['accountId'] = 'YOUR_ACCOUNT_ID'.");
        }
        $response = $this->privatePostVenuesVenueIdAccountsAccountIdDeposit ($request);
        //
        //     {
        //         assetId => 'BTC/stronghold.co',
        //         $paymentMethod => 'bitcoin',
        //         paymentMethodInstructions => array(
        //             deposit_address => 'mzMT9Cfw8JXVWK7rMonrpGfY9tt57ytHt4',
        //             reference => 'sometimes-exists',
        //         ),
        //         direction => 'deposit',
        //     }
        //
        $data = $response['result']['paymentMethodInstructions'];
        $address = $data['deposit_address'];
        $tag = $this->safe_string($data, 'reference');
        return array(
            'currency' => $code,
            'address' => $this->check_address($address),
            'tag' => $tag,
            'info' => $response,
        );
    }

    public function withdraw($code, $amount, $address, $tag = null, $params = array ()) {
        $this->load_markets();
        $paymentMethod = $this->safe_string($this->options['paymentMethods'], $code);
        if ($paymentMethod === null) {
            throw new NotSupported($this->id . ' withdraw requires $code to be BTC, ETH, or XLM');
        }
        $request = array_merge(array(
            'venueId' => $this->options['venueId'],
            'accountId' => $this->get_active_account(),
            'assetId' => $this->currency_id($code),
            'amount' => $amount,
            'paymentMethod' => $paymentMethod,
            'paymentMethodDetails' => array(
                'withdrawal_address' => $address,
            ),
        ), $params);
        if ($tag !== null) {
            $request['paymentMethodDetails']['reference'] = $tag;
        }
        if (!$request['accountId']) {
            throw new ArgumentsRequired($this->id . " withdraw requires either the 'accountId' extra parameter or exchange.options['accountId'] = 'YOUR_ACCOUNT_ID'.");
        }
        $response = $this->privatePostVenuesVenueIdAccountsAccountIdWithdrawal ($request);
        //
        //     {
        //         "id" => "5be48892-1b6e-4431-a3cf-34b38811e82c",
        //         "assetId" => "BTC/stronghold.co",
        //         "$amount" => "10",
        //         "feeAmount" => "0.01",
        //         "$paymentMethod" => "bitcoin",
        //         "paymentMethodDetails" => array(
        //             "withdrawal_address" => "1vHysJeXYV6nqhroBaGi52QWFarbJ1dmQ"
        //         ),
        //         "direction" => "withdrawal",
        //         "status" => "pending"
        //     }
        //
        $data = $response['result'];
        return array(
            'id' => $this->safe_string($data, 'id'),
            'info' => $response,
        );
    }

    public function handle_errors($code, $reason, $url, $method, $headers, $body, $response, $requestHeaders, $requestBody) {
        if (!$response) {
            return; // fallback to base error handler by default
        }
        //
        //     {
        //         requestId => '3e7d17ab-b316-4721-b5aa-f7e6497eeab9',
        //         timestamp => '2019-01-31T21:59:06.696855Z',
        //         $success => true,
        //         statusCode => 200,
        //         result => array()
        //     }
        //
        $errorCode = $this->safe_string($response, 'errorCode');
        if (is_array($this->exceptions) && array_key_exists($errorCode, $this->exceptions)) {
            $Exception = $this->exceptions[$errorCode];
            throw new $Exception($this->id . ' ' . $body);
        }
        $success = $this->safe_value($response, 'success');
        if (!$success) {
            throw new ExchangeError($this->id . ' ' . $body);
        }
    }

    public function sign($path, $api = 'public', $method = 'GET', $params = array (), $headers = null, $body = null) {
        $request = '/' . $this->version . '/' . $this->implode_params($path, $params);
        $query = $this->omit($params, $this->extract_params($path));
        $url = $this->urls['api'][$api] . $request;
        if ($query) {
            if ($method === 'GET') {
                $url .= '?' . $this->urlencode($query);
            } else {
                $body = $this->json($query);
            }
        }
        if ($api === 'private') {
            $this->check_required_credentials();
            $timestamp = (string) $this->nonce();
            $payload = $timestamp . $method . $request;
            if ($body !== null) {
                $payload .= $body;
            }
            $secret = base64_decode($this->secret);
            $headers = array(
                'SH-CRED-ID' => $this->apiKey,
                'SH-CRED-SIG' => $this->hmac($this->encode($payload), $secret, 'sha256', 'base64'),
                'SH-CRED-TIME' => $timestamp,
                'SH-CRED-PASS' => $this->password,
                'Content-Type' => 'application/json',
            );
        }
        return array( 'url' => $url, 'method' => $method, 'body' => $body, 'headers' => $headers );
    }
}
