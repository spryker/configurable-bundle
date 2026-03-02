<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Persistence;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlot;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundlePersistenceFactory getFactory()
 */
class ConfigurableBundleEntityManager extends AbstractEntityManager implements ConfigurableBundleEntityManagerInterface
{
    public function createConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateTransfer {
        $configurableBundleTemplateEntity = $this->getFactory()
            ->createConfigurableBundleMapper()
            ->mapTemplateTransferToTemplateEntity($configurableBundleTemplateTransfer, new SpyConfigurableBundleTemplate());

        $configurableBundleTemplateEntity->save();

        $configurableBundleTemplateTransfer->setIdConfigurableBundleTemplate(
            $configurableBundleTemplateEntity->getIdConfigurableBundleTemplate(),
        );

        return $configurableBundleTemplateTransfer;
    }

    public function updateConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateTransfer {
        $configurableBundleTemplateEntity = $this->getFactory()
            ->getConfigurableBundleTemplatePropelQuery()
            ->filterByIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
            ->findOne();

        $configurableBundleTemplateEntity = $this->getFactory()
            ->createConfigurableBundleMapper()
            ->mapTemplateTransferToTemplateEntity($configurableBundleTemplateTransfer, $configurableBundleTemplateEntity);

        $configurableBundleTemplateEntity->save();

        return $configurableBundleTemplateTransfer;
    }

    public function createConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleTemplateSlotTransfer {
        $configurableBundleTemplateSlotEntity = $this->getFactory()
            ->createConfigurableBundleMapper()
            ->mapTemplateSlotTransferToTemplateSlotEntity($configurableBundleTemplateSlotTransfer, new SpyConfigurableBundleTemplateSlot());

        $configurableBundleTemplateSlotEntity->save();

        $configurableBundleTemplateSlotTransfer->setIdConfigurableBundleTemplateSlot(
            $configurableBundleTemplateSlotEntity->getIdConfigurableBundleTemplateSlot(),
        );

        return $configurableBundleTemplateSlotTransfer;
    }

    public function updateConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleTemplateSlotTransfer {
        $configurableBundleTemplateSlotEntity = $this->getFactory()
            ->getConfigurableBundleTemplateSlotPropelQuery()
            ->filterByIdConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot())
            ->findOne();

        $configurableBundleTemplateSlotEntity = $this->getFactory()
            ->createConfigurableBundleMapper()
            ->mapTemplateSlotTransferToTemplateSlotEntity($configurableBundleTemplateSlotTransfer, $configurableBundleTemplateSlotEntity);

        $configurableBundleTemplateSlotEntity->save();

        return $configurableBundleTemplateSlotTransfer;
    }

    public function deleteConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): void
    {
        $configurableBundleTemplateEntity = $this->getFactory()
            ->getConfigurableBundleTemplatePropelQuery()
            ->filterByIdConfigurableBundleTemplate($idConfigurableBundleTemplate)
            ->findOne();

        if (!$configurableBundleTemplateEntity) {
            return;
        }

        $configurableBundleTemplateEntity->delete();
    }

    public function deleteConfigurableBundleTemplateSlotById(int $idConfigurableBundleTemplateSlot): void
    {
        $configurableBundleTemplateSlotEntity = $this->getFactory()
            ->getConfigurableBundleTemplateSlotPropelQuery()
            ->filterByIdConfigurableBundleTemplateSlot($idConfigurableBundleTemplateSlot)
            ->findOne();

        if (!$configurableBundleTemplateSlotEntity) {
            return;
        }

        $configurableBundleTemplateSlotEntity->delete();
    }

    public function deleteConfigurableBundleTemplateSlotsByIdTemplate(int $idConfigurableBundleTemplate): void
    {
        $configurableBundleTemplateSlotEntities = $this->getFactory()
            ->getConfigurableBundleTemplateSlotPropelQuery()
            ->filterByFkConfigurableBundleTemplate($idConfigurableBundleTemplate)
            ->find();

        foreach ($configurableBundleTemplateSlotEntities as $configurableBundleTemplateSlotEntity) {
            $configurableBundleTemplateSlotEntity->delete();
        }
    }
}
