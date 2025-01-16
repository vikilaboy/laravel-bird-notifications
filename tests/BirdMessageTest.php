<?php

namespace NotificationChannels\Messagebird\Test;

use NotificationChannels\Messagebird\BirdMessage;
use PHPUnit\Framework\TestCase;

class BirdMessageTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $message = new BirdMessage;

        $this->assertInstanceOf(BirdMessage::class, $message);
    }

    /** @test */
    public function it_can_accept_body_content_when_created()
    {
        $message = new BirdMessage('Foo');

        $this->assertEquals('Foo', $message->body);
    }

    /** @test */
    public function it_supports_create_method()
    {
        $message = BirdMessage::create('Foo');

        $this->assertInstanceOf(BirdMessage::class, $message);
        $this->assertEquals('Foo', $message->body);
    }

    /** @test */
    public function it_can_set_body()
    {
        $message = (new BirdMessage)->setBody('Bar');

        $this->assertEquals('Bar', $message->body);
    }

    /** @test */
    public function it_can_set_originator()
    {
        $message = (new BirdMessage)->setOriginator('APPNAME');

        $this->assertEquals('APPNAME', $message->originator);
    }

    /** @test */
    public function it_can_set_recipients_from_array()
    {
        $message = (new BirdMessage)->setRecipients([31650520659, 31599858770]);

        $this->assertEquals('31650520659,31599858770', $message->recipients);
    }

    /** @test */
    public function it_can_set_recipients_from_integer()
    {
        $message = (new BirdMessage)->setRecipients(31650520659);

        $this->assertEquals(31650520659, $message->recipients);
    }

    /** @test */
    public function it_can_set_recipients_from_string()
    {
        $message = (new BirdMessage)->setRecipients('31650520659');

        $this->assertEquals('31650520659', $message->recipients);
    }
}
