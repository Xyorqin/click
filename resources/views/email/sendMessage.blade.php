<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div>
        <h3>Weather for {{ $weatherData['city'] }}</h3> <br>
        <p><b>Temperature: </b>
            {{ $weatherData['temp'] }}
        </p>

        <p><b>Description: </b>
            {{ $weatherData['description'] }}
        </p>
     

    </div>
</body>

</html>