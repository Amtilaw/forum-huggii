<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogPostTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('div');
    }

    //Test the form
    public function testForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/subject/form');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    //Test the form submit
    public function testFormSubmit(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/subject/form');

        $form = $crawler->selectButton('Envoyer')->form();
        $form['form[name]'] = 'Test';
        $form['form[contenue]'] = 'Test';
        $client->submit($form);

        $this->assertResponseRedirects('/subject/default1');
    }

    //Test the form submit with error
    public function testFormSubmitError(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/subject/form');

        $form = $crawler->selectButton('Envoyer')->form();
        $form['form[name]'] = 'Test';
        $client->submit($form);

        $this->assertResponseRedirects('/subject/form');
    }

}
