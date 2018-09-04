<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\AvailabilityWidget\Widget\ProductViewAvailabilityWidget;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\AvailabilityWidget\AvailabilityWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\AvailabilityWidget\Widget\ProductViewAvailabilityWidget instead.
 */
class AvailabilityWidgetPlugin extends AbstractWidgetPlugin implements AvailabilityWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer): void
    {
        $widget = new ProductViewAvailabilityWidget($productViewTransfer);

        $this->parameters = $widget->getParameters();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return ProductViewAvailabilityWidget::getTemplate();
    }
}
