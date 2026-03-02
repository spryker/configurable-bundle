<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Writer;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;

interface ProductListWriterInterface
{
    public function createProductList(ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer): ProductListResponseTransfer;

    public function updateProductList(ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer): ProductListResponseTransfer;
}
