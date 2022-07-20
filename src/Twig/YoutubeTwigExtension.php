<?php

namespace App\Twig;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class YoutubeTwigExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('playlistItemsListByPlaylistId', [$this, 'playlistItemsListByPlaylistId']),
        ];
    }

    public function playlistItemsListByPlaylistId(string $channelId)
    {
        return [
            'video1',
            'video2',
            $channelId
        ];
    }
}