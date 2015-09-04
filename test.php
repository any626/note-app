<?php

$curl = curl_init('localhost:8000/notes/create');
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => array(
        'api_key' => 'test',
        'title' => 'this is the title',
        'note' => 'this is the note'
    )
));
$result = curl_exec($curl);
print_r($result);
