<?php

declare(strict_types=1);

namespace Twisterarmy\Twister;

class Rss
{
    private int $_length = 256;

    private string $_format = '{title} {link}';

    public function setLength(int $value)
    {
        $this->_length = $value;
    }

    public function setFormat(string $value)
    {
        $this->_format = $value;
    }

    public function get(string $url, array &$error = []): ?array
    {
        if (empty($url))
        {
            $error[] = _('RSS address required!');

            return null;
        }

        if (false === (bool) filter_var($url, FILTER_VALIDATE_URL))
        {
            $error[] = _('Valid RSS address required!');

            return null;
        }

        if (!$xml = simplexml_load_file($url))
        {
            $error[] = sprintf(
                'Could not open RSS feed "%s"',
                $url
            );

            return null;
        }

        if (empty($xml->channel))
        {
            $error[] = _('RSS channel not found!');

            return null;
        }

        if (empty($xml->channel->item))
        {
            $error[] = _('RSS channel item not found!');

            return null;
        }

        $messages = [];

        foreach ($xml->channel->item as $item)
        {
            if (empty($item->link))
            {
                $error[] = _('RSS channel item does not contain link!');

                continue;
            }

            if (empty($item->title))
            {
                $error[] = _('RSS channel item does not contain title!');

                continue;
            }

            $link = trim(
                (string) $item->link
            );

            $title = trim(
                strip_tags(
                    html_entity_decode(
                        (string) $item->title
                    )
                )
            );

            $message = str_replace(
                [
                    '{link}',
                    '{title}',
                    // ..
                ],
                [
                    $link,
                    $title,
                    // ..
                ],
                $this->_format
            );

            if (mb_strlen($message) > $this->_length)
            {
                $error[] = _('Message does not correspond twister protocol length!');

                continue;
            }

            $messages[mb_strlen($message)] = $message;
        }

        return $messages;
    }
}