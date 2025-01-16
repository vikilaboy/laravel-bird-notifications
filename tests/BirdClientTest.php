<?php

namespace NotificationChannels\Messagebird\Test;

use GuzzleHttp\Client;
use Mockery;
use NotificationChannels\Messagebird\BirdClient;
use NotificationChannels\Messagebird\BirdMessage;
use PHPUnit\Framework\TestCase;

class BirdClientTest extends TestCase
{
    public function setUp(): void
    {
        $this->client = Mockery::mock(new BirdClient('test_ek1qBbKbHoA20gZHM40RBjxzX'));
        $this->message = (new BirdMessage('Message content'))->setOriginator('APPNAME')->setRecipients('31650520659')->setReference('000123');
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(BirdClient::class, $this->client);
        $this->assertInstanceOf(BirdMessage::class, $this->message);
    }

    /** @test */
    public function it_can_send_message()
    {
        $this->client->shouldReceive('send')->with($this->message)->once();
        $this->assertNull($this->client->send($this->message));
    }
}
