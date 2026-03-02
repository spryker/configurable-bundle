<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTemplateSlotProductListExpanderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpanderInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToLocaleFacadeInterface;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface;

class ConfigurableBundleTemplateSlotReader implements ConfigurableBundleTemplateSlotReaderInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_NOT_EXISTS = 'configurable_bundle.slot.validation.error.not_exists';

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface
     */
    protected $configurableBundleRepository;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpanderInterface
     */
    protected $configurableBundleTranslationExpander;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTemplateSlotProductListExpanderInterface
     */
    protected $configurableBundleTemplateSlotProductListExpander;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToLocaleFacadeInterface
     */
    protected $localeFacade;

    public function __construct(
        ConfigurableBundleRepositoryInterface $configurableBundleRepository,
        ConfigurableBundleTranslationExpanderInterface $configurableBundleTranslationExpander,
        ConfigurableBundleTemplateSlotProductListExpanderInterface $configurableBundleTemplateSlotProductListExpander,
        ConfigurableBundleToLocaleFacadeInterface $localeFacade
    ) {
        $this->configurableBundleRepository = $configurableBundleRepository;
        $this->configurableBundleTranslationExpander = $configurableBundleTranslationExpander;
        $this->configurableBundleTemplateSlotProductListExpander = $configurableBundleTemplateSlotProductListExpander;
        $this->localeFacade = $localeFacade;
    }

    public function getConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ConfigurableBundleTemplateSlotResponseTransfer {
        $configurableBundleTemplateSlotTransfer = $this->configurableBundleRepository
            ->findConfigurableBundleTemplateSlot($configurableBundleTemplateSlotFilterTransfer);

        if (!$configurableBundleTemplateSlotTransfer) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_NOT_EXISTS);
        }

        $configurableBundleTemplateSlotTransfer = $this->expandConfigurableBundleTemplateSlot(
            $configurableBundleTemplateSlotTransfer,
            $configurableBundleTemplateSlotFilterTransfer,
        );

        return (new ConfigurableBundleTemplateSlotResponseTransfer())
            ->setConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer)
            ->setIsSuccessful(true);
    }

    public function getConfigurableBundleTemplateSlotCollection(
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ConfigurableBundleTemplateSlotCollectionTransfer {
        $configurableBundleTemplateSlotCollectionTransfer = $this->configurableBundleRepository
            ->getConfigurableBundleTemplateSlotCollection($configurableBundleTemplateSlotFilterTransfer);

        foreach ($configurableBundleTemplateSlotCollectionTransfer->getConfigurableBundleTemplateSlots() as $configurableBundleTemplateSlotTransfer) {
            $this->expandConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer, $configurableBundleTemplateSlotFilterTransfer);
        }

        return $configurableBundleTemplateSlotCollectionTransfer;
    }

    public function getConfigurableBundleTemplateSlotById(int $idConfigurableBundleTemplateSlot): ConfigurableBundleTemplateSlotResponseTransfer
    {
        $configurableBundleTemplateSlotFilterTransfer = (new ConfigurableBundleTemplateSlotFilterTransfer())
            ->setIdConfigurableBundleTemplateSlot($idConfigurableBundleTemplateSlot)
            ->setTranslationLocales(new ArrayObject([$this->getDefaultLocale()]));

        return $this->getConfigurableBundleTemplateSlot($configurableBundleTemplateSlotFilterTransfer);
    }

    protected function expandConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer,
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ConfigurableBundleTemplateSlotTransfer {
        $configurableBundleTemplateSlotTransfer = $this->configurableBundleTemplateSlotProductListExpander
            ->expandConfigurableBundleTemplateSlotWithProductList($configurableBundleTemplateSlotTransfer);

        $configurableBundleTemplateSlotTransfer = $this->configurableBundleTranslationExpander
            ->expandConfigurableBundleTemplateSlotWithTranslations(
                $configurableBundleTemplateSlotTransfer,
                $configurableBundleTemplateSlotFilterTransfer->getTranslationLocales(),
            );

        $configurableBundleTemplateTransfer = $this->configurableBundleTranslationExpander
            ->expandConfigurableBundleTemplateWithTranslations(
                $configurableBundleTemplateSlotTransfer->getConfigurableBundleTemplate(),
                $configurableBundleTemplateSlotFilterTransfer->getTranslationLocales(),
            );

        $configurableBundleTemplateSlotTransfer->setConfigurableBundleTemplate($configurableBundleTemplateTransfer);

        return $configurableBundleTemplateSlotTransfer;
    }

    protected function getDefaultLocale(): LocaleTransfer
    {
        $localeTransfers = $this->localeFacade->getLocaleCollection();

        return array_shift($localeTransfers);
    }

    protected function getErrorResponse(string $message): ConfigurableBundleTemplateSlotResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message);

        return (new ConfigurableBundleTemplateSlotResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }
}
