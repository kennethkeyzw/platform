<?php declare(strict_types=1);

namespace Shopware\Core\Content\Media\Aggregate\MediaFolderTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class MediaFolderTranslationCollection extends EntityCollection
{
    public function get(string $id): ? MediaFolderTranslationEntity
    {
        return parent::get($id);
    }

    public function current(): MediaFolderTranslationEntity
    {
        return parent::current();
    }

    protected function getExpectedClass(): string
    {
        return MediaFolderTranslationEntity::class;
    }
}