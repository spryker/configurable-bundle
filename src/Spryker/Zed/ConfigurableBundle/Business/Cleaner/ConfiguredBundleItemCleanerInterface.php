<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Cleaner;

use Generated\Shared\Transfer\QuoteTransfer;

interface ConfiguredBundleItemCleanerInterface
{
    public function removeInactiveConfiguredBundleItemsFromQuote(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
