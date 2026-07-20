<?php

namespace FrontAccounting\ProductAttributes\Plugin;

use KsfCommon\Plugin\PluginRegistry;

/**
 * Product-attribute-specific plugin registry.
 *
 * Extends the generic PluginRegistry with tab-specific helpers:
 * - Discover loads only *Tab.php files
 * - getAvailableTabs filters by stock ID availability
 */
class TabRegistry extends PluginRegistry
{
    /**
     * Scan a directory for PHP files ending in *Tab.php that implement
     * ProductAttributeTabInterface.
     *
     * @param string $directory Absolute path to scan
     */
    public function discover(string $directory): void
    {
        if (!is_dir($directory)) {
            return;
        }

        $files = glob($directory . '/*Tab.php');
        if ($files === false) {
            return;
        }

        foreach ($files as $file) {
            $this->loadFile($file);
        }
    }

    /**
     * Get tabs that are available for the given stock ID.
     *
     * Filters out tabs where isAvailable() returns false and
     * ensures only ProductAttributeTabInterface instances are returned.
     *
     * @return ProductAttributeTabInterface[]
     */
    public function getAvailableTabs(string $stockId): array
    {
        $available = [];
        foreach ($this->getActive() as $plugin) {
            if ($plugin instanceof ProductAttributeTabInterface
                && $plugin->isAvailable($stockId)) {
                $available[$plugin->getTabKey()] = $plugin;
            }
        }
        return $available;
    }

    /**
     * Get a tab by its key.
     */
    public function getTab(string $key): ?ProductAttributeTabInterface
    {
        $plugin = $this->get($key);
        return ($plugin instanceof ProductAttributeTabInterface) ? $plugin : null;
    }

    /**
     * Check whether a tab key is registered.
     */
    public function hasTab(string $key): bool
    {
        return $this->has($key);
    }
}
