<?php
/**
 * User: ramindu
 * Date: 6/27/15
 * Time: 1:06 PM
 */

function build_response($chat_id, $text) {
    return 'https://api.telegram.org/bot112493740:AAGW9ZOjyfJZh-DJZ-HYW2aJDLuVs2_wwBE/sendMessage?chat_id='
            . $chat_id . '&text=' . $text;
}
function send_curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }

    // Close connection
    curl_close($ch);
}
function send_response($input_raw) {
    include 'dbAccess.php';
    $db = dbAccess::getInstance();
    //$response = send_curl('https://api.telegram.org/bot112493740:AAHBuoGVyX2_T-qOzl8LgcH-xoFyYUjIsdg/getUpdates');
    $input_raw = '{
                      "update_id": 89018516,
                      "message": {
                        "message_id": 62,
                        "from": {
                          "id": 63477295,
                          "first_name": "Ramindu \"RamdeshLota\"",
                          "last_name": "Deshapriya",
                          "username": "RamdeshLota"
                        },
                        "chat": {
                          "id": -34025370,
                          "title": "Bottest"
                        },
                        "date": 1435508622,
                        "text": "\/addmetofarm"
                      }
                    }';
    $messageobj = json_decode($input_raw, true);
    print_r($messageobj);
    $message_txt_parts = explode(' ', $messageobj['message']['text']);
    $chat_id = $messageobj['message']['chat']['id'];
    $reply = '';
    if ($message_txt_parts[0] == '/farming') {
        $db->setQuery('select * from farms where current=1');
        $currentfarm = $db->loadAssoc();
        if (empty($currentfarm)) {
            $reply = 'There are no current farms set up. Use /createfarm LOCATION DATE TIME to set up a new farm.';
            send_curl(build_response($chat_id, $reply));
            return;
        }
        $reply .= urlencode('Current farm - ' . $currentfarm['location'] . ' ' . $currentfarm['date_and_time'] . ' ');
        $db->setQuery('select * from farmers where farm_id='.$currentfarm['id']);
        $farmers = $db->loadAssocList();
        $i = 1;
        foreach ($farmers as $farmer) {
            $reply .= urlencode($i . '. ' . $farmer['farmer_name'] . ' ');
            $i++;
        }
        send_curl(build_response($chat_id, $reply));
        return;

    }
    if ($message_txt_parts[0] == '/createfarm') {
        $db->setQuery('select * from farms where current=1');
        $currentfarm = $db->loadAssoc();
        if (!empty($currentfarm)) {
            $reply = urlencode('There is already an active farming session set up. Send /deletefarm to delete that session and create
                        a new one using /createfarm after that. ');
            send_curl(build_response($chat_id, $reply));
            return;
        }
        $time = $location = '';
        $farmer_name = '@' . $messageobj['message']['from']['username'];
        if (!empty($message_txt_parts[1])) {
            $location = $message_txt_parts[1];
        } else {
            $reply .= urlencode('You might want to set a location for the farm using /setfarmlocation LOCATION_NAME. ');
        }
        if (!empty($message_txt_parts[2]) && !empty($message_txt_parts[3])) {
            $time = $message_txt_parts[2] . ' ' . $message_txt_parts[3];
        } else {
            $reply .= urlencode('You might want to set a date and time for the farm using /setfarmtime DATE TIME. ');
        }
        $farm = new stdClass();
        $farm->date_and_time = $time;
        $farm->location = $location;
        $farm->current = 1;
        $db->insertObject('farms', $farm);
        $db->setQuery('select * from farms where current=1');
        $currentfarm = $db->loadAssoc();
        $reply .= urlencode('Current farm - ' . $currentfarm['location'] . ' ' . $currentfarm['date_and_time'] . ' 1. ' . $farmer_name);
        $farmer = new stdClass();
        $farmer->farm_id = $currentfarm['id'];
        $farmer->farmer_name = $farmer_name;
        $db->insertObject('farmers', $farmer);
        send_curl(build_response($chat_id, $reply));
        return;


    }
    if ($message_txt_parts[0] == '/addmetofarm') {
        $db->setQuery('select * from farms where current=1');
        $currentfarm = $db->loadAssoc();
        if (empty($currentfarm)) {
            $reply = urlencode('There are no current farms set up. Use /createfarm LOCATION DATE TIME to set up a new farm.');
            send_curl(build_response($chat_id, $reply));
            return;
        }
        $farmer_name = '@' . $messageobj['message']['from']['username'];
        $db->setQuery("select * from farmers where farmer_name='$farmer_name' and farm_id=" . $currentfarm['id']);
        $farmeravailable = $db->loadAssoc();
        if (!empty($farmeravailable)) {
            $reply = urlencode('You have already been added to this farm, ' . $farmer_name);
            send_curl(build_response($chat_id, $reply));
            return;
        }
        $farmer = new stdClass();
        $farmer->farm_id = $currentfarm['id'];
        $farmer->farmer_name = $farmer_name;
        $db->insertObject('farmers', $farmer);
        $reply .= urlencode('Current farm - ' . $currentfarm['location'] . ' ' . $currentfarm['date_and_time'] . ' ');
        $db->setQuery('select * from farmers where farm_id='.$currentfarm['id']);
        $farmers = $db->loadAssocList();
        $i = 1;
        foreach ($farmers as $farmer) {
            $reply .= urlencode($i . '. ' . $farmer['farmer_name'] . ' ');
            $i++;
        }
        send_curl(build_response($chat_id, $reply));
        return;
    }
    if ($message_txt_parts[0] == '/deletefarm') {
        $db->setQuery('select * from farms where current=1');
        $currentfarm = $db->loadAssoc();
        if (empty($currentfarm)) {
            $reply = urlencode('There are no current farms set up to delete. Use /createfarm LOCATION DATE TIME to set up a new farm.');
            send_curl(build_response($chat_id, $reply));
            return;
        }
        $farm = new stdClass();
        $farm->id = $currentfarm['id'];
        $farm->current = 0;
        $db->updateObject('farms', $farm, 'id');
        $reply = urlencode('Deleted current farm.');
        send_curl(build_response($chat_id, $reply));
        return;
    }
}

send_response(file_get_contents('php://input'));


