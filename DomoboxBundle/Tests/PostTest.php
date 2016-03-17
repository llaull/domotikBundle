<?php
/**
 * Created by PhpStorm.
 * User: hazardl
 * Date: 04/02/2016
 * Time: 17:12
 */

namespace Domotique\bundle\ModuleBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class PostTest extends WebTestCase
{

    public function testIndex()
    {
        $client = static::createClient();

        // données simulé
        $datas = array(
            "mac" => "000000000000",
            "ipv4" => "10.1.1.1",
            "iterator" => "1932",
            "debug txt" => "sucess",
            "sensors" => array(
                "sensor Id" => "1",
                "sensor type Id" => "1",
                "sensor unit Id" => "7",
                "sensor value" => "1")
        );

        $client->request(
            'POST',
            '/module/esp8266/post/json/',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode(array('data' => $datas))
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
