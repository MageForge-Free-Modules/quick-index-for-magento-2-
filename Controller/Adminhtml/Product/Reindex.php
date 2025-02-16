<?php
declare(strict_types=1);

/**
 * Copyright © MageForge - Free Modules. All rights reserved.
 * Developed by : MageForge - Free Modules
 * Contact : max.developperfb@gmail.com
 */

namespace MageForge\QuickIndex\Controller\Adminhtml\Product;

use MageForge\QuickIndex\Api\ProductReindexerInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Indexer\Model\IndexerFactory;
use Psr\Log\LoggerInterface;

/**
 * Reset password controller
 *
 * @package Magento\Customer\Controller\Adminhtml\Index
 */
class Reindex extends Action
{
    /**
     * @var IndexerFactory
     */
    protected IndexerFactory $indexerFactory;
    /**
     * @var JsonFactory
     */
    protected JsonFactory $jsonFactory;
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;
    /**
     * @var ProductReindexerInterface
     */
    private ProductReindexerInterface $productReindexer;

    /**
     * @param Context $context
     * @param IndexerFactory $indexerFactory
     * @param JsonFactory $jsonFactory
     * @param LoggerInterface $logger
     * @param ProductReindexerInterface $productReindexer
     */
    public function __construct(
        Context $context,
        IndexerFactory $indexerFactory,
        JsonFactory $jsonFactory,
        LoggerInterface $logger,
        ProductReindexerInterface $productReindexer
    ) {
        parent::__construct($context);
        $this->indexerFactory = $indexerFactory;
        $this->jsonFactory = $jsonFactory;
        $this->logger = $logger;
        $this->productReindexer = $productReindexer;
    }

    /**
     * Reset password handler
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $indexes = $this->getRequest()->getParam('indexes', []);
        $productId = $this->getRequest()->getParam('product_id');

        if (!$productId) {
            return $resultJson->setData(['success' => false, 'message' => 'No product id found.']);
        }

        if (empty($indexes)) {
            return $resultJson->setData(['success' => false, 'message' => 'No indexes selected.']);
        }

        $result = $this->productReindexer->execute((int)$productId, $indexes);

        return $resultJson->setData(['success' => true, 'message' => 'Reindexing completed.', 'result' => $result]);
    }
}
