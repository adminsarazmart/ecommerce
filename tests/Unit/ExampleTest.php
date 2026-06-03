<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function test_basic_unit_test(): void
    {
        $this->assertTrue(true);
    }

    public function test_math_operations(): void
    {
        $this->assertEquals(4, 2 + 2);
        $this->assertGreaterThan(0, 100);
        $this->assertIsFloat(3.14);
    }

    public function test_string_functions(): void
    {
        $this->assertEquals('Hello World', trim(' Hello World '));
        $this->assertStringContainsString('World', 'Hello World');
        $this->assertStringEndsWith('!', 'Hi!');
    }

    public function test_array_operations(): void
    {
        $array = ['a', 'b', 'c'];
        $this->assertCount(3, $array);
        $this->assertContains('b', $array);
        $this->assertArrayHasKey(0, $array);
    }
}
