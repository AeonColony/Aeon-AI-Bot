<?php

$commands = array('/weather');

function weather($bot, $arguments) {

    allowInPM($bot, false);

    if (strlen($arguments) > 1) {
        $weather_data = json_decode(@file_get_contents("http://wttr.in/" . $arguments . "?english=%7BLANG%7D&format=j1"), true);

        if (!is_array($weather_data)) {
            $reply = $arguments." is an invalid location";
        } else {

            $cu_temp = $weather_data['current_condition'][0]['temp_C'];
            $temp_max = $weather_data['weather'][0]['maxtempC'];
            $temp_min = $weather_data['weather'][0]['mintempC'];
            $feel = $weather_data['current_condition'][0]['FeelsLikeC'];
            $wea_desc = $weather_data['current_condition'][0]['weatherDesc'][0]['value'];
            $hmdt = $weather_data['current_condition'][0]['humidity'];
            $windk = $weather_data['current_condition'][0]['windspeedKmph'];
            $region = $weather_data['nearest_area']['areaName']['value'];
            $cnt = $weather_data['nearest_area'][0]['country'][0]['value'];

            error_log($region);
            error_log($cnt);

            $reply = "The weather in " . $arguments . " is currently: <b>" . $wea_desc . "</b>\n
Current Temp: <b>" . $cu_temp . "</b>°c
Max Temp: <b>" . $temp_max . "</b>°C
Min Temp: <b>" . $temp_min . "</b>°C
Feels like: <b>" . $feel . "</b>°C
Humidity: <b>" . $hmdt . "</b>%
Wind speed: <b>" . $windk . "</b> Kmph
    
weather data provided by <a href=\"https://wttr.in/\">https://wttr.in/</a>    
    ";
        }
    } else {
        $reply = "Please provide a location";
    }

    $content = array('chat_id' => $bot->ChatID(), 'disable_web_page_preview' => 'True', 'parse_mode'=>'HTML', 'text' => $reply, 'reply_to_message_id' => $bot->MessageID());
    $botResponse = $bot->sendMessage($content);

    error_log(print_r($botResponse, true));

    saveBotReplies($bot, $botResponse['result']['message_id'], $botResponse['result']['text'], '1');
}

function weatherHelp($bot) {
    $chat_id = $bot->ChatID();
    $reply = "Send the command /weather <location> to see the current weather.";

    $content = array('chat_id' => $chat_id, 'text' => $reply);
    $bot->sendMessage($content);
}

if (in_array(COMMAND, $commands)) {

    if (trim(strtolower(ARGS)) == 'help') {
        weatherHelp($bot);
    } else {
        weather($bot, ARGS);
    }
}