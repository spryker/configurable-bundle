<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ConfigurableBundle\Expander;

use Generated\Shared\Transfer\ConfiguredBundleTransfer;

class ConfiguredBundleGroupKeyExpander implements ConfiguredBundleGroupKeyExpanderInterface
{
    public function expandConfiguredBundleWithGroupKey(ConfiguredBundleTransfer $configuredBundleTransfer): ConfiguredBundleTransfer
    {
        $configuredBundleGroupKey = sprintf(
            '%s-%s',
            $configuredBundleTransfer->getTemplateOrFail()->getUuidOrFail(),
            uniqid('', true),
        );

        return $configuredBundleTransfer->setGroupKey($configuredBundleGroupKey);
    }
}
