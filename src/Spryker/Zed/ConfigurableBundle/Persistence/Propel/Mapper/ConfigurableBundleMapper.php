<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlot;
use Orm\Zed\Locale\Persistence\SpyLocale;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Propel\Runtime\Collection\Collection;

class ConfigurableBundleMapper
{
    public function mapTemplateEntityCollectionToTemplateTransferCollection(
        Collection $configurableBundleTemplateEntities
    ): ConfigurableBundleTemplateCollectionTransfer {
        $configurableBundleTemplateCollectionTransfer = new ConfigurableBundleTemplateCollectionTransfer();

        foreach ($configurableBundleTemplateEntities as $configurableBundleTemplateEntity) {
            $configurableBundleTemplateTransfer = $this->mapTemplateEntityToTemplateTransfer(
                $configurableBundleTemplateEntity,
                new ConfigurableBundleTemplateTransfer(),
            );

            $configurableBundleTemplateCollectionTransfer->addConfigurableBundleTemplate($configurableBundleTemplateTransfer);
        }

        return $configurableBundleTemplateCollectionTransfer;
    }

    public function mapTemplateSlotEntityCollectionToTemplateSlotTransferCollection(
        Collection $configurableBundleTemplateSlotEntities
    ): ConfigurableBundleTemplateSlotCollectionTransfer {
        $configurableBundleTemplateSlotCollectionTransfer = new ConfigurableBundleTemplateSlotCollectionTransfer();

        foreach ($configurableBundleTemplateSlotEntities as $configurableBundleTemplateSlotEntity) {
            $configurableBundleTemplateSlotTransfer = $this->mapTemplateSlotEntityToTemplateSlotTransfer(
                $configurableBundleTemplateSlotEntity,
                new ConfigurableBundleTemplateSlotTransfer(),
            );

            $configurableBundleTemplateSlotCollectionTransfer->addConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);
        }

        return $configurableBundleTemplateSlotCollectionTransfer;
    }

    public function mapTemplateTransferToTemplateEntity(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        SpyConfigurableBundleTemplate $configurableBundleTemplateEntity
    ): SpyConfigurableBundleTemplate {
        $configurableBundleTemplateEntity->fromArray($configurableBundleTemplateTransfer->modifiedToArray());

        return $configurableBundleTemplateEntity;
    }

    public function mapTemplateEntityToTemplateTransfer(
        SpyConfigurableBundleTemplate $configurableBundleTemplateEntity,
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateTransfer {
        return $configurableBundleTemplateTransfer->fromArray($configurableBundleTemplateEntity->toArray(), true);
    }

    public function mapTemplateSlotTransferToTemplateSlotEntity(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer,
        SpyConfigurableBundleTemplateSlot $configurableBundleTemplateSlotEntity
    ): SpyConfigurableBundleTemplateSlot {
        $configurableBundleTemplateSlotEntity->fromArray($configurableBundleTemplateSlotTransfer->modifiedToArray());

        $configurableBundleTemplateSlotEntity
            ->setFkProductList($configurableBundleTemplateSlotTransfer->getProductList()->getIdProductList());

        return $configurableBundleTemplateSlotEntity;
    }

    public function mapTemplateSlotEntityToTemplateSlotTransfer(
        SpyConfigurableBundleTemplateSlot $configurableBundleTemplateSlotEntity,
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleTemplateSlotTransfer {
        $configurableBundleTemplateSlotTransfer = $configurableBundleTemplateSlotTransfer
            ->fromArray($configurableBundleTemplateSlotEntity->toArray(), true);

        $configurableBundleTemplateTransfer = $this->mapTemplateEntityToTemplateTransfer(
            $configurableBundleTemplateSlotEntity->getSpyConfigurableBundleTemplate(),
            new ConfigurableBundleTemplateTransfer(),
        );

        $productListTransfer = (new ProductListTransfer())
            ->setIdProductList($configurableBundleTemplateSlotEntity->getFkProductList());

        return $configurableBundleTemplateSlotTransfer
            ->setConfigurableBundleTemplate($configurableBundleTemplateTransfer)
            ->setProductList($productListTransfer);
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $productImageSetEntities
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function mapProductImageSetEntityCollectionToProductImageSetTransfers(Collection $productImageSetEntities): array
    {
        $productImageSetTransfers = [];

        foreach ($productImageSetEntities as $productImageSetEntity) {
            $productImageSetTransfer = $this->mapProductImageSetEntityToProductImageSetTransfer(
                $productImageSetEntity,
                new ProductImageSetTransfer(),
            );

            $productImageSetTransfers[] = $productImageSetTransfer;
        }

        return $productImageSetTransfers;
    }

    public function mapProductImageSetEntityToProductImageSetTransfer(
        SpyProductImageSet $productImageSetEntity,
        ProductImageSetTransfer $productImageSetTransfer
    ): ProductImageSetTransfer {
        $productImageSetTransfer = $productImageSetTransfer
            ->fromArray($productImageSetEntity->toArray(), true);

        if ($productImageSetEntity->getSpyLocale()) {
            $productImageSetTransfer->setLocale($this->mapLocaleEntityToLocaleTransfer($productImageSetEntity->getSpyLocale(), new LocaleTransfer()));
        }

        return $productImageSetTransfer
            ->setProductImages(new ArrayObject($this->mapProductImageSetEntityToProductImageTransfers($productImageSetEntity)));
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet $productImageSetEntity
     *
     * @return array<\Generated\Shared\Transfer\ProductImageTransfer>
     */
    protected function mapProductImageSetEntityToProductImageTransfers(SpyProductImageSet $productImageSetEntity): array
    {
        $productImageTransfers = [];

        foreach ($productImageSetEntity->getSpyProductImageSetToProductImages() as $productImageSetToProductImageEntity) {
            $productImageTransfer = $this->mapProductImageEntityToProductImageTransfer(
                $productImageSetToProductImageEntity->getSpyProductImage(),
                new ProductImageTransfer(),
            );

            $productImageTransfer
                ->setIdProductImageSetToProductImage($productImageSetToProductImageEntity->getIdProductImageSetToProductImage())
                ->setSortOrder($productImageSetToProductImageEntity->getSortOrder());

            $productImageTransfers[] = $productImageTransfer;
        }

        return $productImageTransfers;
    }

    protected function mapProductImageEntityToProductImageTransfer(
        SpyProductImage $productImageEntity,
        ProductImageTransfer $productImageTransfer
    ): ProductImageTransfer {
        $productImageTransfer = $productImageTransfer->fromArray($productImageEntity->toArray(), true);

        return $productImageTransfer;
    }

    protected function mapLocaleEntityToLocaleTransfer(SpyLocale $localeEntity, LocaleTransfer $localeTransfer): LocaleTransfer
    {
        $localeTransfer = $localeTransfer->fromArray($localeEntity->toArray(), true);

        return $localeTransfer;
    }
}
