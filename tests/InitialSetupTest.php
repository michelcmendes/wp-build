<?php

use PHPUnit\Framework\TestCase;

class InitialSetupTest extends TestCase
{
    public function testInitialSetupPageLoads()
    {
        $this->assertTrue(function_exists('wp_install'));
    }
}
