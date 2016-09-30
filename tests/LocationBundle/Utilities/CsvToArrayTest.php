<?php

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use LocationBundle\Utilities\CsvToArray;

class CsvToArrayTest extends KernelTestCase
{
    public function testParse() {

        $csv = new CsvToArray("web/files/dummydata.csv");

        $this->assertEquals(100, count($csv->parse()));
    }
}