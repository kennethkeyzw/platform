<?php declare(strict_types=1);

namespace Shopware\Api\Order\Collection;

use Shopware\Api\Language\Collection\LanguageBasicCollection;
use Shopware\Api\Order\Struct\OrderStateTranslationDetailStruct;

class OrderStateTranslationDetailCollection extends OrderStateTranslationBasicCollection
{
    /**
     * @var OrderStateTranslationDetailStruct[]
     */
    protected $elements = [];

    public function getOrderStates(): OrderStateBasicCollection
    {
        return new OrderStateBasicCollection(
            $this->fmap(function (OrderStateTranslationDetailStruct $orderStateTranslation) {
                return $orderStateTranslation->getOrderState();
            })
        );
    }

    public function getLanguages(): LanguageBasicCollection
    {
        return new LanguageBasicCollection(
            $this->fmap(function (OrderStateTranslationDetailStruct $orderStateTranslation) {
                return $orderStateTranslation->getLanguage();
            })
        );
    }

    protected function getExpectedClass(): string
    {
        return OrderStateTranslationDetailStruct::class;
    }
}