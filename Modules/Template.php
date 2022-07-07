<?php

/** @var GLOBAL $commands - an array of your commands needs to have the slash */
$commands = array('/testing', '/test', '/pass_args');

/**
 * any function can be called with the commands - $bot needs to be passed to the function that will post to the group
 * @param $bot - the main bot that will respond to the user
 * @return void - Returns nothing, but the $bot does return if the post was successful, so this can be returned if required
 */
function test($bot) {

    // list of available variables about the interaction

    // user variables sent to the bot from telegram
    $username = $bot->Username();
    $first_name = $bot->FirstName();
    $last_name = $bot->LastName();
    $telegram_id = $bot->UserID();

    // message variables sent to the bot from telegram
    $chat_id = $bot->ChatID();
    $message_id = $bot->messageID();
    $callback_id = $bot->Callback_ID();
    $message_content = $bot->Text();

    // replys - if the message receieved was a reply to, get the replied message ID and who sent the original message telegram ID
    $reply_to_id = $bot->ReplyToMessageID();
    $reply_to_telegramID = $bot->ReplyToMessageFromUserID();

    // get user details from reply to message
    $reply_user_first_name = $bot->getMessageReplyUserID();
    $reply_user_first_name = $bot->getMessageReplyUserFirstName();

    // callback - general callback queries used for building keyboards
    $inline_query = $bot->Inline_Query();
    $callback_query = $bot->Callback_Query();
    $callback_id = $bot->Callback_ID();
    $callback_message = $bot->Callback_Message();

    // date - the date the message was sent
    $date = $bot->Date();

    // get mentioned user - if the message contains a mentioned user, get the details of the user that was mentioned
    $mentioned_user_id = $bot->getMentionedUserID();
    $mentioned_user_first_name = $bot->getMentionedUserFirstName();

    // if message was forwarded get the forward ID and the chat ID that it came from
    $forward_id = $bot->FromID();
    $forward_from_chat = $bot->FromChatID();

    // send a generic response
    // See https://core.telegram.org/bots/api#sendmessage for the correct input values
    $responseContent = array(
        'chat_id' => $bot->chatID(),
        'text' => "Hello",
    );
    $bot->sendMessage($responseContent);

    // send an image
    // See https://core.telegram.org/bots/api#sendphoto for the correct input values
    $photo_link = 'Telegram link to image, or an external URL';
    $photo_caption = 'Caption to show under the image';
    $PhotoRepsonseContent = array(
        'chat_id' => $bot->ChatID(),
        'photo' => $photo_link,
        'caption' => $photo_caption,
        );
    $bot->sendPhoto($PhotoRepsonseContent);

    // send an inline keyboard button
    // keyboards can be sent with images, plain text messages, and responses to other messages
    // to keep things easier to manage when using a callback query - use this format "function_name-action"
    $keyboard_options = array(
        array(
            $bot->buildInlineKeyboardButton("Line 1, Button 1", $url="", $callback = 'Callback when clicked'),
            $bot->buildInlineKeyboardButton("Line 1, Button 2,", $url="Any HTTPS link here", $callback = '')
        ),
        array(
            $bot->buildInlineKeyboardButton("Line 2, Button 1", $url="", $callback = '')
        )
    );

    $built_keyboard = $bot->buildInlineKeyBoard($keyboard_options);

    $keyboard_content = array(
        'chat_id' => $bot->chatID(),
        'text' => "Hello",
        'reply_markup' => $keyboard_options
    );
    $bot->sendMessage($keyboard_content);

    // reply to a keyboard callback, has to be done first thing to remove the spinner from the button, to show the user the bot has received the response and is processing
    $answer_callback = array(
        'callback_query_id' => $bot->Callback_ID()
    );
    $bot->answerCallbackQuery($answer_callback);
    
    // allow the bot to respond to the command in a private message
    // this is by default set to allow, but can be overridden and instead a message will be sent saying that this command needs to be used within the group
    allowInPM($bot, false);

}

/**
 * another function to handle anything else, can be called in any function can be user to call external APIs' etc
 * @return false
 */
function another_test() {
    // anything could be in here, calculations, calls to external API's etc..
    return false;
}

/**
 * As shown below this is called when the user asks for help as an argument passed with the module command
 * @param $bot - the main bot that will respond to the user
 * @return void - Returns nothing, but the $bot does return if the post was successful, so this can be returned if required
 */
function help_function($bot) {
    // calls the help from the args list
    $responseContent = array(
        'chat_id' => $bot->chatID(),
        'text' => "Hello, im here to help",
    );
    $bot->sendMessage($responseContent);
}

/**
 * This function receives the arguments that was written after the command
 * @param $bot - the main bot that will respond to the user
 * @param $args - the text after the command
 * @return void - Returns nothing, but the $bot does return if the post was successful, so this can be returned if required
 */
function pass_arguments($bot, $args) {
    $responseContent = array(
        'chat_id' => $bot->chatID(),
        'text' => "Hello, user said ".$args,
    );
    $bot->sendMessage($responseContent);
}

/**
 * Showing how to pass callback data from the bottom function
 * @param $bot - the main bot that will respond to the user
 * @return void - Returns nothing, but the $bot does return if the post was successful, so this can be returned if required
 */
function movies($bot) {

    // need to always answer the callback before anything else
    $answer_callback = array(
        'callback_query_id' => $bot->Callback_ID()
    );
    $bot->answerCallbackQuery($answer_callback);

    $responseContent = array(
        'chat_id' => $bot->chatID(),
        'text' => "You clicked the movies button",
    );
    $bot->sendMessage($responseContent);
}

/**
 * Showing how to pass callback data from the bottom function
 * @param $bot - the main bot that will respond to the user
 * @return void - Returns nothing, but the $bot does return if the post was successful, so this can be returned if required
 */
function images($bot) {

    // need to always answer the callback before anything else
    $answer_callback = array(
        'callback_query_id' => $bot->Callback_ID()
    );
    $bot->answerCallbackQuery($answer_callback);

    $responseContent = array(
        'chat_id' => $bot->chatID(),
        'text' => "You clicked the images button",
    );
    $bot->sendMessage($responseContent);
}


/////  REQUIRED  ///////

// calling the commands
if (in_array(COMMAND, $commands)) {

    /* @var COMMAND is the command the user passed, if the user types "/testing" - the function test will be run, but if the user types "/test" this will fail */
    if (COMMAND == '/testing') {
        test($bot);
    }

    // now with arguments that users can pass
    // so a user might ask "/test internet"

    // we trim the whitespace after the command and also make everything lowercase so the bot can understand
    // this will be run with any of the commands that have set in the array.
    if (trim(strtolower(ARGS)) == 'help') {
        help_function($bot);
    }

    // to make it so the bot will only respond to certain commands wrap these within the if statement for that command
    if (COMMAND == '/test') {
        if (trim(strtolower(ARGS)) == 'help') {
            help_function($bot);
        }
    }

    // or single line nested if
    if (COMMAND == '/test' && trim(strtolower(ARGS)) == 'help') {
        help_function($bot);
    }

    // if you want to use the arguments in the function just pass the ARGS to the function as so
    if (COMMAND == '/pass_args') {
        pass_arguments($bot, ARGS);
    }
}

// to use the callback feature we need to check the callback directly as the information is passed back to us as the message text
if ($bot->ServerUpdate() == 'callback_query' && strpos($bot->Text(), 'function_name')) {

    // call your functions in here based on the callback text...

    // i.e one button could be a callback for movies, whilst the other for images
    // as per the comments to keep everything in order and easier to use we use the format "function_name-action"
    $callback = str_replace('function_name-', '', $bot->Text());

    // after we replace the function name we now have the action we want to take in the $callback variable
    // we could also pass the full callback command to get more information.
    if ($callback == 'movies') {
        movies($bot);
    } else if ($callback == 'images') {
        images($bot);
    }
}

