<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Communication\Plugin\ProductList;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductListExtension\Dependency\Plugin\ProductListDeletePreCheckPluginInterface;

/**
 * @method \Spryker\Zed\ConfigurableBundle\ConfigurableBundleConfig getConfig()
 * @method \Spryker\Zed\ConfigurableBundle\Business\ConfigurableBundleFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundle\Communication\ConfigurableBundleCommunicationFactory getFactory()
 */
class ConfigurableBundleTemplateSlotProductListDeletePreCheckPlugin extends AbstractPlugin implements ProductListDeletePreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Finds configurable bundle template slots which use given product list by ProductListTransfer::idProductList.
     * - Returns ProductListResponseTransfer with check results.
     * - ProductListResponseTransfer::isSuccessful is equal to true when usage cases were not found, false otherwise.
     * - ProductListResponseTransfer::messages contains usage details.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function execute(ProductListTransfer $productListTransfer): ProductListResponseTransfer
    {
        $configurableBundleTemplateSlotFilterTransfer = (new ConfigurableBundleTemplateSlotFilterTransfer())
            ->setProductList($productListTransfer)
            ->setTranslationLocales(new ArrayObject([
                $this->getFactory()->getLocaleFacade()->getCurrentLocale(),
            ]));

        return $this->getFacade()->isProductListDeletable($configurableBundleTemplateSlotFilterTransfer);
    }
}
