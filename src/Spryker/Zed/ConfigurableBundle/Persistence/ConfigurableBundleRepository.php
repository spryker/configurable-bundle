<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Persistence;

use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\Map\SpyConfigurableBundleTemplateSlotTableMap;
use Orm\Zed\ConfigurableBundle\Persistence\Map\SpyConfigurableBundleTemplateTableMap;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundlePersistenceFactory getFactory()
 */
class ConfigurableBundleRepository extends AbstractRepository implements ConfigurableBundleRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer|null
     */
    public function findConfigurableBundleTemplate(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ?ConfigurableBundleTemplateTransfer {
        $configurableBundleTemplateQuery = $this->getFactory()->createConfigurableBundleTemplateQuery();
        $configurableBundleTemplateQuery = $this->setConfigurableBundleTemplateFilters(
            $configurableBundleTemplateQuery,
            $configurableBundleTemplateFilterTransfer
        );

        $configurableBundleTemplateEntity = $configurableBundleTemplateQuery->findOne();

        if (!$configurableBundleTemplateEntity) {
            return null;
        }

        return $this->getFactory()
            ->createConfigurableBundleMapper()
            ->mapTemplateEntityToTemplateTransfer($configurableBundleTemplateEntity, new ConfigurableBundleTemplateTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateCollectionTransfer
     */
    public function getConfigurableBundleTemplateCollection(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ConfigurableBundleTemplateCollectionTransfer {
        $configurableBundleTemplateQuery = $this->getFactory()->createConfigurableBundleTemplateQuery();

        $configurableBundleTemplateQuery = $this->setConfigurableBundleTemplateFilters(
            $configurableBundleTemplateQuery,
            $configurableBundleTemplateFilterTransfer
        );

        return $this->getFactory()
            ->createConfigurableBundleMapper()
            ->mapTemplateEntityCollectionToTemplateTransferCollection($configurableBundleTemplateQuery->find());
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer|null
     */
    public function findConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ?ConfigurableBundleTemplateSlotTransfer {
        $configurableBundleTemplateSlotQuery = $this->getFactory()->createConfigurableBundleTemplateSlotQuery();
        $configurableBundleTemplateSlotQuery = $this->setConfigurableBundleTemplateSlotFilters(
            $configurableBundleTemplateSlotQuery,
            $configurableBundleTemplateSlotFilterTransfer
        );

        $configurableBundleTemplateSlotEntity = $configurableBundleTemplateSlotQuery->findOne();

        if (!$configurableBundleTemplateSlotEntity) {
            return null;
        }

        return $this->getFactory()
            ->createConfigurableBundleMapper()
            ->mapConfigurableBundleTemplateSlotEntityToTransfer($configurableBundleTemplateSlotEntity, new ConfigurableBundleTemplateSlotTransfer());
    }

    /**
     * @param string[] $allowedTemplateUuids
     *
     * @return string[]
     */
    public function getActiveConfigurableBundleTemplateUuids(array $allowedTemplateUuids): array
    {
        if (empty($allowedTemplateUuids)) {
            return [];
        }

        return $this->getFactory()
            ->createConfigurableBundleTemplateQuery()
            ->filterByUuid_In($allowedTemplateUuids)
            ->filterByIsActive(true)
            ->select([SpyConfigurableBundleTemplateTableMap::COL_UUID])
            ->find()
            ->toArray();
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotCollectionTransfer
     */
    public function getConfigurableBundleTemplateSlotCollection(
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ConfigurableBundleTemplateSlotCollectionTransfer {
        $configurableBundleTemplateSlotQuery = $this->getFactory()
            ->createConfigurableBundleTemplateSlotQuery()
            ->joinWithSpyConfigurableBundleTemplate();

        $configurableBundleTemplateSlotQuery = $this->setConfigurableBundleTemplateSlotFilters(
            $configurableBundleTemplateSlotQuery,
            $configurableBundleTemplateSlotFilterTransfer
        );

        $configurableBundleTemplateSlotEntityCollection = $configurableBundleTemplateSlotQuery->find();

        if (!$configurableBundleTemplateSlotEntityCollection->count()) {
            return [];
        }

        $configurableBundleTemplateSlotTransfers = [];
        $configurableBundleMapper = $this->getFactory()->createConfigurableBundleMapper();

        foreach ($configurableBundleTemplateSlotEntityCollection as $configurableBundleTemplateSlotEntity) {
            $configurableBundleTemplateSlotTransfers[] = $configurableBundleMapper->mapConfigurableBundleTemplateSlotEntityToTransfer(
                $configurableBundleTemplateSlotEntity,
                new ConfigurableBundleTemplateSlotTransfer()
            );
        }

        return $configurableBundleTemplateSlotTransfers;
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return int
     */
    public function getProductListIdByIdConfigurableBundleTemplateSlot(int $idConfigurableBundleTemplate): int
    {
        return $this->getFactory()
            ->createConfigurableBundleTemplateSlotQuery()
            ->filterByIdConfigurableBundleTemplateSlot($idConfigurableBundleTemplate)
            ->select([SpyConfigurableBundleTemplateSlotTableMap::COL_FK_PRODUCT_LIST])
            ->findOne();
    }

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery $configurableBundleTemplateQuery
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery
     */
    protected function setConfigurableBundleTemplateFilters(
        SpyConfigurableBundleTemplateQuery $configurableBundleTemplateQuery,
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): SpyConfigurableBundleTemplateQuery {
        if ($configurableBundleTemplateFilterTransfer->getIdConfigurableBundleTemplate()) {
            $configurableBundleTemplateQuery->filterByIdConfigurableBundleTemplate(
                $configurableBundleTemplateFilterTransfer->getIdConfigurableBundleTemplate()
            );
        }

        if ($configurableBundleTemplateFilterTransfer->getConfigurableBundleTemplateIds()) {
            $configurableBundleTemplateQuery->filterByIdConfigurableBundleTemplate_In(
                $configurableBundleTemplateFilterTransfer->getConfigurableBundleTemplateIds()
            );
        }

        return $configurableBundleTemplateQuery;
    }

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery $configurableBundleTemplateSlotQuery
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery
     */
    protected function setConfigurableBundleTemplateSlotFilters(
        SpyConfigurableBundleTemplateSlotQuery $configurableBundleTemplateSlotQuery,
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): SpyConfigurableBundleTemplateSlotQuery {
        if ($configurableBundleTemplateSlotFilterTransfer->getIdProductList()) {
            $configurableBundleTemplateSlotQuery->filterByFkProductList(
                $configurableBundleTemplateSlotFilterTransfer->getIdProductList()
            );
        }

        if ($configurableBundleTemplateSlotFilterTransfer->getIdConfigurableBundleTemplateSlot()) {
            $configurableBundleTemplateSlotQuery->filterByIdConfigurableBundleTemplateSlot(
                $configurableBundleTemplateSlotFilterTransfer->getIdConfigurableBundleTemplateSlot()
            );
        }

        return $configurableBundleTemplateSlotQuery;
    }
}
