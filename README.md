# twister-php

PHP 8 / Composer Tools for Twister API

## Install

`composer require twisterarmy/twister`

## Features

### Client

Twister client communication toolkit

### RSS

Useful to create twister news bot

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