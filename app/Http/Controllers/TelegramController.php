<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TelegramController extends Controller
{
    public function sendMessage(Request $request)
    {
        $telegramToken = '6408950608:AAGYsWUHDViiIDbu1uX8Zlxh8W2ZYbast60';
        $channelUsername = $request->has('chatId') ? '@' . $request->input('chatId') : '@click_uz_test';
        $weatherData = $request->input('weatherData');

        $message =
            "Weather for " . $weatherData["city"] . ": " . PHP_EOL .
            'Temperature: ' . $weatherData['temp'] . '°C ' . PHP_EOL .
            'Description: ' . $weatherData['description'] . '' . PHP_EOL;

        $response = Http::post("https://api.telegram.org/bot{$telegramToken}/sendMessage", [
            'chat_id' => $channelUsername,
            'text' => $message,
        ]);

        if ($response->failed()) {
            return  ['message' => 'Failed to send message to Telegram channel', 'code' => 500];
        }

        return  ['message' => $channelUsername, 'code' => 200];
    }
}
