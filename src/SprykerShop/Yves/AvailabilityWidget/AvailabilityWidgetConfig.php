<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class AvailabilityWidgetConfig extends AbstractBundleConfig
{
    protected const bool STOCK_DISPLAY_ENABLED = false;

    protected const int DEFAULT_MAX_FRACTION_DIGITS = 2;

    public const string STOCK_DISPLAY_MODE_INDICATOR_ONLY = 'indicator_only';

    public const string STOCK_DISPLAY_MODE_INDICATOR_AND_QUANTITY = 'indicator_and_quantity';

    /**
     * Specification:
     * - Returns true if the stock display widget is enabled.
     *
     * @api
     */
    public function isStockDisplayEnabled(): bool
    {
        return static::STOCK_DISPLAY_ENABLED;
    }

    /**
     * Specification:
     * - Returns the stock display mode.
     * - Available values are 'indicator_only', 'indicator_and_quantity'.
     * - Requires `AvailabilityWidgetConfig::isStockDisplayEnabled()` to be set to `true`.
     *
     * @api
     */
    public function getStockDisplayMode(): string
    {
        return static::STOCK_DISPLAY_MODE_INDICATOR_AND_QUANTITY;
    }

    /**
     * Specification:
     * - Returns the maximum number of decimal places for displaying product quantity values.
     *
     * @api
     *
     * @return int
     */
    public function getMaxFractionDigits(): int
    {
        return static::DEFAULT_MAX_FRACTION_DIGITS;
    }
}
