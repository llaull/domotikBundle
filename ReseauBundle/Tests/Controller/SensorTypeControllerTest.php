<?php

namespace Domotique\ReseauBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SensorTypeControllerTest extends WebTestCase
{

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $kernel = static::createKernel();
        $kernel->boot();
        $container = $kernel->getContainer();

        $client = static::createClient();
        $s = $container->get('back_office.loging');
        $s->logIn($client);

        // Create a new entry in the database
        $crawler = $client->request('GET', '/admin/domotique/sensor/type/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /admin/domotique/sensor/type/");
        $crawler = $client->click($crawler->selectLink('Ajouter')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Créer')->form(array(
            'sensor_type[name]'  => 'sensorTypeName',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("sensorTypeName")')->count(), 'Missing element td:contains("sensorTypeName")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Editer')->link());

        $form = $crawler->selectButton('Sauvegarder')->form(array(
            'sensor_type[name]'  => 'sensorTypeNameBis',
        ));

        $client->submit($form);
        $crawler = $client->request('GET', '/admin/domotique/sensor/type/');
        $this->assertGreaterThan(0, $crawler->filter('td:contains("sensorTypeNameBis")')->count(), 'Missing element td:contains("sensorTypeNameBis")');
    }

}
