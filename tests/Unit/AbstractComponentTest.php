<?php

namespace AdeptDigital\WpBaseComponent\Tests\Unit;

use AdeptDigital\WpBaseComponent\AbstractComponent;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class AbstractComponentTest extends TestCase
{
    private function getMockComponent(): AbstractComponent
    {
        $ns = 'ns';
        $map = [
            TEST_DATA_DIR . '/test-theme-child' => 'https://localhost/theme-child',
            TEST_DATA_DIR . '/test-theme' => 'https://localhost/theme',
        ];

        return $this->getMockForAbstractClass(
            AbstractComponent::class,
            [$ns, $map]
        );
    }

    public function testGetUriNotFound()
    {
        $this->expectException(RuntimeException::class);
        $this->getMockComponent()->getUri('not-found.php');
    }

    public function testGetPathNotFound()
    {
        $this->expectException(RuntimeException::class);
        $this->getMockComponent()->getPath('not-found.php');
    }

    public function testGetNamespace()
    {
        $component = $this->getMockComponent();
        $this->assertEquals('ns', $component->getNamespace());
        $this->assertEquals('ns_test', $component->getNamespace('test'));
    }

    public function testGetUri()
    {
        $component = $this->getMockComponent();
        $this->assertEquals('https://localhost/theme-child', $component->getUri());
        $this->assertEquals('https://localhost/theme-child/style.css', $component->getUri('style.css'));
        $this->assertEquals('https://localhost/theme/index.php', $component->getUri('index.php'));
    }

    public function testGetPath()
    {
        $component = $this->getMockComponent();
        $this->assertEquals(TEST_DATA_DIR . '/test-theme-child', $component->getPath());
        $this->assertEquals(TEST_DATA_DIR . '/test-theme-child/style.css', $component->getPath('style.css'));
        $this->assertEquals(TEST_DATA_DIR . '/test-theme/index.php', $component->getPath('index.php'));
    }
}
