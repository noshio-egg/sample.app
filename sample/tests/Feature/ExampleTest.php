<?php
namespace Tests\Feature;

use PHPUnit\Framework\TestResult;
use Tests\TestCase;

class ExampleTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function run(TestResult $result = null)
    {}
}
