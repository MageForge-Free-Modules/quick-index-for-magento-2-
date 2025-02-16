<?php
declare(strict_types=1);

/**
 * Copyright © MageForge - Free Modules. All rights reserved.
 * Developed by : MageForge - Free Modules
 * Contact : max.developperfb@gmail.com
 */

namespace MageForge\QuickIndex\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Indexer\Model\Indexer\CollectionFactory as IndexerCollectionFactory;

class GetIndexes extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'MageForge_QuickIndex::reindex';

    protected Context $context;
    protected IndexerCollectionFactory $indexerCollection;
    protected JsonFactory $jsonFactory;

    public function __construct(
        Context                  $context,
        IndexerCollectionFactory $indexerCollection,
        JsonFactory              $jsonFactory
    ) {
        parent::__construct($context);
        $this->indexerCollection = $indexerCollection;
        $this->jsonFactory = $jsonFactory;
    }

    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $indexerCollection = $this->indexerCollection->create();

        $indexes = [];
        foreach ($indexerCollection->getItems() as $indexer) {
            $indexes[] = [
                'id' => $indexer->getId(),
                'title' => $indexer->getTitle()
            ];
        }

        return $resultJson->setData(['success' => true, 'indexes' => $indexes]);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MageForge_QuickIndex::reindex');
    }
}

