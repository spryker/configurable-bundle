<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Combiner\ProductImageSetCombinerInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToLocaleFacadeInterface;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface;

class ConfigurableBundleTemplateImageSetExpander implements ConfigurableBundleTemplateImageSetExpanderInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface
     */
    protected $configurableBundleRepository;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Combiner\ProductImageSetCombinerInterface
     */
    protected $productImageSetCombiner;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface $configurableBundleRepository
     * @param \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ConfigurableBundle\Business\Combiner\ProductImageSetCombinerInterface $productImageSetCombiner
     */
    public function __construct(
        ConfigurableBundleRepositoryInterface $configurableBundleRepository,
        ConfigurableBundleToLocaleFacadeInterface $localeFacade,
        ProductImageSetCombinerInterface $productImageSetCombiner
    ) {
        $this->configurableBundleRepository = $configurableBundleRepository;
        $this->localeFacade = $localeFacade;
        $this->productImageSetCombiner = $productImageSetCombiner;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function expandConfigurableBundleTemplateWithImageSets(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        ArrayObject $localeTransfers
    ): ConfigurableBundleTemplateTransfer {
        $configurableBundleTemplateTransfer->requireIdConfigurableBundleTemplate();

        $idConfigurableBundleTemplate = $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate();
        $localeIds = $this->extractLocaleIds($localeTransfers);

        $localizedProductImageSetTransfers = $this->configurableBundleRepository->getConfigurableBundleTemplateImageSets($idConfigurableBundleTemplate, $localeIds);
        $productImageSetTransfers = $this->configurableBundleRepository->getDefaultConfigurableBundleTemplateImageSets($idConfigurableBundleTemplate);

        $productImageSetTransfers = $this->productImageSetCombiner->combineProductImageSets(
            $localizedProductImageSetTransfers,
            $productImageSetTransfers
        );

        $configurableBundleTemplateTransfer->setProductImageSets(new ArrayObject($productImageSetTransfers));

        return $configurableBundleTemplateTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return int[]
     */
    protected function extractLocaleIds(ArrayObject $localeTransfers): array
    {
        $localeIds = [];

        foreach ($localeTransfers as $localeTransfer) {
            $localeIds[] = $localeTransfer->getIdLocale();
        }

        return $localeIds;
    }
}
