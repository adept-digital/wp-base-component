<?php

namespace AdeptDigital\WpBaseComponent\Tests\Unit;

use AdeptDigital\WpBaseComponent\AbstractComponent;
use AdeptDigital\WpBaseComponent\Exception\NotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AbstractComponentTest extends TestCase
{
    /**
     * @param string $namespace
     * @param string $file
     * @return AbstractComponent|MockObject
     */
    private function createMockComponent(string $namespace = 'abc', string $file = 'test.php'): MockObject
    {
        return $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs([$namespace, $file])
            ->getMockForAbstractClass();
    }

    private function createMockCallable(): MockObject
    {
        return $this
            ->getMockBuilder(\stdclass::class)
            ->addMethods(['__invoke'])
            ->getMock();
    }

    public function testInvoke()
    {
        $component = $this->createMockComponent();
        $component->method('getBootAction')->willReturn('boot');
        $component();
        $this->assertIsInt(has_action('boot', [$component, 'doBoot']));
        $this->assertIsInt(has_action('init', [$component, 'doInit']));
        $this->assertIsInt(has_action('abc_boot', [$component, 'boot']));
        $this->assertIsInt(has_action('abc_init', [$component, 'init']));
    }

    public function testDoBoot()
    {
        $component = $this->createMockComponent();
        $callable = $this->createMockCallable();
        $callable->expects(self::once())
            ->method('__invoke')
            ->with(self::identicalTo($component));

        add_action('abc_boot', $callable);
        $component->doBoot();
    }

    public function testDoInit()
    {
        $component = $this->createMockComponent();
        $callable = $this->createMockCallable();
        $callable->expects(self::once())
            ->method('__invoke')
            ->with(self::identicalTo($component));

        add_action('abc_init', $callable);
        $component->doInit();
    }

    public function testGetBaseNamespace()
    {
        $component = $this->createMockComponent();
        $this->assertEquals('abc', $component->getBaseNamespace());

        $component = $this->createMockComponent('');
        $this->assertEquals('', $component->getBaseNamespace());
    }

    public function testGetNamespace()
    {
        $component = $this->createMockComponent();
        $this->assertEquals('abc_def', $component->getNamespace('def'));

        $component = $this->createMockComponent('');
        $this->assertEquals('_def', $component->getNamespace('def'));
    }

    public function testGetFile()
    {
        $component = $this->createMockComponent();
        $this->assertEquals('test.php', $component->getFile());

        $component = $this->createMockComponent('abc', '');
        $this->assertEquals('', $component->getFile());
    }

    public function testGetPath()
    {
        $component = $this->createMockComponent();
        $component->method('getBasePath')->willReturn(__DIR__);
        $this->assertEquals(__FILE__, $component->getPath(basename(__FILE__)));

        $this->expectException(NotFoundException::class);
        $component->getPath('not-found.txt');
    }

    public function testGetUri()
    {
        $component = $this->createMockComponent();
        $component->method('getBasePath')->willReturn(__DIR__);
        $component->method('getBaseUri')->willReturn('http://localhost');

        $basename = basename(__FILE__);
        $this->assertEquals("http://localhost/{$basename}", $component->getUri($basename));
        $this->assertEquals("http://localhost/{$basename}", $component->getUri("/{$basename}"));
        $this->assertEquals("http://localhost/{$basename}", $component->getUri("\\{$basename}"));
        $this->assertEquals("http://localhost/{$basename}?test=1", $component->getUri("/{$basename}?test=1"));
        $this->assertEquals("http://localhost/{$basename}?test=1#link", $component->getUri("/{$basename}?test=1#link"));

        $this->expectException(NotFoundException::class);
        $component->getPath('not-found.txt');
    }
}
