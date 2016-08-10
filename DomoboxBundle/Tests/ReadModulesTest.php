<?php
/**
 * Created by PhpStorm.
 * User: hazardl
 * Date: 04/02/2016
 * Time: 17:12
 */

namespace Domotique\DomoboxBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class ReadModuleTest extends WebTestCase
{

    public function testIndex()
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/log/value',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json')
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
