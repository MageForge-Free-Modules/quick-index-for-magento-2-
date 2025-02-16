<?php
declare(strict_types=1);

/**
 * Copyright © MageForge - Free Modules. All rights reserved.
 * Developed by : MageForge - Free Modules
 * Contact : max.developperfb@gmail.com
 */
namespace MageForge\QuickIndex\Service\Product;

use MageForge\QuickIndex\Api\ProductReindexerInterface;
use Magento\Indexer\Model\IndexerFactory;
use Psr\Log\LoggerInterface;

class ProductReindexer implements ProductReindexerInterface
{
    protected IndexerFactory $indexerFactory;
    protected LoggerInterface $logger;

    public function __construct(
        IndexerFactory $indexerFactory,
        LoggerInterface $logger
    ) {
        $this->indexerFactory = $indexerFactory;
        $this->logger = $logger;
    }

    /**
     * @inheridoc
     */
    public function execute(int $productId, array $indexIds): array
    {
        foreach ($indexIds as $indexId) {
            try {
                $indexer = $this->indexerFactory->create()->load($indexId);
                if ($indexer->getId()) {
                    $indexer->reindexRow($productId);
                    $results[] = ['index' => $indexId, 'status' => 'success'];
                    $this->logger->info('Successfully reindexed: ' . $indexId . ' for product: ' . $productId);
                } else {
                    $results[] = ['index' => $indexId, 'status' => 'not found'];
                }
            } catch (\Exception $e) {
                $results[] = ['index' => $indexId, 'status' => 'error', 'message' => $e->getMessage()];
                $this->logger->error('Error reindexing ' . $indexId . ' for product: ' . $productId . ': ' . $e->getMessage());
            }
        }
        return $results;
    }
}
