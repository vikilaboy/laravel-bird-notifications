<?php

namespace NotificationChannels\Bird;

use Illuminate\Support\ServiceProvider;
use NotificationChannels\Messagebird\Exceptions\InvalidConfiguration;

class BirdServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->when(BirdChannel::class)
            ->needs(BirdClient::class)
            ->give(function () {
                $config = config('services.bird');

                if (is_null($config)) {
                    throw InvalidConfiguration::configurationNotSet();
                }

                return new BirdClient(
                    accessKey: $config['access_key'],
                    originator: $config['originator'],
                    workspace: $config['workspace'],
                    channel: $config['channel']);
            });
    }
}
