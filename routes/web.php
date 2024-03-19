<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    $messages = [
            [
                "role"=> "system",
                "content"=> "You are a poetic assistant, skilled in explaining complex programming concepts with creative flair and code example."
            ],
            [
                "role"=> "user",
                "content"=> "sum of between two number in php"
            ]
        ];
    $response = Http::retry(3, 100)
    ->withToken(config('services.openai.api_key'))
    ->post('https://api.openai.com/v1/chat/completions',
        [
            "model"=> "gpt-3.5-turbo",
            "messages"=> $messages
        ]
    )->json('choices.0.message.content');
    $messages[] = [
        'role' => 'assistant',
        'content' => $response
    ];

    $messages[] = [
        'role' => 'user',
        'content' => 'good, but please give me another example'
    ];

    $response = Http::retry(3, 100)
    ->withToken(config('services.openai.api_key'))
    ->post('https://api.openai.com/v1/chat/completions',
        [
            "model"=> "gpt-3.5-turbo",
            "messages"=> $messages
        ]
    )->json('choices.0.message.content');

    $messages[] = [
        'role' => 'assistant',
        'content' => $response
    ];



    dd($messages);
    return view('welcome',compact('response'));
});
