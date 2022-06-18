<?php
$url = 'https://api.wordpress.org/secret-key/1.1/salt/';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_keys = curl_exec($ch);
curl_close($ch);

// Remove default WP definition code
$plain_text = preg_replace("/define\('([A-Z_]+)',\s+('.+')\);/", '$1=$2', $wp_keys);

// Use double quotes for consistency with Nebula .env convention
$plain_text = str_replace("'", '"', $plain_text);

// Return JSON verion if "?json=true" has been added to the URL
if (isset($_GET['json']) && $_GET['json'] === 'true') {
    $data = [];
    $lines = explode("\n", $plain_text);
    foreach ($lines as $line) {
        if (!$line) {
            continue;
        }
        $fields = explode("=", $line, 2);
        $data[] = [
            $fields[0],
            trim($fields[1], '"'),
        ];
    } 
    header('Content-Type: application_json; charset=UTF-8');
    echo json_encode($data, JSON_PRETTY_PRINT);
    die;
}

header('Content-Type: text/plain; charset=UTF-8');
echo $plain_text;
