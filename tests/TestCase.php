<?php

namespace Tests;

class TestCase extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Marks a method as a test stub.
     */
    protected function stub()
    {
        $this->markTestIncomplete('Incomplete Test.');
    }

}