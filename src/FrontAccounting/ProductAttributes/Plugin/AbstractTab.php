<?php

namespace FrontAccounting\ProductAttributes\Plugin;

use KsfCommon\Plugin\AbstractPlugin;

/**
 * Base class for product attribute tab plugins.
 *
 * Provides no-op defaults for every hook point so that concrete
 * tabs only need to override the methods they actually use.
 *
 * Example — a minimal tab:
 *
 *   class MyTab extends AbstractTab
 *   {
 *       public function getName(): string    { return 'my_tab'; }
 *       public function getTabKey(): string   { return 'my_tab'; }
 *       public function getTabLabel(): string { return 'My Tab'; }
 *       public function renderTabContent(string $stockId): void
 *       {
 *           echo '<p>Hello from my tab</p>';
 *       }
 *   }
 */
abstract class AbstractTab extends AbstractPlugin implements ProductAttributeTabInterface
{
    /** {@inheritDoc} */
    public function isAvailable(string $stockId): bool
    {
        return $stockId !== '';
    }

    /** {@inheritDoc} — default: do nothing */
    public function renderTabContent(string $stockId): void
    {
    }

    /** {@inheritDoc} — default: do nothing */
    public function handleSave(string $stockId, array $postData): void
    {
    }

    /** {@inheritDoc} — default: do nothing */
    public function handleDelete(string $stockId): void
    {
    }
}
