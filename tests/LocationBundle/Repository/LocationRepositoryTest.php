<?php

namespace LocationBundle\Tests\Repository;


use LocationBundle\Entity\Location;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LocationRepositoryTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp() {

        self::bootKernel();

        // init entity manager
        $this->entityManager = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

    }

    public function testCrud() {

        # create
        $location = new Location();
        $location->setName("Marlon Lede");
        $location->setAddress("Nachtwachtlaan 407");
        $location->setZipcode("1058EP");
        $location->setCity("Amsterdam");
        $location->setLatitude("52.3631461");
        $location->setLongitude("4.8436509");
        $location->setScore(0);

        $this->entityManager->persist($location);
        $this->entityManager->flush();

        $this->assertEquals(1, count($this->entityManager->getRepository("LocationBundle:Location")->findAll()));
        $this->assertEquals("Marlon Lede", $location->getName());

        # update
        $location->setName("Updated name");
        $this->entityManager->flush();

        $location = $this->entityManager->getRepository("LocationBundle:Location")->find($location->getId());

        $this->assertEquals("Updated name", $location->getName());

        # delete
        $this->entityManager->remove($location);
        $this->entityManager->flush();

        $this->assertEquals(0, count($this->entityManager->getRepository("LocationBundle:Location")->findAll()));
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}