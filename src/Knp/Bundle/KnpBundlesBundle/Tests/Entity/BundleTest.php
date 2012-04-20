<?php

namespace Knp\Bundle\KnpBundlesBundle\Tests\Git;

use Knp\Bundle\KnpBundlesBundle\Entity\Bundle;

class BundleTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDaysSinceLastCommit()
    {
        $bundle = new Bundle('knplabs/KnpMenuBundle');
        $bundle->setLastCommitAt(new \Datetime('-31 days'));
        $this->assertEquals(31, $bundle->getDaysSinceLastCommit());
    }

    public function testIsInitializedTrue()
    {
        $bundle = new Bundle('knplabs/KnpMenuBundle');
        $this->assertFalse($bundle->isInitialized());
    }

    public function testIsInitializedFalse()
    {
        $bundle = new Bundle('knplabs/KnpMenuBundle');
        $bundle->setNbForks(1);
        $this->assertTrue($bundle->isInitialized());
    }

    public function testNotInitializedDependencies()
    {
        $bundle = new Bundle('knplabs/KnpMenuBundle');
        $this->assertNull($bundle->getBundleDependencies());
    }

    public function testCanHaveDependencies()
    {
        $bundle = new Bundle('knplabs/KnpMenuBundle');
        $bundleDependentOn = new Bundle('knplabs/KnpMenuBundle');
        $bundle->addBundle($bundleDependentOn);
        $this->assertNotEmpty($bundle->getBundleDependencies());
    }
}
