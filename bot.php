<?php

use TelegramBot\ICBot;

if (!file_exists('ICTelegramBot.php')) {
    copy('https://raw.githubusercontent.com/Incognito-Coder/ICTelegramBot/main/ICTelegramBot.php', 'ICTelegramBot.php');
}
require('ICTelegramBot.php');
$bot = new ICBot();
$bot->Initialize("6122044115:AAEvwck0HdiH-48_1erZU0ssOjP9lQC26Uc");

//decomment and run bot.php then comment again.
//$bot->SetWebHook(get_directory_url('bot.php'));
function get_directory_url($file = null)
{
    $protocolizedURL = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $trailingslashURL = preg_replace('/[^\/]+\.php(\?.*)?$/i', '', $protocolizedURL);
    return $trailingslashURL . str_replace($_SERVER['DOCUMENT_ROOT'], '', $file);
}
$text = $bot->GetText();
$chat = $bot->GetChatID();

if (preg_match('/^\/[Ss]tart$/', $text)) {
    $bot->SendMessage($chat, "Hello,I\'m Vk Video Leecher.\nRead /about");
} elseif (str_contains($text, 'vk.com')) {
    $json = json_decode(file_get_contents(get_directory_url('leecher.php') . '?url=' . $text));
    $keys = [];
    foreach ($json->files as $key => $val) {
        array_push($keys, [['text' => "⬇️ Download $key 💾", 'url' => $val]]);
    }
    $bot->SendPhoto(chat: $chat, file: $json->thumb, caption: $json->title, keyboard: $bot->MultiInlineKeyboard($keys));
} elseif (preg_match('/^\/[Aa]bout$/', $text)) {
    $bot->SendMessage($chat, "This bot was build by @Incognito_Coder for getting direct download urls from Vk.com videos.", keyboard: $bot->InlineKeyboard('Dev Channel', 'https://ic_mods.t.me'));
} else {
    $bot->SendMessage($chat, 'I can\'t understand.');
}
