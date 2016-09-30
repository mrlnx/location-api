<?php

namespace LocationBundle\Utilities;

class CsvToArray
{
    private $input = "";
    private $options = array("ignoreFirstLine" => true);

    /**
     * CsvToArray constructor.
     * @param $input
     */

    public function __construct($input) {
        $this->input = $input;
    }

    /**
     * Parses CSV data to an Array
     *
     * @return array
     */

    public function parse() {

        $rows = [];
        $c = 0;

        // open stream
        if (($handle = fopen($this->input, "r")) !== FALSE) {

            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $c++;
                if($this->options["ignoreFirstLine"] && $c == 1) continue;
                $rows[] = $data;
            }

            // close stream
            fclose($handle);
        }
        return $rows;
    }
}