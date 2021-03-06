<?php

namespace Laracore\Tests;

class TestCase extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Marks a method as a test stub.
     */
    protected function stub()
    {
        $this->markTestIncomplete('Incomplete Test.');
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        \Mockery::close();
        return parent::tearDown();
    }

}