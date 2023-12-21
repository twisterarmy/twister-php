# twister-php

PHP 8 / Composer Tools for Twister API

## Install

`composer require twisterarmy/twister`

## Features

### Client

Twister client communication toolkit

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

### RSS

Useful to create twister news bot

#### Methods

* feed

#### Feed

Read remote URL and convert response to formatted twister messages

```
$array = \Twisterarmy\Twister\Rss::feed('url');
```

##### Request

* `url` - feed address
* `format` - `{title} {link}` by default
  + `{nl}` - new line
  + `{title}` - item title
  + `{link}` - target link
* `length` - `256` by default
* `errors` - array of errors

##### Response

```
[
  time:    int,
  message: string
],
...
```