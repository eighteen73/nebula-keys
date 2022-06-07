<?php
header('Content-Type: text/plain; charset=UTF-8');

$url = 'https://api.wordpress.org/secret-key/1.1/salt/';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_keys = curl_exec($ch);
curl_close($ch);

echo preg_replace("/define\('([A-Z_]+)',\s+('.+')\);/", '$1=$2', $wp_keys);
