<?php
/**
 * Created by PhpStorm.
 * User: dansen
 * Date: 9/8/17
 * Time: 5:22 PM
 */

namespace AppBundle\TestCase;

use Faker\Factory;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AppTestCase extends WebTestCase
{
    protected $_faker;

    public function __construct()
    {
        parent::__construct();
        $this->_faker = Factory::create();
    }

    protected function makeAdminClient()
    {
        $credentials = array(
            'username' => "admin",
            'password' => "123456"
        );
        return $this->makeClient($credentials);
    }

    protected function parseJsonContent($content) {
        return json_decode($content);
    }

    protected function hasError($content) {
        $result = $this->parseJsonContent($content);
        return array_key_exists("error", $result);
    }

    protected function isFailure(Response $response) {
        return $response->getStatusCode() == 500;
    }
}