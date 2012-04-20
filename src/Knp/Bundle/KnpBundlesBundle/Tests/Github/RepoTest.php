<?php

namespace Knp\Bundle\KnpBundlesBundle\Tests\Github;

use Knp\Bundle\KnpBundlesBundle\Git\RepoManager;
use Knp\Bundle\KnpBundlesBundle\Github\Repo;
use Knp\Bundle\KnpBundlesBundle\Entity\Bundle;
use Symfony\Component\EventDispatcher\EventDispatcher;

class RepoTest extends \PHPUnit_Framework_TestCase
{
    protected function getRepo()
    {
        $github = new \Github_Client;
        $output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');
        $repoManager = $this->getMockBuilder('Knp\Bundle\KnpBundlesBundle\Git\RepoManager')
            ->disableOriginalConstructor()
            ->getMock();

        return new Repo($github, $output, $repoManager, new EventDispatcher());
    }

    protected function getGitRepoMock()
    {
        return $this->getMockBuilder('Knp\Bundle\KnpBundlesBundle\Git\Repo')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testUpdateComposerFailure()
    {
        $repoEntity = new Bundle('knplabs/KnpMenuBundle');
        $repo = $this->getRepo();
        $gitRepo = $this->getGitRepoMock();

        $gitRepo->expects($this->any())
            ->method('hasFile')
            ->with('composer.json')
            ->will($this->returnValue(false));

        $method = new \ReflectionMethod($repo, 'updateComposerFile');
        $method->setAccessible(true);

        $method->invokeArgs($repo, array($gitRepo, $repoEntity));

        $this->assertNull($repoEntity->getComposerName());
    }

    public function testUpdateComposerSuccess()
    {
        $repoEntity = new Bundle('knplabs/KnpMenuBundle');
        $repo = $this->getRepo();
        $gitRepo = $this->getGitRepoMock();

        $gitRepo->expects($this->any())
            ->method('hasFile')
            ->with('composer.json')
            ->will($this->returnValue(true));

        $gitRepo->expects($this->any())
            ->method('getFileContent')
            ->with('composer.json')
            ->will($this->returnValue('{"name": "knplabs/knp-menu-bundle"}'));

        $method = new \ReflectionMethod($repo, 'updateComposerFile');
        $method->setAccessible(true);

        $method->invokeArgs($repo, array($gitRepo, $repoEntity));

        $this->assertEquals($repoEntity->getComposerName(), 'knplabs/knp-menu-bundle');
    }

    public function testFetchComposerDependencies()
    {
        $dependencyFixtures = json_encode(array(
            "requires" => array(
                "first-bundle" => '*',
                "first-lib" => '*',
                "second-bundle" => '*',
                "second-lib" => '*',
                "third-bundle" => '*',
            ),
        ));

        $mockedGitRepo = $this->getGitRepoMock();

        $mockedGitRepo->expects($this->once())
            ->method('hasFile')
            ->with('composer.json')
            ->will($this->returnValue(true));

        $mockedGitRepo->expects($this->once())
            ->method('getFileContent')
            ->will($this->returnValue($dependencyFixtures));


        $bundle = new Bundle('knplabs/KnpMenuBundle');

        $githubRepo = $this->getRepo(); // real

        $labels = $githubRepo->fetchComposerDependencies($bundle);

        $this->assertCount(2, $labels); // so there will be array
    }

    protected function getRepoWithStubbedManager($gitRepo)
    {
        $github = new \Github_Client;
        $output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');
        $repoManager = $this->getMockBuilder('Knp\Bundle\KnpBundlesBundle\Git\RepoManager')
            ->disableOriginalConstructor()
            ->getMock();

        $repoManager->expects($this->any())
            ->method('getRepo')
            ->will($this->returnValue($gitRepo));

        return new Repo($github, $output, $repoManager, new EventDispatcher());
    }

}