<?php
class SampleTest extends WP_UnitTestCase {

    public function test_example() {
        // Test that true is true
        $this->assertTrue(true);
    }

    public function test_wp_version() {
        // Example test to check WordPress version
        $this->assertEquals(get_bloginfo('version'), '5.8');
    }
}