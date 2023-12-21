<?php

declare(strict_types=1);

namespace Twisterarmy\Twister\Tools;

class Rss
{
    public static function feed(
        string $url,
        string $format = '{title}{nl}{link}',
        int    $length = 256,
        array &$error  = []
    ): ?array
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

        $feed = [];

        foreach ($xml->channel->item as $item)
        {
            if (empty($item->pubDate) || !strtotime((string) $item->pubDate))
            {
                $error[] = _('RSS channel item does not contain valid pubDate!');

                continue;
            }

            if (empty($item->link) || false === (bool) filter_var((string) $item->link, FILTER_VALIDATE_URL))
            {
                $error[] = _('RSS channel item does not contain valid link!');

                continue;
            }

            if (empty($item->title))
            {
                $error[] = _('RSS channel item does not contain title!');

                continue;
            }

            $pubDate = trim(
                (string) $item->pubDate
            );

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
                    '{nl}',
                    '{link}',
                    '{title}',
                    // ..
                ],
                [
                    PHP_EOL,
                    $link,
                    $title,
                    // ..
                ],
                $format
            );

            if (mb_strlen($message) > $length)
            {
                $error[] = _('Message does not correspond twister protocol length!');

                continue;
            }

            $feed[] =
            [
                'time'    => strtotime($pubDate),
                'message' => $message
            ];
        }

        array_multisort(
            array_column(
                $feed,
                'time'
            ),
            SORT_ASC,
            $feed
        );

        return $feed;
    }
}