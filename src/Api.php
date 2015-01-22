<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 New Media Campaigns
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Hifi;

use GuzzleHttp\Client;

/**
 * Api
 * @package  Hifi
 * @author   Josh Lockhart <josh@newmediacampaigns.com>
 * @since    1.0.0
 * @property \GuzzleHttp\Client $client
 * @property string             $domain
 */
class Api
{
    /**
     * The Guzzle client used to manage HTTP requests to/from the API
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * The HiFi website domain
     * @var string
     */
    protected $domain;

    /**
     * Constructor
     * @param string $domain   The HiFi website domain name
     * @param string $username The HiFi website user name
     * @param string $password The HiFi website user password
     * @api
     */
    public function __construct($domain, $username, $password)
    {
        $this->domain = $domain . '/hifi/api';
        $this->client = new Client([
            'defaults' => [
                'auth' => [$username, $password],
                'headers' => [
                    'Content-type' => 'application/x-www-form-urlencoded',
                    'Accept' => 'application/json'
                ]
            ]
        ]);
    }

    /**
     * Fetch data from HiFi API
     * @param  array $q A HiFi API query
     * @return array|false
     * @see    http://docs.gethifi.com/developers/hifi-query-language/overview
     * @api
     */
    public function get(array $q)
    {
        $request = $this->client->createRequest('GET', $this->domain);
        $query = $request->getQuery();
        $query->add('q', json_encode($q));
        $query->setEncodingType(false);

        return $this->sendRequest($request);
    }

    /**
     * Send data to HiFi API
     * @param  array $objects Numeric array of HiFi nodes
     * @return array|false
     * @see    http://docs.gethifi.com/developers/hifi-query-language/overview
     * @api
     */
    public function post(array $objects)
    {
        $request = $this->client->createRequest('POST', $this->domain, [
            'body' => [
                'data' => json_encode($objects)
            ]
        ]);

        return $this->sendRequest($request);
    }

    /**
     * Delete data from HiFi API
     * @param  array $objects Numeric array of HiFi nodes
     * @return array|false
     * @see    http://docs.gethifi.com/developers/hifi-query-language/overview
     * @api
     */
    public function delete(array $objects)
    {
        foreach ($objects as $object) {
            $object['fresh'] = 0;
        }

        return $this->post($objects);
    }

    /**
     * Send request
     * @param  \GuzzleHttp\Message\RequestInterface $request
     * @return \GuzzleHttp\Message\ResponseInterface|false
     * @throws \GuzzleHttp\Exception\BadResponseException
     */
    protected function sendRequest(\GuzzleHttp\Message\RequestInterface $request)
    {
        $response = $this->client->send($request);
        $responseBody = $response->json();

        return isset($responseBody['results']) ? $responseBody['results'] : $responseBody;
    }
}
