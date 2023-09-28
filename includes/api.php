<?php


function translate_text($texts, $source_lang, $target_lang) {
    global $api_key;

    $url = "https://api.openai.com/v1/chat/completions";
    $headers = array(
        'Authorization' => 'Bearer ' . $api_key,
        'Content-Type' => 'application/json'
    );

    $translated_texts = [];

    foreach ($texts as $text) { 
        $body = json_encode(array(
            'model' => 'gpt-3.5-turbo',
            'prompt' => "Translate the following text from $source_lang to $target_lang: $text",
            'max_tokens' => 100
        ));

        $args = array(
            'body' => $body,
            'headers' => $headers,
            'method' => 'POST'
        );

        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            error_log("API Error: " . $response->get_error_message());
            $translated_texts[] = 'Error: ' . $response->get_error_message();
        } else {
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body);

            if (isset($data->error) && $data->error->type === 'insufficient_quota') {
                error_log("API Quota Exceeded: " . json_encode($data));
                $translated_texts[] = 'Translation service is currently unavailable due to quota limits.';
            } elseif (isset($data->choices[0]->text)) {
                $translated_texts[] = $data->choices[0]->text;
            } else {
                error_log("API Response Missing 'choices': " . json_encode($data));
                $translated_texts[] = 'Translation Error';
            }
        }
    }
    return $translated_texts;
}
