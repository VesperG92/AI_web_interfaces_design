<?php

function callOpenAIAPIText($prompt)
{
    $apiKey = 'your-api-key';
    $url = 'https://api.openai.com/v1/chat/completions?';

    $data = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => 'You are a helpful assistant that helps personalize web interfaces.'],
            ['role' => 'user', 'content' => $prompt]
        ],
        'max_tokens' => 100
    ];

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    //echo '<pre>';
    //print_r($response);
    //echo '</pre>';

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);

    return json_decode($response, true);
}

function callOpenAIAPIDall($prompt)
{
    $apiKey = 'your-api-key';
    $url = 'https://api.openai.com/v1/images/generations';

    $data = [
        'model' => 'dall-e-3',
        'prompt' => $prompt,
        'n'=> 1,
        'size'=> '1792x1024'
    ];

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    //echo '<pre>';
    //print_r($response);
    //echo '</pre>';

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);

    return json_decode($response, true);
}
