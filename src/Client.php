<?php

declare(strict_types=1);

namespace Twisterarmy\Twister;

class Client
{
    private $_curl;

    private string $_url;

    public function __construct(
        string $protocol,
        string $host,
        int    $port,
        string $username,
        string $password
    )
    {
        $this->_url = sprintf(
            '%s://%s:%s',
            $protocol,
            $host,
            $port
        );

        $this->_curl = curl_init();

        curl_setopt_array(
            $this->_curl,
            [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_FRESH_CONNECT  => true,
                CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
                CURLOPT_USERPWD        => $username . ':' . $password,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/plain',
                ],
            ]
        );
    }

    public function __destruct()
    {
        curl_close(
            $this->_curl
        );
    }

    private function _exec(string $uri, string $method = 'POST', array $data = [], array &$errors = []): ?array
    {
        curl_setopt(
            $this->_curl,
            CURLOPT_URL,
            $this->_url . $uri
        );

        curl_setopt(
            $this->_curl,
            CURLOPT_CUSTOMREQUEST,
            $method
        );

        if ($method == 'POST' && $data)
        {
            curl_setopt(
                $this->_curl,
                CURLOPT_POSTFIELDS,
                json_encode(
                    $data
                )
            );
        }

        if ($response = curl_exec($this->_curl))
        {
            if ($result = json_decode($response, true))
            {
                return $result;
            }
        }

        else
        {
            $errors[] = curl_error(
                $this->_curl
            );
        }

        return null;
    }

    public function importWallet(string $userName, string $userPrivateKey, array &$errors = []): ?array
    {
        return $this->_exec(
            '/',
            'POST',
            [
                'jsonrpc' => '2.0',
                'method'  => 'importprivkey',
                'params'  =>
                [
                    $userPrivateKey,
                    $userName
                ],
                'id' => time()
            ],
            $errors
        );
    }

    public function getBlockHash(int $number, array &$errors = []): ?array
    {
        return $this->_exec(
            '/',
            'POST',
            [
                'jsonrpc' => '2.0',
                'method'  => 'getblockhash',
                'params'  =>
                [
                    $number
                ],
                'id' => time()
            ],
            $errors
        );
    }

    public function getBlock(string $hash, array &$errors = []): ?array
    {
        return $this->_exec(
            '/',
            'POST',
            [
                'jsonrpc' => '2.0',
                'method'  => 'getblock',
                'params'  =>
                [
                    $hash
                ],
                'id' => time()
            ],
            $errors
        );
    }

    public function getPosts(array $userNames, int $limit, array &$errors = []): ?array
    {
        $data = [];

        foreach ($userNames as $userName)
        {
            $data[] =
            [
                'username' => $userName
            ];
        }

        return $this->_exec(
            '/',
            'POST',
            [
                'jsonrpc' => '2.0',
                'method'  => 'getposts',
                'params'  =>
                [
                    $limit,
                    $data
                ],
                'id' => time()
            ],
            $errors
        );
    }

    public function follow(string $userName, array $userNames, array &$errors = []): ?array
    {
        return $this->_exec(
            '/',
            'POST',
            [
                'jsonrpc' => '2.0',
                'method'  => 'follow',
                'params'  =>
                [
                    $userName,
                    $userNames
                ],
                'id' => time()
            ],
            $errors
        );
    }

    public function unFollow(string $userName, array $userNames, array &$errors = []): ?array
    {
        return $this->_exec(
            '/',
            'POST',
            [
                'jsonrpc' => '2.0',
                'method'  => 'unfollow',
                'params'  =>
                [
                    $userName,
                    $userNames
                ],
                'id' => time()
            ],
            $errors
        );
    }

    public function getFollowing(string $userName, array &$errors = []): ?array
    {
        return $this->_exec(
            '/',
            'POST',
            [
                'jsonrpc' => '2.0',
                'method'  => 'getfollowing',
                'params'  =>
                [
                    $userName
                ],
                'id' => time()
            ],
            $errors
        );
    }

    public function getDHTProfileRevisions(string $userName, array &$errors = []): ?array
    {
        return $this->_exec(
            '/',
            'POST',
            [
                'jsonrpc' => '2.0',
                'method'  => 'dhtget',
                'params'  =>
                [
                    $userName,
                    'profile',
                    's'
                ],
                'id' => time()
            ],
            $errors
        );
    }

    public function getDHTAvatarRevisions(string $userName, array &$errors = []): ?array
    {
        return $this->_exec(
            '/',
            'POST',
            [
                'jsonrpc' => '2.0',
                'method'  => 'dhtget',
                'params'  =>
                [
                    $userName,
                    'avatar',
                    's'
                ],
                'id' => time()
            ],
            $errors
        );
    }

    public function putDHT(string $peerAlias, string $command, string $flag /*s(ingle)/m(ulti) mixed*/, $value, string $sig_user, int $seq, array &$errors = []): ?array
    {
        return $this->_exec(
            '/',
            'POST',
            [
                'jsonrpc' => '2.0',
                'method'  => 'dhtput',
                'params'  => [
                $peerAlias,
                $command,
                $flag,
                $value,
                $sig_user,
                $seq,
                ],
                'id' => time()
            ],
            $errors
        );
    }

    public function createWalletUser(string $userName, array &$errors = []): ?array
    {
        return $this->_exec(
            '/',
            'POST',
            [
                'jsonrpc' => '2.0',
                'method'  => 'createwalletuser',
                'params'  =>
                [
                    $userName
                ],
                'id' => time()
            ],
            $errors
        );
    }

    public function sendNewUserTransaction(string $userName, array &$errors = []): ?array
    {
        return $this->_exec(
            '/',
            'POST',
            [
                'jsonrpc' => '2.0',
                'method'  => 'sendnewusertransaction',
                'params'  =>
                [
                    $userName
                ],
                'id' => time()
            ],
            $errors
        );
    }

    public function newPostMessage(string $userName, int $k, string $message, array &$errors = []): ?array
    {
        return $this->_exec(
            '/',
            'POST',
            [
                'jsonrpc' => '2.0',
                'method'  => 'newpostmsg',
                'params'  =>
                [
                    $userName,
                    $k,
                    $message
                ],
                'id' => time()
            ],
            $errors
        );
    }

    public function newRetwistMessage(string $userName, int $k, string $sigUserPost, array $userPost, string $comment, array &$errors = []): ?array
    {
        return $this->_exec(
            '/',
            'POST',
            [
                'jsonrpc' => '2.0',
                'method'  => 'newrtmsg',
                'params'  =>
                [
                    $userName,
                    $k,
                    [
                        'sig_userpost' => $sigUserPost,
                        'userpost'     => $userPost,
                    ],
                    $comment
                ],
                'id' => time()
            ],
            $errors
        );
    }
}