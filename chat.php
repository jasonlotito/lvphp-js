<?php

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $chat = apc_fetch('chat');
    $text = generate_links(htmlspecialchars($_POST['chatText']));
    $chat .= '<li><b>' . htmlspecialchars($_POST['user']) . '</b> > ' . $text;
    apc_store('chat', $chat);
    echo json_encode($_POST);
} else if ( $_SERVER['REQUEST_METHOD'] === 'GET' ) {
    $action = isset($_GET['action']) ? $_GET['action'] : 'default';
    switch ($action)
    {
        case 'username':
            break;
        case 'default':
        default:
            echo json_encode(['chatText' => apc_fetch('chat')]);
            break;
    }
}

function generate_links($text)
{
    $pattern = "/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\".,<>?«»“”‘’]))/";
    $text = preg_replace($pattern, '<a href="$1" nofollow="true" target="_blank">$1</a>', $text);
    $text = str_replace(['href="www','href=".'], ['href="http://www','href="http://'], $text);

    $pattern = '/\@([a-zA-Z0-9]+)/';
    $text = preg_replace($pattern, '<a href="http://www.twitter.com/$1" target="_blank">@$1</a>', $text);
    return $text;
}
