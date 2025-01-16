<?php

namespace NotificationChannels\Bird\Test;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\Bird\BirdChannel;
use NotificationChannels\Bird\BirdClient;
use NotificationChannels\Bird\BirdMessage;
use PHPUnit\Framework\TestCase;

class BirdChannelTest extends TestCase
{
    public function setUp(): void
    {
        $this->notification = new TestNotification;
        $this->string_notification = new TestStringNotification;
        $this->notifiable = new TestNotifiable;
        $this->client = Mockery::mock(new BirdClient('test_ek1qBbKbHoA20gZHM40RBjxzX'));
        $this->channel = new BirdChannel($this->client);
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
        $this->assertInstanceOf(BirdChannel::class, $this->channel);
    }

    /** @test */
    public function test_it_shares_message()
    {
        $this->client->shouldReceive('send')->once();
        $this->assertNull($this->channel->send($this->notifiable, $this->notification));
    }

    /** @test */
    public function if_string_message_can_be_send()
    {
        $this->client->shouldReceive('send')->once();
        $this->assertNull($this->channel->send($this->notifiable, $this->string_notification));
    }
}

class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForMessagebird()
    {
        return '31650520659';
    }
}

class TestNotification extends Notification
{
    public function toMessagebird($notifiable)
    {
        return (new BirdMessage('Message content'))->setOriginator('APPNAME')->setRecipients('31650520659');
    }
}

class TestStringNotification extends Notification
{
    public function toMessagebird($notifiable)
    {
        return 'Test by string';
    }
}
