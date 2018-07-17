<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Order\Aggregate\OrderStateTranslation;

use Shopware\Core\Checkout\Order\Aggregate\OrderState\OrderStateDefinition;
use Shopware\Core\Framework\ORM\EntityDefinition;
use Shopware\Core\Framework\ORM\Field\FkField;
use Shopware\Core\Framework\ORM\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\ORM\Field\ReferenceVersionField;
use Shopware\Core\Framework\ORM\Field\StringField;
use Shopware\Core\Framework\ORM\FieldCollection;
use Shopware\Core\Framework\ORM\Write\Flag\PrimaryKey;
use Shopware\Core\Framework\ORM\Write\Flag\Required;
use Shopware\Core\System\Language\LanguageDefinition;

class OrderStateTranslationDefinition extends EntityDefinition
{
    public static function getEntityName(): string
    {
        return 'order_state_translation';
    }

    public static function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new FkField('order_state_id', 'orderStateId', OrderStateDefinition::class))->setFlags(new PrimaryKey(), new Required()),
            (new ReferenceVersionField(OrderStateDefinition::class))->setFlags(new PrimaryKey(), new Required()),
            (new FkField('language_id', 'languageId', LanguageDefinition::class))->setFlags(new PrimaryKey(), new Required()),
            (new StringField('description', 'description'))->setFlags(new Required()),
            new ManyToOneAssociationField('orderState', 'order_state_id', OrderStateDefinition::class, false),
            new ManyToOneAssociationField('language', 'language_id', LanguageDefinition::class, false),
        ]);
    }

    public static function getCollectionClass(): string
    {
        return OrderStateTranslationCollection::class;
    }

    public static function getStructClass(): string
    {
        return OrderStateTranslationStruct::class;
    }
}