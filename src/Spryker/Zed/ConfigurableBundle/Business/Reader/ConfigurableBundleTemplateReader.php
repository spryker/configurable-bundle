<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Reader;

use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpanderInterface;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface;

class ConfigurableBundleTemplateReader implements ConfigurableBundleTemplateReaderInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface
     */
    protected $configurableBundleRepository;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpanderInterface
     */
    protected $configurableBundleTranslationExpander;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface $configurableBundleRepository
     * @param \Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpanderInterface $configurableBundleTranslationExpander
     */
    public function __construct(
        ConfigurableBundleRepositoryInterface $configurableBundleRepository,
        ConfigurableBundleTranslationExpanderInterface $configurableBundleTranslationExpander
    ) {
        $this->configurableBundleRepository = $configurableBundleRepository;
        $this->configurableBundleTranslationExpander = $configurableBundleTranslationExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer|null
     */
    public function findConfigurableBundleTemplate(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ?ConfigurableBundleTemplateTransfer {
        $configurableBundleTemplateTransfer = $this->configurableBundleRepository->findConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);

        if (!$configurableBundleTemplateTransfer) {
            return null;
        }

        return $this->configurableBundleTranslationExpander->expandConfigurableBundleTemplateWithTranslations(
            $configurableBundleTemplateTransfer,
            $configurableBundleTemplateFilterTransfer->getTranslationLocales()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer[]
     */
    public function getConfigurableBundleTemplateCollection(ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer): array
    {
        $configurableBundleTemplateTransfers = [];

        foreach ($this->configurableBundleRepository->getConfigurableBundleTemplateCollection($configurableBundleTemplateFilterTransfer) as $configurableBundleTemplateTransfer) {
            $configurableBundleTemplateTransfers[] = $this->configurableBundleTranslationExpander->expandConfigurableBundleTemplateWithTranslations(
                $configurableBundleTemplateTransfer,
                $configurableBundleTemplateFilterTransfer->getTranslationLocales()
            );
        }

        return $configurableBundleTemplateTransfers;
    }
}
