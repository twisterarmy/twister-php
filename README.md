# twister-php

PHP 8 / Composer Library for Twister P2P

## Install

`composer require twisterarmy/twister`

## Features

### Client

Twister client communication toolkit

```
$client = new \Twisterarmy\Twister\Client('http', 'localhost', 28332, 'user', 'pwd');

var_dump(
    $client->getPosts(
      [
        'twisterarmy'
      ]
    )
);
```

#### Methods

Currently not documented, please visit src/Client.php for details

* importWallet
* getBlockHash
* getBlock
* getPosts
* follow
* unFollow
* getFollowing
* getDHTProfileRevisions
* getDHTAvatarRevisions
* putDHT
* createWalletUser
* sendNewUserTransaction
* newPostMessage
* newRetwistMessage

### Tools

#### RSS

Useful to create twister news bot

##### Feed

Read remote URL and convert response to formatted twister messages

```
$array = \Twisterarmy\Twister\Tools\Rss::feed('url');
```

###### Request

* `url` - feed address
* `format` - `{title} {link}` by default
  + `{nl}` - new line
  + `{title}` - item title
  + `{link}` - target link
* `length` - `256` by default
* `errors` - array of errors

###### Response

```
[
  time:    int,
  message: string
],
...
```