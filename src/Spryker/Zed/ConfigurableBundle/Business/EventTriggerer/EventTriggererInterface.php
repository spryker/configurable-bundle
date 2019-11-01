<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\EventTriggerer;

interface EventTriggererInterface
{
    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return void
     */
    public function triggerConfigurableBundleTemplatePublishEvent(int $idConfigurableBundleTemplate): void;
}
