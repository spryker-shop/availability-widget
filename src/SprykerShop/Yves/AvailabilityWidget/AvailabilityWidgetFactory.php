<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityWidget;

use Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface;
use Spryker\Client\Locale\LocaleClientInterface;
use Spryker\Service\UtilNumber\UtilNumberServiceInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\AvailabilityWidget\Dependency\Client\AvailabilityWidgetToAvailabilityStorageClientInterface;
use SprykerShop\Yves\AvailabilityWidget\Mapper\ProductAvailabilityMapper;
use SprykerShop\Yves\AvailabilityWidget\Mapper\ProductAvailabilityMapperInterface;

class AvailabilityWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\AvailabilityWidget\Mapper\ProductAvailabilityMapperInterface
     */
    public function createProductAvailabilityMapper(): ProductAvailabilityMapperInterface
    {
        return new ProductAvailabilityMapper(
            $this->getAvailabilityStorageClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\AvailabilityWidget\Dependency\Client\AvailabilityWidgetToAvailabilityStorageClientInterface
     */
    public function getAvailabilityStorageClient(): AvailabilityWidgetToAvailabilityStorageClientInterface
    {
        return $this->getProvidedDependency(AvailabilityWidgetDependencyProvider::CLIENT_AVAILABILITY_STORAGE);
    }

    public function getGlossaryStorageClient(): GlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(AvailabilityWidgetDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    public function getLocaleClient(): LocaleClientInterface
    {
        return $this->getProvidedDependency(AvailabilityWidgetDependencyProvider::CLIENT_LOCALE);
    }

    public function getUtilNumberService(): UtilNumberServiceInterface
    {
        return $this->getProvidedDependency(AvailabilityWidgetDependencyProvider::SERVICE_UTIL_NUMBER);
    }

    /**
     * @return array<\SprykerShop\Yves\AvailabilityWidgetExtension\Dependency\Plugin\AvailabilityQuantityFormatterStrategyPluginInterface>
     */
    public function getAvailabilityQuantityFormatterStrategyPlugins(): array
    {
        return $this->getProvidedDependency(AvailabilityWidgetDependencyProvider::PLUGINS_AVAILABILITY_QUANTITY_FORMATTER_STRATEGY);
    }
}
