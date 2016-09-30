<?php

namespace LocationBundle\Controller;

use Doctrine\ORM\ORMInvalidArgumentException;
use FOS\RestBundle\Controller\FOSRestController;
use LocationBundle\Entity\Location;
use LocationBundle\Utilities\CsvToArray;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class LocationController extends FOSRestController
{

    /**
     * Generates dummy data from a CSV file
     *
     * @Route("location/generate")
     * @Method("GET")
     */
    public function generateAction()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $csv = new CsvToArray("../web/files/dummydata.csv");

        if($output = $csv->parse()) {

            $rows = 0;

            foreach ($output as $value) {
                try {

                    $location = new Location();
                    $location->setName($value[0]);
                    $location->setAddress($value[1]);
                    $location->setZipcode($value[2]);
                    $location->setCity($value[3]);
                    $location->setLatitude($value[4]);
                    $location->setLongitude($value[5]);
                    $location->setScore(0);

                    $entityManager->persist($location);
                    $entityManager->flush();

                    $rows++;

                } catch (ContextErrorException $e) {
                    $this->get("logger")->error("ContextErrorException:  " . $e->xdebug_message);
                }
            }
        }

        return new JsonResponse(array("response" => "locations_generated", "message" => "added $rows rows to database" ));
    }

    /**
     * Look up a single location by locationId
     *
     * @Route("location/find/{locationId}")
     * @Method("GET")
     */
    public function findOneAction(Request $request)
    {
        $response = array();
        $location = array();

        $entityManager = $this->getDoctrine()->getManager();

        $location = $entityManager->getRepository("LocationBundle:Location")->find($request->get("locationId"));

        if (count($location) > 0) {
            // update score
            $location->setScore($location->getScore() + 1);

            $entityManager->flush();

            // normalize entity output
            $normalize = new GetSetMethodNormalizer();
            $response = $normalize->normalize($location);
        }

        return new JsonResponse($response);
    }

    /**
     *
     * Lookup multiple locations by a search term
     *
     * @Route("location/search/{term}")
     * @Method("GET")
     */
    public function findNumberOfAction(Request $request)
    {
        $location = array();

        $entityManager = $this->getDoctrine()->getManager();

        $term = $request->get("term");

        // check string length
        if(strlen($term) > 2)
            $location = $entityManager->getRepository("LocationBundle:Location")->findByPrefix($term);

        // check returned rows
        return new JsonResponse(array("rows" => count($location), "locations" => $location));
    }

    /**
     * Creating a new location
     *
     * @Route("location/create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
         try {
             // get content location
             // TODO validate $content

             $data = json_decode($request->getContent(), false); // 2nd param to get as array

             $entityManager = $this->getDoctrine()->getManager();

             $location = new Location();
             $location->setName($data->name);
             $location->setAddress($data->address);
             $location->setZipcode($data->zipcode);
             $location->setCity($data->city);
             $location->setLatitude($data->latitude);
             $location->setLongitude($data->longitude);
             $location->setScore(0);

             $entityManager->persist($location);
             $entityManager->flush();

             return new JsonResponse(array("response" => "location_created"));

         } catch (ContextErrorException $e) {

             $this->get("logger")->error("ContextErrorException " . $e->xdebug_message);

             return new JsonResponse(array("response" => "location_not_created"));
         }
    }

    /**
     * Update a existing location
     *
     * @Route("location/update")
     * @Method("POST")
     */
    public function updateAction(Request $request)
    {
        try {
            // get location content
            $data = json_decode($request->getContent(), false); // 2nd param to get as array

            // TODO validate $content
            // TODO validate $id

            $entityManager = $this->getDoctrine()->getManager();

            // get movie by id
            $location = $entityManager->getRepository("LocationBundle:Location")->find($data->id);

            if(count($location) > 0) {
                // create location
                $location->setName($data->name);
                $location->setAddress($data->address);
                $location->setZipcode($data->zipcode);
                $location->setCity($data->city);
                $location->setLatitude($data->latitude);
                $location->setLongitude($data->longitude);

                $entityManager->flush();
            }

            return new JsonResponse(array("response" => "location_updated"));

        } catch (ContextErrorException $e) {

            $this->get("logger")->error("ContextErrorException: " . $e->xdebug_message);

            return new JsonResponse(array("response" => "location_not_updated"));
        }
    }

    /**
     * Deleting a location
     * @Route("location/delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request)
    {
        try {

            // get location content
            $data = json_decode($request->getContent(), false); // 2nd param to get as array

            // TODO validate $id
            $entityManager = $this->getDoctrine()->getManager();

            $location = $entityManager->getRepository("LocationBundle:Location")->find($data->id);

            // remove
            $entityManager->remove($location);
            $entityManager->flush();

            return new JsonResponse(array("response" => "location_deleted"));

        } catch (ORMInvalidArgumentException $e) {

            $this->get("logger")->error("ContextErrorException: " . $e->xdebug_message);

            return new JsonResponse(array("response" => "location_not_deleted"));
        }
    }
}
