<?php

namespace FrontAccounting\ProductAttributes\Plugin;

use KsfCommon\Plugin\PluginInterface;

/**
 * Contract for a product attribute tab plugin.
 *
 * Extends the generic PluginInterface with methods specific to
 * the FA items.php tab system. Any class implementing this interface
 * can be dropped into the tab directory and will be auto-discovered.
 *
 * All methods must be implemented — return null or no-op if the
 * tab does not need to act for a given hook point.
 */
interface ProductAttributeTabInterface extends PluginInterface
{
    /**
     * Unique key used in the FA tab array and _tabs_sel POST var.
     * e.g. 'shipping_attributes', 'product_warranty'
     */
    public function getTabKey(): string;

    /**
     * Display label shown in the FA tab bar.
     * e.g. 'Shipping', 'Warranty'
     */
    public function getTabLabel(): string;

    /**
     * Whether this tab should appear for the given item.
     * Return false to hide the tab entirely.
     */
    public function isAvailable(string $stockId): bool;

    /**
     * Render the tab content HTML.
     */
    public function renderTabContent(string $stockId): void;

    /**
     * Handle POST data when the item is saved.
     */
    public function handleSave(string $stockId, array $postData): void;

    /**
     * Handle cleanup when the item is deleted.
     */
    public function handleDelete(string $stockId): void;
}
