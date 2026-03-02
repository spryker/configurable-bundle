<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Generator;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;

interface ConfigurableBundleNameGeneratorInterface
{
    public function generateTemplateName(ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer): string;

    public function generateTemplateSlotName(ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer): string;
}
