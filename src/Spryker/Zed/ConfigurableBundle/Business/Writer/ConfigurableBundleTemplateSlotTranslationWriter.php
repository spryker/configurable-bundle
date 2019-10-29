<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Writer;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface;

class ConfigurableBundleTemplateSlotTranslationWriter implements ConfigurableBundleTemplateSlotTranslationWriterInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(ConfigurableBundleToGlossaryFacadeInterface $glossaryFacade)
    {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return void
     */
    public function saveTranslations(ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer): void
    {
        $configurableBundleTemplateSlotTransfer->requireName();
        $translationKey = $configurableBundleTemplateSlotTransfer->getName();

        if (!$this->glossaryFacade->hasKey($translationKey)) {
            $this->glossaryFacade->createKey($translationKey);
        }

        foreach ($configurableBundleTemplateSlotTransfer->getTranslations() as $configurableBundleTemplateSlotTranslationTransfer) {
            $this->persistNameTranslation($configurableBundleTemplateSlotTranslationTransfer, $translationKey);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTranslationTransfer $configurableBundleTemplateSlotTranslationTransfer
     * @param string $translationKey
     *
     * @return void
     */
    protected function persistNameTranslation(
        ConfigurableBundleTemplateSlotTranslationTransfer $configurableBundleTemplateSlotTranslationTransfer,
        string $translationKey
    ): void {
        $configurableBundleTemplateSlotTranslationTransfer
            ->requireName()
            ->requireLocale();

        $this->persistTranslation(
            $translationKey,
            $configurableBundleTemplateSlotTranslationTransfer->getName(),
            $configurableBundleTemplateSlotTranslationTransfer->getLocale()
        );
    }

    /**
     * @param string $translationKey
     * @param string $translationValue
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function persistTranslation(string $translationKey, string $translationValue, LocaleTransfer $localeTransfer): void
    {
        if (!$this->glossaryFacade->hasTranslation($translationKey, $localeTransfer)) {
            $this->glossaryFacade->createTranslation($translationKey, $localeTransfer, $translationValue);

            return;
        }

        $this->glossaryFacade->updateTranslation($translationKey, $localeTransfer, $translationValue);
    }
}
