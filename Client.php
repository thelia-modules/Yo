<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace Yo;

use Thelia\Log\Tlog;

/**
 * Class Client
 * @package YoT
 * @author Manuel Raynaud <manu@thelia.net>
 */
class Client
{

    protected $apiUrl = "https://api.justyo.co";
    protected $apiKey;

    public function __construct($apiKey)
    {
        if (empty($apiKey)) {
            throw new \RuntimeException('the Yo! apikey can`t be empty');
        }

        $this->apiKey = $apiKey;
    }

    /**
     * send a Yo to an existing user
     *
     * @param string $username
     * @param null $link
     * @return mixed
     */
    public function yo($username, $link = null)
    {
        $postFields['username'] = strtoupper($username);

        if (null !== $link) {
            $postFields['link'] = $link;
        }

        $params = [
            'postFields' => $postFields,
            'method' => 'POST',
            'endPoint' => '/yo/'
        ];

        return $this->send($params);
    }

    /**
     * Send a Yo to all your contact
     *
     * @param null $link
     * @return mixed
     */
    public function yoAll($link = null)
    {
        $postFields = [];

        if (null !== $link) {
            $postFields['link'] = $link;
        }

        return $this->send([
            'postFields' => $postFields,
            'method' => 'POST',
            'endPoint' => '/yoall/'
        ]);
    }

    /**
     * Send a request to Yo webservice.
     *
     * @param array $params
     * @return mixed
     */
    public function send($params)
    {
        $url = $this->apiUrl . $params['endPoint'];
        $method = $params['method'];

        $curlOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        ];

        if (strtoupper($method) == 'POST') {
            $curlOptions = $this->processPost($curlOptions, $params['postFields']);
        } else {
            $curlOptions = $this->processGet($curlOptions);
        }

        $ch = curl_init();
        curl_setopt_array($ch, $curlOptions);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        Tlog::getInstance()->debug($info);

        return $response;
    }

    protected function processPost($curlOptions, $postFields)
    {
        $postFields['api_token'] = $this->apiKey;
        $curlOptions[CURLOPT_POST] = true;
        $curlOptions[CURLOPT_POSTFIELDS] = $postFields;

        return $curlOptions;
    }

    protected function processGet($curlOptions)
    {
        $queryString = http_build_query(['api_token' => $this->apiKey]);
        $curlOptions[CURLOPT_URL] .= '?' . $queryString;

        return $curlOptions;
    }
}
