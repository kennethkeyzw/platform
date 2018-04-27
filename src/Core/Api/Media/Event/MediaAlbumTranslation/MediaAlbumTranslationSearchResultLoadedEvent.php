<?php declare(strict_types=1);

namespace Shopware\Api\Media\Event\MediaAlbumTranslation;

use Shopware\Api\Media\Struct\MediaAlbumTranslationSearchResult;
use Shopware\Context\Struct\ApplicationContext;
use Shopware\Framework\Event\NestedEvent;

class MediaAlbumTranslationSearchResultLoadedEvent extends NestedEvent
{
    public const NAME = 'media_album_translation.search.result.loaded';

    /**
     * @var MediaAlbumTranslationSearchResult
     */
    protected $result;

    public function __construct(MediaAlbumTranslationSearchResult $result)
    {
        $this->result = $result;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): ApplicationContext
    {
        return $this->result->getContext();
    }
}