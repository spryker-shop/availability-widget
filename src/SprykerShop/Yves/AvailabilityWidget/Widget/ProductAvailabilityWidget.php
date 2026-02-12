<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityWidget\Widget;

use Generated\Shared\Transfer\NumberFormatFilterTransfer;
use Generated\Shared\Transfer\NumberFormatFloatRequestTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use SprykerShop\Yves\AvailabilityWidget\AvailabilityWidgetConfig;

/**
 * @method \SprykerShop\Yves\AvailabilityWidget\AvailabilityWidgetFactory getFactory()
 * @method \SprykerShop\Yves\AvailabilityWidget\AvailabilityWidgetConfig getConfig()
 */
class ProductAvailabilityWidget extends AbstractWidget
{
    protected const string PARAMETER_IS_STOCK_DISPLAY_ENABLED = 'isStockDisplayEnabled';

    protected const string PARAMETER_STATUS = 'status';

    protected const string PARAMETER_LABEL = 'label';

    protected const string STATUS_AVAILABLE = 'available';

    protected const string STATUS_OUT_OF_STOCK = 'out-of-stock';

    protected const string GLOSSARY_KEY_AVAILABLE = 'product.availability.available';

    protected const string GLOSSARY_KEY_IN_STOCK = 'product.availability.in_stock';

    protected const string GLOSSARY_KEY_OUT_OF_STOCK = 'product.availability.out_of_stock';

    protected string $localeName;

    public function __construct(mixed $product)
    {
        $this->addIsStockDisplayEnabledParameter();

        if (!$this->hasRequiredMethods($product)) {
            return;
        }

        $this->localeName = $this->getFactory()->getLocaleClient()->getCurrentLocale();
        $statusData = $this->calculateAvailabilityStatus($product);
        $this->addStatusParameter($statusData['status']);
        $this->addLabelParameter($statusData['label']);
    }

    public static function getName(): string
    {
        return 'ProductAvailabilityWidget';
    }

    public static function getTemplate(): string
    {
        return '@AvailabilityWidget/views/product-availability-widget/product-availability-widget.twig';
    }

    protected function addIsStockDisplayEnabledParameter(): void
    {
        $this->addParameter(static::PARAMETER_IS_STOCK_DISPLAY_ENABLED, $this->getConfig()->isStockDisplayEnabled());
    }

    protected function addStatusParameter(string $status): void
    {
        $this->addParameter(static::PARAMETER_STATUS, $status);
    }

    protected function addLabelParameter(string $label): void
    {
        $this->addParameter(static::PARAMETER_LABEL, $label);
    }

    protected function hasRequiredMethods(mixed $product): bool
    {
        return method_exists($product, 'getIsNeverOutOfStock')
            && method_exists($product, 'getStockQuantity');
    }

    /**
     * @param mixed $product
     *
     * @return array<string, mixed>
     */
    protected function calculateAvailabilityStatus(mixed $product): array
    {
        $glossaryClient = $this->getFactory()->getGlossaryStorageClient();

        if ($this->isStatusAvailable($product)) {
            return [
                'status' => static::STATUS_AVAILABLE,
                'label' => $glossaryClient->translate(static::GLOSSARY_KEY_AVAILABLE, $this->localeName),
            ];
        }

        if ($this->isStatusAvailableWithInStock($product)) {
            $formattedQuantity = $this->formatQuantity($product);

            return [
                'status' => static::STATUS_AVAILABLE,
                'label' => $glossaryClient->translate(static::GLOSSARY_KEY_IN_STOCK, $this->localeName, [
                    '%number%' => $formattedQuantity,
                ]),
            ];
        }

        return [
            'status' => static::STATUS_OUT_OF_STOCK,
            'label' => $glossaryClient->translate(static::GLOSSARY_KEY_OUT_OF_STOCK, $this->localeName),
        ];
    }

    protected function formatQuantity(mixed $product): string
    {
        foreach ($this->getFactory()->getAvailabilityQuantityFormatterStrategyPlugins() as $strategyPlugin) {
            if (!$strategyPlugin->isApplicable($product)) {
                continue;
            }

            $formattedQuantity = $strategyPlugin->formatQuantity($product, $this->localeName);

            if ($formattedQuantity !== null) {
                return $formattedQuantity;
            }
        }

        return $this->formatNumber($product->getStockQuantity());
    }

    protected function formatNumber(float $quantity): string
    {
        $numberFormatFloatRequest = (new NumberFormatFloatRequestTransfer())
            ->setNumber($quantity)
            ->setNumberFormatFilter(
                (new NumberFormatFilterTransfer())
                    ->setLocale($this->localeName)
                    ->setMaxFractionDigits($this->getConfig()->getMaxFractionDigits()),
            );

        return $this->getFactory()
            ->getUtilNumberService()
            ->formatFloat($numberFormatFloatRequest);
    }

    protected function isStatusAvailable(mixed $product): bool
    {
        if ($product->getIsNeverOutOfStock()) {
            return true;
        }

        return $this->getConfig()->getStockDisplayMode() === AvailabilityWidgetConfig::STOCK_DISPLAY_MODE_INDICATOR_ONLY
            && $product->getStockQuantity() > 0;
    }

    protected function isStatusAvailableWithInStock(mixed $product): bool
    {
        return $this->getConfig()->getStockDisplayMode() === AvailabilityWidgetConfig::STOCK_DISPLAY_MODE_INDICATOR_AND_QUANTITY
            && $product->getStockQuantity() > 0;
    }
}
