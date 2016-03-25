<?php

namespace Domotique\ReseauBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ModuleControllerTest extends WebTestCase
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
        $crawler = $client->request('GET', '/admin/domotique/module/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /admin/domotique/module/");
        $crawler = $client->click($crawler->selectLink('Add New')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'module[name]'  => 'Module test',
            'module[adressMac]'  => 'FFFFFFFFFFFF',
            'module[adressIpv4]'  => '127.0.0.1'
        ));

        $client->submit($form);
        $crawler = $client->request('GET', '/admin/domotique/module/');

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Module test")')->count(), 'Missing element td:contains("Module")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('edit')->link());
//
        $form = $crawler->selectButton('Update')->form(array(
            'module[name]'  => 'Module Foo',
            'module[adressMac]'  => 'FFFFFFFFFFFF',
            'module[adressIpv4]'  => '127.0.0.1',
        ));
        $client->submit($form);
//        $crawler = $client->request('GET', '/admin/domotique/module/');
//
//        $this->assertGreaterThan(0, $crawler->filter('td:contains("Module Foo")')->count(), 'Missing element td:contains("Module foo")');
    }


}
