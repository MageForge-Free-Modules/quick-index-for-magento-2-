<?php

declare(strict_types=1);

/**
 * Copyright © MageForge - Free Modules. All rights reserved.
 * Developed by : MageForge - Free Modules
 * Contact : max.developperfb@gmail.com
 */
namespace MageForge\QuickIndex\Api;

interface ProductReindexerInterface
{
    /**
     * @param int $productId
     * @param array<string> $indexIds
     * @return array<int, array<string, mixed>>
     */
    public function execute(int $productId, array $indexIds): array;
}

