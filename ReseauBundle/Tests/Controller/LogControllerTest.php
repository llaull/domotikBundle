<?php

namespace Domotique\ReseauBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LogControllerTest extends WebTestCase
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
        $crawler = $client->request('GET', '/admin/domotique/log/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /admin/domotique/log/");
        $crawler = $client->click($crawler->selectLink('Add New')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'log[sonsorValue]'  => '666',
        ));

        $client->submit($form);
//        $crawler = $client->click($crawler->selectLink('Cancel')->link());

//        $this->assertGreaterThan(0, $crawler->filter('td:contains("666")')->count(), 'Missing element td:contains("666")');

    }

}
