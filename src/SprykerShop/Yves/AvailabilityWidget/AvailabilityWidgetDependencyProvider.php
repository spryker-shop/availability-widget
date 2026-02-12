<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\AvailabilityWidget\Dependency\Client\AvailabilityWidgetToAvailabilityStorageClientBridge;

/**
 * @method \SprykerShop\Yves\AvailabilityWidget\AvailabilityWidgetConfig getConfig()
 */
class AvailabilityWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const string CLIENT_AVAILABILITY_STORAGE = 'CLIENT_AVAILABILITY_STORAGE';

    public const string CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';

    public const string CLIENT_LOCALE = 'CLIENT_LOCALE';

    public const string SERVICE_UTIL_NUMBER = 'SERVICE_UTIL_NUMBER';

    public const string PLUGINS_AVAILABILITY_QUANTITY_FORMATTER_STRATEGY = 'PLUGINS_AVAILABILITY_QUANTITY_FORMATTER_STRATEGY';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);
        $container = $this->addAvailabilityStorageClient($container);
        $container = $this->addGlossaryStorageClient($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addUtilNumberService($container);
        $container = $this->addAvailabilityQuantityFormatterStrategyPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAvailabilityStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_AVAILABILITY_STORAGE, function (Container $container) {
            return new AvailabilityWidgetToAvailabilityStorageClientBridge(
                $container->getLocator()->availabilityStorage()->client(),
            );
        });

        return $container;
    }

    protected function addGlossaryStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_GLOSSARY_STORAGE, function (Container $container) {
            return $container->getLocator()->glossaryStorage()->client();
        });

        return $container;
    }

    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container) {
            return $container->getLocator()->locale()->client();
        });

        return $container;
    }

    protected function addUtilNumberService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_NUMBER, function (Container $container) {
            return $container->getLocator()->utilNumber()->service();
        });

        return $container;
    }

    protected function addAvailabilityQuantityFormatterStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_AVAILABILITY_QUANTITY_FORMATTER_STRATEGY, function () {
            return $this->getAvailabilityQuantityFormatterStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return array<\SprykerShop\Yves\AvailabilityWidgetExtension\Dependency\Plugin\AvailabilityQuantityFormatterStrategyPluginInterface>
     */
    protected function getAvailabilityQuantityFormatterStrategyPlugins(): array
    {
        return [];
    }
}
