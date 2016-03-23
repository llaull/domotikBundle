<?php

namespace Domotique\ReseauBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmplacementControllerTest extends WebTestCase
{


    public function testCompleteScenario()
    {

        $kernel = static::createKernel();
        $kernel->boot();
        $container = $kernel->getContainer();

        $client = static::createClient();
        $s = $container->get('back_office.loging');
        $s->logIn($client);

        // Create a new entry in the database
        $crawler = $client->request('GET', '/admin/domotique/emplacement/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /admin/domotique/emplacement/");
        $crawler = $client->click($crawler->selectLink('Add New')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'emplacement[name]'  => 'Test0001',

            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test0001")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('edit')->link());
//
        $form = $crawler->selectButton('Edit')->form(array(
            'emplacement[_token]'  => 'Test0001',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->click($crawler->selectLink('Back to the list')->link());

        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test0001")')->count(), 'Missing element td:contains("Test")');
    }

}
