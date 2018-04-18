<?php

namespace Shopware\Api\Entity\Dbal\FieldResolver;

use Shopware\Api\Entity\Dbal\EntityDefinitionQueryHelper;
use Shopware\Api\Entity\Dbal\QueryBuilder;
use Shopware\Api\Entity\EntityDefinition;
use Shopware\Api\Entity\Field\Field;
use Shopware\Api\Entity\Field\OneToManyAssociationField;
use Shopware\Api\Entity\Write\Flag\CascadeDelete;
use Shopware\Context\Struct\ApplicationContext;

class OneToManyAssociationFieldResolver implements FieldResolverInterface
{
    public function resolve(
        string $definition,
        string $root,
        Field $field,
        QueryBuilder $query,
        ApplicationContext $context,
        EntityDefinitionQueryHelper $queryHelper,
        bool $raw
    ): void {
        if (!$field instanceof OneToManyAssociationField) {
            return;
        }

        $query->addState(EntityDefinitionQueryHelper::HAS_TO_MANY_JOIN);

        /** @var EntityDefinition|string $reference */
        $reference = $field->getReferenceClass();

        $table = $reference::getEntityName();

        $alias = $root . '.' . $field->getPropertyName();
        if ($query->hasState($alias)) {
            return;
        }
        $query->addState($alias);

        $versionJoin = '';
        /** @var string|EntityDefinition $definition */
        if ($definition::isVersionAware() && $field->is(CascadeDelete::class)) {
            $versionJoin = ' AND #root#.version_id = #alias#.version_id';
        }

        $catalogJoinCondition = '';
        if ($definition::isCatalogAware() && $reference::isCatalogAware()) {
            $catalogJoinCondition = ' AND #root#.catalog_id = #alias#.catalog_id';
        }

        $query->leftJoin(
            EntityDefinitionQueryHelper::escape($root),
            EntityDefinitionQueryHelper::escape($table),
            EntityDefinitionQueryHelper::escape($alias),
            str_replace(
                ['#root#', '#source_column#', '#alias#', '#reference_column#'],
                [
                    EntityDefinitionQueryHelper::escape($root),
                    EntityDefinitionQueryHelper::escape($field->getLocalField()),
                    EntityDefinitionQueryHelper::escape($alias),
                    EntityDefinitionQueryHelper::escape($field->getReferenceField()),
                ],
                '#root#.#source_column# = #alias#.#reference_column#' . $versionJoin . $catalogJoinCondition
            )
        );

        if ($definition === $reference) {
            return;
        }

        if (!$reference::getParentPropertyName()) {
            return;
        }

        $parent = $reference::getFields()->get($reference::getParentPropertyName());
        $queryHelper->resolveField($parent, $reference, $alias, $query, $context);
    }
}