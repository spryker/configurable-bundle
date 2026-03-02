<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business;

use Spryker\Zed\ConfigurableBundle\Business\Checker\ProductListDeleteChecker;
use Spryker\Zed\ConfigurableBundle\Business\Checker\ProductListDeleteCheckerInterface;
use Spryker\Zed\ConfigurableBundle\Business\Cleaner\ConfigurableBundleTemplateCleaner;
use Spryker\Zed\ConfigurableBundle\Business\Cleaner\ConfigurableBundleTemplateCleanerInterface;
use Spryker\Zed\ConfigurableBundle\Business\Cleaner\ConfigurableBundleTemplateSlotCleaner;
use Spryker\Zed\ConfigurableBundle\Business\Cleaner\ConfigurableBundleTemplateSlotCleanerInterface;
use Spryker\Zed\ConfigurableBundle\Business\Cleaner\ConfiguredBundleItemCleaner;
use Spryker\Zed\ConfigurableBundle\Business\Cleaner\ConfiguredBundleItemCleanerInterface;
use Spryker\Zed\ConfigurableBundle\Business\Creator\ConfigurableBundleTemplateCreator;
use Spryker\Zed\ConfigurableBundle\Business\Creator\ConfigurableBundleTemplateCreatorInterface;
use Spryker\Zed\ConfigurableBundle\Business\Creator\ConfigurableBundleTemplateSlotCreator;
use Spryker\Zed\ConfigurableBundle\Business\Creator\ConfigurableBundleTemplateSlotCreatorInterface;
use Spryker\Zed\ConfigurableBundle\Business\EventTriggerer\EventTriggerer;
use Spryker\Zed\ConfigurableBundle\Business\EventTriggerer\EventTriggererInterface;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTemplateImageSetExpander;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTemplateImageSetExpanderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTemplateSlotProductListExpander;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTemplateSlotProductListExpanderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpander;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpanderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGenerator;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ProductListTitleGenerator;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ProductListTitleGeneratorInterface;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReader;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReaderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateSlotReader;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateSlotReaderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Updater\ConfigurableBundleTemplateSlotUpdater;
use Spryker\Zed\ConfigurableBundle\Business\Updater\ConfigurableBundleTemplateSlotUpdaterInterface;
use Spryker\Zed\ConfigurableBundle\Business\Updater\ConfigurableBundleTemplateUpdater;
use Spryker\Zed\ConfigurableBundle\Business\Updater\ConfigurableBundleTemplateUpdaterInterface;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTranslationWriter;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTranslationWriterInterface;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ProductListWriter;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ProductListWriterInterface;
use Spryker\Zed\ConfigurableBundle\ConfigurableBundleDependencyProvider;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToEventFacadeInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToLocaleFacadeInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToProductImageFacadeInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToProductListFacadeInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Service\ConfigurableBundleToUtilTextServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface getRepository()
 * @method \Spryker\Zed\ConfigurableBundle\ConfigurableBundleConfig getConfig()
 */
class ConfigurableBundleBusinessFactory extends AbstractBusinessFactory
{
    public function createConfigurableBundleNameGenerator(): ConfigurableBundleNameGeneratorInterface
    {
        return new ConfigurableBundleNameGenerator(
            $this->getUtilTextService(),
        );
    }

    public function createProductListTitleGenerator(): ProductListTitleGeneratorInterface
    {
        return new ProductListTitleGenerator();
    }

    public function createProductListDeleteChecker(): ProductListDeleteCheckerInterface
    {
        return new ProductListDeleteChecker(
            $this->createConfigurableBundleTemplateSlotReader(),
        );
    }

    public function createConfigurableBundleTemplateCleaner(): ConfigurableBundleTemplateCleanerInterface
    {
        return new ConfigurableBundleTemplateCleaner(
            $this->getEntityManager(),
            $this->createConfigurableBundleTemplateReader(),
            $this->getProductImageFacade(),
        );
    }

    public function createConfigurableBundleTemplateSlotCleaner(): ConfigurableBundleTemplateSlotCleanerInterface
    {
        return new ConfigurableBundleTemplateSlotCleaner(
            $this->getEntityManager(),
            $this->createConfigurableBundleTemplateSlotReader(),
        );
    }

    public function createConfiguredBundleItemCleaner(): ConfiguredBundleItemCleanerInterface
    {
        return new ConfiguredBundleItemCleaner(
            $this->getRepository(),
        );
    }

    public function createConfigurableBundleTemplateSlotProductListExpander(): ConfigurableBundleTemplateSlotProductListExpanderInterface
    {
        return new ConfigurableBundleTemplateSlotProductListExpander(
            $this->getProductListFacade(),
        );
    }

    public function createConfigurableBundleTranslationExpander(): ConfigurableBundleTranslationExpanderInterface
    {
        return new ConfigurableBundleTranslationExpander(
            $this->getGlossaryFacade(),
            $this->getLocaleFacade(),
        );
    }

    public function createConfigurableBundleTemplateCreator(): ConfigurableBundleTemplateCreatorInterface
    {
        return new ConfigurableBundleTemplateCreator(
            $this->getEntityManager(),
            $this->createConfigurableBundleTranslationWriter(),
            $this->createConfigurableBundleNameGenerator(),
            $this->createEventTriggerer(),
        );
    }

    public function createConfigurableBundleTemplateUpdater(): ConfigurableBundleTemplateUpdaterInterface
    {
        return new ConfigurableBundleTemplateUpdater(
            $this->getEntityManager(),
            $this->createConfigurableBundleTranslationWriter(),
            $this->createConfigurableBundleNameGenerator(),
            $this->createConfigurableBundleTemplateReader(),
            $this->createEventTriggerer(),
        );
    }

    public function createConfigurableBundleTemplateSlotCreator(): ConfigurableBundleTemplateSlotCreatorInterface
    {
        return new ConfigurableBundleTemplateSlotCreator(
            $this->getEntityManager(),
            $this->createConfigurableBundleTranslationWriter(),
            $this->createConfigurableBundleNameGenerator(),
            $this->createProductListWriter(),
            $this->createConfigurableBundleTemplateReader(),
        );
    }

    public function createConfigurableBundleTemplateSlotUpdater(): ConfigurableBundleTemplateSlotUpdaterInterface
    {
        return new ConfigurableBundleTemplateSlotUpdater(
            $this->getEntityManager(),
            $this->createConfigurableBundleTranslationWriter(),
            $this->createConfigurableBundleNameGenerator(),
            $this->createProductListWriter(),
            $this->createConfigurableBundleTemplateSlotReader(),
        );
    }

    public function createConfigurableBundleTranslationWriter(): ConfigurableBundleTranslationWriterInterface
    {
        return new ConfigurableBundleTranslationWriter(
            $this->getGlossaryFacade(),
        );
    }

    public function createProductListWriter(): ProductListWriterInterface
    {
        return new ProductListWriter(
            $this->getProductListFacade(),
            $this->createProductListTitleGenerator(),
        );
    }

    public function createConfigurableBundleTemplateReader(): ConfigurableBundleTemplateReaderInterface
    {
        return new ConfigurableBundleTemplateReader(
            $this->getRepository(),
            $this->createConfigurableBundleTranslationExpander(),
            $this->getLocaleFacade(),
            $this->createConfigurableBundleTemplateImageSetExpander(),
        );
    }

    public function createConfigurableBundleTemplateImageSetExpander(): ConfigurableBundleTemplateImageSetExpanderInterface
    {
        return new ConfigurableBundleTemplateImageSetExpander(
            $this->getRepository(),
            $this->getLocaleFacade(),
        );
    }

    public function createConfigurableBundleTemplateSlotReader(): ConfigurableBundleTemplateSlotReaderInterface
    {
        return new ConfigurableBundleTemplateSlotReader(
            $this->getRepository(),
            $this->createConfigurableBundleTranslationExpander(),
            $this->createConfigurableBundleTemplateSlotProductListExpander(),
            $this->getLocaleFacade(),
        );
    }

    public function createEventTriggerer(): EventTriggererInterface
    {
        return new EventTriggerer($this->getEventFacade());
    }

    public function getGlossaryFacade(): ConfigurableBundleToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleDependencyProvider::FACADE_GLOSSARY);
    }

    public function getLocaleFacade(): ConfigurableBundleToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleDependencyProvider::FACADE_LOCALE);
    }

    public function getProductListFacade(): ConfigurableBundleToProductListFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleDependencyProvider::FACADE_PRODUCT_LIST);
    }

    public function getEventFacade(): ConfigurableBundleToEventFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleDependencyProvider::FACADE_EVENT);
    }

    public function getProductImageFacade(): ConfigurableBundleToProductImageFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleDependencyProvider::FACADE_PRODUCT_IMAGE);
    }

    public function getUtilTextService(): ConfigurableBundleToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleDependencyProvider::SERVICE_UTIL_TEXT);
    }
}
