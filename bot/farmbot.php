<?php
/**
 * Bot that helps set up a farming session for the Sri Lankan Ingress Enlightened.
 */

function build_response($chat_id, $text) {
    $returnvalue = 'https://api.telegram.org/bot112493740:AAGW9ZOjyfJZh-DJZ-HYW2aJDLuVs2_wwBE/sendMessage?chat_id='
            . $chat_id . '&text=' . $text;
    return $returnvalue;
}
function send_curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }

    // Close connection
    curl_close($ch);
}
function build_farm_message() {
	include_once ('dbAccess.php');
	$db = dbAccess::getInstance();
    $db->setQuery('select * from farms where current=1');
    $currentfarm = $db->loadAssoc();
	$reply = urlencode('Current farm - ' . $currentfarm['location'] . ' ' . $currentfarm['date_and_time'] . '
');
	$reply .= urlencode('Farm creator - ' . $currentfarm['creator'] .'
');
        $db->setQuery('select * from farmers where farm_id='.$currentfarm['id']);
        $farmers = $db->loadAssocList();
        $i = 1;
        foreach ($farmers as $farmer) {
            $reply .= urlencode($i . '. ' . $farmer['farmer_name'] . '
');
            $i++;
        }
    return $reply;
}
function easter_eggs($farmer_name) {
    $reply = "";

    if ($farmer_name == '@sirStinkySocks') {
        $reply .= urlencode('Welonde Uncle!!
');
    }
    if ($farmer_name == '@SLpooh') {
        $reply .= urlencode('Gus n galz v r settng up framing fr 2day.
');
    }
    if ($farmer_name == '@thushethan') {
        $reply .= urlencode('This Bot has been verified by the SL ENL Security Experts Incompetency Group (SESEIG™).
');
    }
    if ($farmer_name == '@ultrasn0w') {
        $reply .= urlencode('Strike Team Ultra! Mobilize!!!
');
    }
    if ($farmer_name == '@jaze87') {
        $reply .= urlencode('Hey @jaze87! We are still waiting for your watalappan!!!
');
    }
    if ($farmer_name == '@kulendraj') {
        $reply .= urlencode('The General is coming for farming. Sh*t just got serious.
');
    }
    return $reply;
}
function send_response($input_raw) {
    include 'dbAccess.php';
    $swears = array('fuckoff', 'fuck', 'hutto', 'ponnaya', 'pakaya', 'paka', 'fuckyou', 'redda', 'motherfucker', 'pimpiya','huththa','hukahan');
    $verified = array(-34025370, -15987932, -39583346);
    $db = dbAccess::getInstance();
    //$response = send_curl('https://api.telegram.org/bot112493740:AAHBuoGVyX2_T-qOzl8LgcH-xoFyYUjIsdg/getUpdates');
    /*$input_raw = '{
                      "update_id": 89018516,
                      "message": {
                        "message_id": 62,
                        "from": {
                          "id": 63477295,
                          "first_name": "Ramindu \"RamdeshLota\"",
                          "last_name": "Deshapriya",
                          "username": "CMNisal"
                        },
                        "chat": {
                          "id": 38722085,
                          "title": "Bottest"
                        },
                        "date": 1435508622,
                        "text": "/removemefromfarm"
                      }
                    }';*/
    // let's log the raw JSON message first
    $log = new stdClass();
    $log->message_text = $input_raw;
    $db->insertObject('message_log', $log);
    $messageobj = json_decode($input_raw, true);
    $message_txt_parts = explode(' ', $messageobj['message']['text']);
    $request_message = $message_txt_parts[0];
    $chat_id = $messageobj['message']['chat']['id'];
    $reply = '';
    //check for swear words
    foreach ($swears as $swear) {
        if (strpos($messageobj['message']['text'], $swear) !== false) {
            $reply = urlencode('යකෝ මේක හදල තියෙන්නෙ ගොන් ආතල් ගන්න නෙවේ. ගොන් ආතල් ගන්න ඕන නම් මෑඩ් හව්ස් එකට පලයන්.');
            send_curl(build_response($chat_id, $reply));
            return;
        }
    }
    if (!in_array($chat_id, $verified)) {
        $reply = urlencode('As requested by the SL ENL Security Experts Incompetency Group (SESEIG™), this bot can no longer be used in unverified groups. If you need to have a particular group added to the verified list, talk to @RamdeshLota.');
        send_curl(build_response($chat_id, $reply));
        return;
    }
    if ($request_message == '/farming' || $request_message == '/farming@SLEnlFarmBot') {
        $db->setQuery('select * from farms where current=1');
        $currentfarm = $db->loadAssoc();
        if (empty($currentfarm)) {
            $reply = urlencode('There are no current farms set up. Use /createfarm LOCATION DATE TIME to set up a new farm.');
            send_curl(build_response($chat_id, $reply));
            return;
        }
        $reply .= build_farm_message();
        send_curl(build_response($chat_id, $reply));
        return;

    }
    if ($request_message == '/createfarm') {
        $db->setQuery('select * from farms where current=1');
        $currentfarm = $db->loadAssoc();
        if (!empty($currentfarm)) {
            $reply = urlencode('There is already an active farming session set up. Send /deletefarm to delete that session and create a new one using /createfarm after that. ');
            send_curl(build_response($chat_id, $reply));
            return;
        }
        $time = $location = '';
        $farmer_name = '@' . $messageobj['message']['from']['username'];
        $reply .= easter_eggs($farmer_name);
        if (!empty($message_txt_parts[1])) {
            $location = $message_txt_parts[1];
        } else {
            $reply .= urlencode('You might want to set a location for the farm using /setfarmlocation LOCATION_NAME.
');
        }
        if (!empty($message_txt_parts[2]) && !empty($message_txt_parts[3])) {
            $time = $message_txt_parts[2] . ' ' . $message_txt_parts[3];
        } else {
            $reply .= urlencode('You might want to set a date and time for the farm using /setfarmtime DATE TIME.
');
        }
        $farm = new stdClass();
        $farm->date_and_time = $time;
        $farm->location = $location;
        $farm->creator = $farmer_name;
        $farm->current = 1;
        $db->insertObject('farms', $farm);
        $db->setQuery('select * from farms where current=1');
        $currentfarm = $db->loadAssoc();
        $reply .= urlencode('Current farm - ' . $currentfarm['location'] . ' ' . $currentfarm['date_and_time'] . '
1. ' . $farmer_name);
        $farmer = new stdClass();
        $farmer->farm_id = $currentfarm['id'];
        $farmer->farmer_name = $farmer_name;
        $db->insertObject('farmers', $farmer);
        send_curl(build_response($chat_id, $reply));
        return;


    }
    if ($request_message == '/addmetofarm' || $request_message == '/addmetofarm@SLEnlFarmBot') {
        $db->setQuery('select * from farms where current=1');
        $currentfarm = $db->loadAssoc();
        if (empty($currentfarm)) {
            $reply = urlencode('There are no current farms set up. Use /createfarm LOCATION DATE TIME to set up a new farm.');
            send_curl(build_response($chat_id, $reply));
            return;
        }
        $farmer_name = '@' . $messageobj['message']['from']['username'];
        $db->setQuery("select * from farmers where farmer_name like '$farmer_name%' and farm_id=" . $currentfarm['id']);
        $farmeravailable = $db->loadAssoc();
        if (!empty($farmeravailable)) {
            $reply = urlencode('You have already been added to this farm, ' . $farmer_name);
            send_curl(build_response($chat_id, $reply));
            return;
        }
        $reply .= easter_eggs($farmer_name);
        $farmer = new stdClass();
        $farmer->farm_id = $currentfarm['id'];
        $farmer->farmer_name = $farmer_name;
        $db->insertObject('farmers', $farmer);
        $reply .= build_farm_message();
        send_curl(build_response($chat_id, $reply));
        return;
    }
    if ($request_message == '/removemefromfarm' || $request_message == '/removemefromfarm@SLEnlFarmBot') {
        $db->setQuery('select * from farms where current=1');
        $currentfarm = $db->loadAssoc();
        if (empty($currentfarm)) {
            $reply = urlencode('There are no current farms set up. Use /createfarm LOCATION DATE TIME to set up a new farm.');
            send_curl(build_response($chat_id, $reply));
            return;
        }
        $farmer_name = '@' . $messageobj['message']['from']['username'];
        $db->setQuery("select * from farmers where farmer_name like '$farmer_name%' and farm_id=" . $currentfarm['id']);
        $farmeravailable = $db->loadAssoc();
        if (empty($farmeravailable)) {
            $reply = urlencode('You were not in this farm anyway, ' . $farmer_name);
            send_curl(build_response($chat_id, $reply));
            return;
        }
        $db->setQuery("delete from farmers where farmer_name like '$farmer_name%' and farm_id=" . $currentfarm['id'])->loadResult();
        $reply .= build_farm_message();
        send_curl(build_response($chat_id, $reply));
        return;
    }
    if ($request_message == '/deletefarm' || $request_message == '/delefarm@SLEnlFarmBot') {
        $db->setQuery('select * from farms where current=1');
        $currentfarm = $db->loadAssoc();
        if (empty($currentfarm)) {
            $reply = urlencode('There are no current farms set up to delete. Use /createfarm LOCATION DATE TIME to set up a new farm.');
            send_curl(build_response($chat_id, $reply));
            return;
        }
        $deleter_name = '@' . $messageobj['message']['from']['username'];
        if (($deleter_name != $currentfarm['creator'])
            && ($deleter_name != '@RamdeshLota') && ($deleter_name != '@CMNisal')) {
            $reply = urlencode($deleter_name . ', you are not my Creator or my Uncle, nor are you my Father. You cannot delete me.');
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
    if ($request_message == '/setfarmlocation') {
        $db->setQuery('select * from farms where current=1');
        $currentfarm = $db->loadAssoc();
        if (empty($currentfarm)) {
            $reply = urlencode('There are no current farms set up to set location. Use /createfarm LOCATION DATE TIME to set up a new farm.');
            send_curl(build_response($chat_id, $reply));
            return;
        }
        $location = $message_txt_parts[1];
        $farm = new stdClass();
        $farm->id = $currentfarm['id'];
        $farm->location = $location;
        $db->updateObject('farms', $farm, 'id');
        $reply .= urlencode('Set farm location to '. $location .'
');

        $reply .= build_farm_message();
        send_curl(build_response($chat_id, $reply));
        return;
    }
    if ($request_message == '/setfarmtime') {
        $db->setQuery('select * from farms where current=1');
        $currentfarm = $db->loadAssoc();
        if (empty($currentfarm)) {
            $reply = urlencode('There are no current farms set up to set time. Use /createfarm LOCATION DATE TIME to set up a new farm.');
            send_curl(build_response($chat_id, $reply));
            return;
        }
        $date_and_time = $message_txt_parts[1] . ' ' . $message_txt_parts[2];
        $farm = new stdClass();
        $farm->id = $currentfarm['id'];
        $farm->date_and_time = $date_and_time;
        $db->updateObject('farms', $farm, 'id');
        $reply .= urlencode('Set farm date and time to '. $date_and_time .'
');
       $reply .= build_farm_message();
        send_curl(build_response($chat_id, $reply));
        return;
    }
    if ($request_message == '/addfarmer') {
        $db->setQuery('select * from farms where current=1');
        $currentfarm = $db->loadAssoc();
        if (empty($currentfarm)) {
            $reply = urlencode('There are no current farms set up. Use /createfarm LOCATION DATE TIME to set up a new farm.');
            send_curl(build_response($chat_id, $reply));
            return;
        }
        $farmer_name = $message_txt_parts[1];
        if (empty($farmer_name)) {
            $reply = urlencode('You have not specified a username to add to the farm. Use /addfarmer USERNAME to add a user.');
            send_curl(build_response($chat_id, $reply));
            return;
        }
        if ($farmer_name == '@Cyan017'){
            $reply .= urlencode('Yeah right, like that lazy bugger is going to come for a farm. Pigs will fly!
');
        }
        $db->setQuery("select * from farmers where farmer_name like '$farmer_name%' and farm_id=" . $currentfarm['id']);
        $farmeravailable = $db->loadAssoc();
        if (!empty($farmeravailable)) {
            $reply = urlencode($farmer_name . ' has already been added to this farm.');
            send_curl(build_response($chat_id, $reply));
            return;
        }
        $reply .= easter_eggs($farmer_name);
        $farmer = new stdClass();
        $farmer->farm_id = $currentfarm['id'];
        $farmer->farmer_name = $farmer_name;
        $db->insertObject('farmers', $farmer);
        $reply .= build_farm_message();
        send_curl(build_response($chat_id, $reply));
        return;
    }
    if ($request_message == '/removefarmer') {
        $db->setQuery('select * from farms where current=1');
        $currentfarm = $db->loadAssoc();
        if (empty($currentfarm)) {
            $reply = urlencode('There are no current farms set up. Use /createfarm LOCATION DATE TIME to set up a new farm.');
            send_curl(build_response($chat_id, $reply));
            return;
        }
        $farmer_name = $message_txt_parts[1];
        if (empty($farmer_name)) {
            $reply = urlencode('You have not specified a username to remove from the farm. Use /removefarmer USERNAME to remove a user.');
            send_curl(build_response($chat_id, $reply));
            return;
        }
        if ($farmer_name == '@Cyan017'){
            $reply .= urlencode('Hahaha I knew that lazy ass @Cyan017 would never come for a farm!');
        }
        $db->setQuery("select * from farmers where farmer_name like '$farmer_name%' and farm_id=" . $currentfarm['id']);
        $farmeravailable = $db->loadAssoc();
        if (empty($farmeravailable)) {
            $reply = urlencode($farmer_name . ' is not on this farm anyway.');
            send_curl(build_response($chat_id, $reply));
            return;
        }
        $db->setQuery("delete from farmers where farmer_name like '$farmer_name%' and farm_id=" . $currentfarm['id'])->loadResult();
        $reply .= build_farm_message();
        send_curl(build_response($chat_id, $reply));
        return;
    }
    if ($request_message == '/changerequest' || $request_message == '/changerequest@SLEnlFarmBot') {
    	$reply = urlencode('Dear Enlightened LK member,
Your suggestion for improvement has been received and will be processed in the distant future (although this is unlikely). 
In the meantime, please be sure to obtain approval from the SL ENL Security Experts Incompetency Group (SESEIG™), 
as there may be unforeseen and unfathomable dangers associated with your change request. 
Thank you!');
    	send_curl(build_response($chat_id, $reply));
    	return;
    }

    if ($request_message == '/icametofarm' || $request_message == '/icametofarm@SLEnlFarmBot') {
        $db->setQuery('select * from farms where current=1');
        $currentfarm = $db->loadAssoc();
        if (empty($currentfarm)) {
            $reply = urlencode('There are no current farms set up. Use /createfarm LOCATION DATE TIME to set up a new farm.');
            send_curl(build_response($chat_id, $reply));
            return;
        }
        $farmer_name = '@' . $messageobj['message']['from']['username'];
        $upgraded_farmer_name = '@' . $messageobj['message']['from']['username'].' (Upgraded)';
        $db->setQuery("select * from farmers where farmer_name='$upgraded_farmer_name' and farm_id=" . $currentfarm['id']);
        $upgradedfarmeravailable = $db->loadAssoc();
        if (!empty($upgradedfarmeravailable)) {
            $reply = urlencode('You have already Upgraded this farm,'.$farmer_name);
            send_curl(build_response($chat_id, $reply));
            return;
        }
        $db->setQuery("select * from farmers where farmer_name like '$farmer_name%' and farm_id=" . $currentfarm['id']);
        $farmeravailable = $db->loadAssoc();
        if (empty($farmeravailable)){
            $farmer = new stdClass();
            $farmer->farm_id = $currentfarm['id'];
            $farmer->farmer_name = $upgraded_farmer_name;
            $db->insertObject('farmers', $farmer);
            $reply = urlencode($farmer_name.' Upgraded '.$currentfarm['location'].' Farm.');
            send_curl(build_response($chat_id, $reply));
            return;
        }
        $db->setQuery("select * from farmers where farmer_name like '$farmer_name%' and farm_id=" . $currentfarm['id']);
        $currentfarmer = $db->loadAssoc();
        $farmer = new stdClass();
        $farmer->id = $currentfarmer['id'];
        $farmer->farm_id = $currentfarm['id'];
        $farmer->farmer_name = $upgraded_farmer_name;
        $db->updateObject('farmers',$farmer,'id');
        //$db->insertObject('farmers', $farmer);
        $reply = urlencode($farmer_name.' Upgraded '.$currentfarm['location'].' Farm.');
        send_curl(build_response($chat_id, $reply));
        return;
    }


    if ($request_message == '/help' || $request_message == '/help@SLEnlFarmBot') {
        $reply = urlencode('This is the SL ENL Farming Bot created by @RamdeshLota. Commands:
/createfarm LOCATION DATE TIME - Creates a new farm.
/addmetofarm - Adds you to the current farm.
/removemefromfarm - Removes you from the current farm.
/addfarmer USERNAME - Adds the given username to the farm.
/removefarmer USERNAME - Removes the given username from the farm.
/setfarmlocation LOCATION - Sets the location for the current farm.
/setfarmtime DATE TIME - Sets the date and time for the current farm.(e.g. "Today 6pm")
/deletefarm - Deletes the current farm.
/changerequest - Suggest a change to the bot.        		
/help - Display this help text.');
        send_curl(build_response($chat_id, $reply));
        return;
    }
}

send_response(file_get_contents('php://input'));
