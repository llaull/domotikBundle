<?php

namespace Domotique\ReseauBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SensorUnitControllerTest extends WebTestCase
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
        $crawler = $client->request('GET', '/admin/domotique/sensor/unit/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /admin/domotique/sensor/unit/");
        $crawler = $client->click($crawler->selectLink('Ajouter')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Créer')->form(array(
            'sensor_unit[symbole]'  => 't',
            'sensor_unit[name]' => 'Test'
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Editer')->link());

        $form = $crawler->selectButton('Sauvegarder')->form(array(
            'sensor_unit[symbole]'  => 'f',
            'sensor_unit[name]' => 'Foo'
        ));

        $client->submit($form);
//        $crawler = $client->click($crawler->selectLink('Cancel')->link());

//        $this->assertGreaterThan(0, $crawler->filter('td:contains("Foo")')->count(), 'Missing element td:contains("Test")');
    }


}
