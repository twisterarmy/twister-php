# twister-php

PHP 8 / Composer Tools for Twister API

## Install

`composer require twisterarmy/twister`

## Features

### Client

Twister client communication toolkit

### RSS

RSS toolkit for twister

###### Init

```
$rss = new \Twisterarmy\Twister\Rss();
```

#### Format

##### Time

Convert RSS time to datetime format ([Documentation](https://www.php.net/manual/en/datetime.format.php))

###### Example

```
$rss->setTimeFormat('c');
```

##### Message

Convert RSS fields to twister message format, `{title} {link}` by default

###### Mask

* `{time}` - formatted time string by `setTimeFormat`, `U` by default
* `{link}` - target link
* `{title}` - item title

###### Example

```
$rss->setMessageFormat('{title} {link}');
```

#### Length

Twister protocol accept messages with 256 chars max but you can define another value.

Formatted messages greater this value will be skipped from feed.

##### Example

```
$rss->setLength(256);
```

#### Feed

Get formatted feed array

##### Example

```
$feed = $rss->get(url);
```
