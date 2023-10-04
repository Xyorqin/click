<?php

namespace App\Console\Commands;

use App\Http\Controllers\TelegramController;
use App\Mail\WeatherEmail;
use App\Services\WeatherService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class GetWeather extends Command
{
    protected $signature = 'weather {provider} {city} {channel?}';
    protected $description = 'Get the current weather for a given city';

    private $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        parent::__construct();
        $this->weatherService = $weatherService;
    }

    public function handle()
    {
        $provider = $this->argument('provider');
        $city = $this->argument('city');
        $channel = $this->argument('channel') ?: 'console';


        try {
            $weatherData = $this->weatherService->getWeather($provider, $city);
            $this->displayWeather($weatherData, $channel);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    private function displayWeather($weatherData, $channel)
    {
        if (mb_substr($channel, 0, 5) === 'mail:') {
            $this->info('Weather sent via email to ' . $this->getEmailFromChannel(substr($channel, 5), $weatherData));
        } elseif (strpos($channel, 'telegram:') === 0) {

            $chatId = substr($channel, 9);
            $response = app(TelegramController::class)->sendMessage(request()->merge(['chatId' => $chatId, 'weatherData' => $weatherData]));

            if ($response['code'] == 200)
                $this->info('Weather sent via Telegram to chat ID ' . $response['message']);
            else
                $this->info($response['message']);
        } else {
            $this->info('Weather for ' . $weatherData['city'] . ':');
            $this->info('Temperature: ' . $weatherData['temp'] . '°C');
            $this->info('Description: ' . $weatherData['description']);
        }
    }

    private function getEmailFromChannel($channel, $weatherData)
    {
        Mail::to($channel)->send(new WeatherEmail($weatherData));
        return $channel;
    }
}
