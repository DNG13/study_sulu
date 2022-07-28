<?php

namespace App\Twig;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class YoutubeTwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('playlistItemsListByPlaylistId', fn(string $channelId) => $this->playlistItemsListByPlaylistId($channelId)),
        ];
    }

    public function playlistItemsListByPlaylistId(string $channelId): array
    {
        return [
            'video1',
            'video2',
            $channelId
        ];
    }
}