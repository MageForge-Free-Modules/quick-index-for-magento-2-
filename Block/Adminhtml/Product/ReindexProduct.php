<?php

declare(strict_types=1);

/**
 * Copyright © MageForge - Free Modules. All rights reserved.
 * Developed by : MageForge - Free Modules
 * Contact : max.developperfb@gmail.com
 */
namespace MageForge\QuickIndex\Block\Adminhtml\Product;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\Context;

class ReindexProduct extends \Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic
{
    /**
     * @param Context $context
     * @param Registry $registry
     */
    public function __construct(Context $context, Registry $registry)
    {
        parent::__construct($context, $registry);
    }

    /**
     * @return array
     */
    public function getButtonData(): array
    {
        $url = $this->getUrl('mageforge_quickindex/product/getindexes', ['_secure' => true]);

        return [
            'label' => __('Reindex Product'),
            'class' => 'action-secondary reindex-product-button',
            'on_click' => '',
            'data_attribute' => [
                'mage-init' => ['MageForge_QuickIndex/js/reindex-modal' => [
                    'url' => $url,
                    'product_id' => $this->getProduct()->getId(),
                ]],
            ],
            'sort_order' => 10
        ];
    }
}
