<?php
if (empty($_GET['url'])) {
    die('Invalid Params!');
}
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $_GET['url'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'authority: vk.com',
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'accept-language: en-US,en;q=0.9,fa-IR;q=0.8,fa;q=0.7',
        'cache-control: max-age=0',
        'referer: https://vk.com/',
        'sec-ch-ua: "Not_A Brand";v="99", "Google Chrome";v="109", "Chromium";v="109"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Windows"',
        'sec-fetch-dest: document',
        'sec-fetch-mode: navigate',
        'sec-fetch-site: same-origin',
        'sec-fetch-user: ?1',
        'upgrade-insecure-requests: 1',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36'
    ),
));
$response = curl_exec($curl);
curl_close($curl);
if (strpos('<title>Error | VK</title>', $response)) {
    echo json_encode(['status' => false], 128);
} else {
    $quality = array('url1080' => '1080', 'url720' => '720', 'url480' => '480', 'url360' => '360', 'url240' => '240');
    $json = [];
    foreach ($quality as $i => $v) {
        preg_match('/"' . $i . '":"(.*?)"/', $response, $matches);
        if ($i)
            $json[$v] = stripslashes($matches[1]);
    }
    preg_match('/"md_title":"(.*?)"/', $response, $Title);
    preg_match('/"jpg":"(.*?)"/', $response, $Thumbnail);
    echo json_encode(['status' => true, 'title' => $Title[1], 'thumb' => stripslashes($Thumbnail[1]), 'files' => $json], 128);
}
