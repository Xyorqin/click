## Weather Service
### Usage
- Clone this repository to your local environment.
- Install the required dependencies using composer:
  ```bash 
    composer install
  ```
- Edit the **.env.example** file to **.env**. The email information is available in the env file
- Run the Laravel console command to get the weather information:
     ```bash
    php artisan weather {weather_provider} {city} {channel}
     ```
_weather_provider_ takes 2 values: <b>accu-weather</b> and <b>open-weather-map</b> <br>
_channel_  is left blank, the result will appear in the console itself.Besides, you can send the result by telegram and email.<br> -  To send a message via telegram, you need to start in the **telegram:** view, followed by **chat_id**. Instead of chat_id we can get [**@click_uz_test**](https://t.me/click_uz_test) created as a test. <br> - To send the result by email, **mail:** must be started, followed by **example@email**.  
