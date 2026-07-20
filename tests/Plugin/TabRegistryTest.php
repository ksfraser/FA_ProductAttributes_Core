<?php

namespace FrontAccounting\ProductAttributes\Test\Plugin;

use FrontAccounting\ProductAttributes\Plugin\AbstractTab;
use FrontAccounting\ProductAttributes\Plugin\ProductAttributeTabInterface;
use FrontAccounting\ProductAttributes\Plugin\TabRegistry;
use PHPUnit\Framework\TestCase;

class SampleTab extends AbstractTab
{
    public function getTabKey(): string
    {
        return 'sample_tab';
    }

    public function getTabLabel(): string
    {
        return 'Sample';
    }
}

class AnotherTab extends AbstractTab
{
    public function getTabKey(): string
    {
        return 'another_tab';
    }

    public function getTabLabel(): string
    {
        return 'Another';
    }

    public function isAvailable(string $stockId): bool
    {
        return $stockId !== 'hidden_item';
    }
}

class TabRegistryTest extends TestCase
{
    public function testRegisterAndGetTab(): void
    {
        $registry = new TabRegistry();
        $tab = new SampleTab();

        $registry->register($tab);

        $this->assertTrue($registry->hasTab('sample_tab'));
        $this->assertSame($tab, $registry->getTab('sample_tab'));
    }

    public function testGetUnknownTabReturnsNull(): void
    {
        $registry = new TabRegistry();

        $this->assertNull($registry->getTab('nonexistent'));
        $this->assertFalse($registry->hasTab('nonexistent'));
    }

    public function testGetAllTabs(): void
    {
        $registry = new TabRegistry();
        $registry->register(new SampleTab());
        $registry->register(new AnotherTab());

        $all = $registry->getAllTabs();
        $this->assertCount(2, $all);
        $this->assertArrayHasKey('sample_tab', $all);
        $this->assertArrayHasKey('another_tab', $all);
    }

    public function testGetAvailableTabsFiltersByStockId(): void
    {
        $registry = new TabRegistry();
        $registry->register(new SampleTab());
        $registry->register(new AnotherTab());

        // SampleTab is available for all stock IDs
        $available = $registry->getAvailableTabs('ITEM001');
        $this->assertCount(2, $available);

        // AnotherTab hides for 'hidden_item'
        $available = $registry->getAvailableTabs('hidden_item');
        $this->assertCount(1, $available);
        $this->assertArrayHasKey('sample_tab', $available);
    }

    public function testDiscoverLoadsTabFiles(): void
    {
        $tmpDir = sys_get_temp_dir() . '/pa_test_' . uniqid();
        mkdir($tmpDir);

        // Write a test tab file
        $code = '<?php
namespace FrontAccounting\ProductAttributes\Test\Plugin\Discovery;
use FrontAccounting\ProductAttributes\Plugin\AbstractTab;
class DiscoveredTab extends AbstractTab {
    public function getTabKey(): string { return "discovered"; }
    public function getTabLabel(): string { return "Discovered"; }
}';
        file_put_contents($tmpDir . '/DiscoveredTab.php', $code);

        $registry = new TabRegistry();
        $registry->discover($tmpDir);

        $this->assertTrue($registry->hasTab('discovered'));
        $this->assertStringEqualsFile(
            $tmpDir . '/DiscoveredTab.php',
            ''
        ); // file was loaded

        // Cleanup
        @unlink($tmpDir . '/DiscoveredTab.php');
        @rmdir($tmpDir);
    }

    public function testDiscoverSkipsNonExistentDirectory(): void
    {
        $registry = new TabRegistry();
        $registry->discover('/nonexistent/path');

        $this->assertCount(0, $registry->getAllTabs());
    }

    public function testRegisterOverridesSameKey(): void
    {
        $registry = new TabRegistry();

        $first = new class extends AbstractTab {
            public function getTabKey(): string { return 'dup'; }
            public function getTabLabel(): string { return 'First'; }
        };
        $second = new class extends AbstractTab {
            public function getTabKey(): string { return 'dup'; }
            public function getTabLabel(): string { return 'Second'; }
        };

        $registry->register($first);
        $registry->register($second);

        $this->assertCount(1, $registry->getAllTabs());
        $this->assertSame('Second', $registry->getTab('dup')->getTabLabel());
    }
}
