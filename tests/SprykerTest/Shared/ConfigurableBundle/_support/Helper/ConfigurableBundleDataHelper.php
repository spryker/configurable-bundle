<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerTest\Shared\ConfigurableBundle\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ConfigurableBundleTemplateBuilder;
use Generated\Shared\DataBuilder\ConfigurableBundleTemplateTranslationBuilder;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ConfigurableBundleDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function haveConfigurableBundleTemplate(array $seed = []): ConfigurableBundleTemplateTransfer
    {
        $configurableBundleTemplateBuilder = new ConfigurableBundleTemplateBuilder($seed);
        $configurableBundleTemplateTranslationSeeds = $seed[ConfigurableBundleTemplateTransfer::TRANSLATIONS] ?? [];

        foreach ($configurableBundleTemplateTranslationSeeds as $configurableBundleTemplateTranslationSeed) {
            $configurableBundleTemplateBuilder->withTranslation(
                new ConfigurableBundleTemplateTranslationBuilder($configurableBundleTemplateTranslationSeed)
            );
        }

        return $this->getLocator()
            ->configurableBundle()
            ->facade()
            ->createConfigurableBundleTemplate($configurableBundleTemplateBuilder->build())
            ->getConfigurableBundleTemplate();
    }
}
