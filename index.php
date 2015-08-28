<?php
/**
 * Bot that helps set up a farming session for the Sri Lankan Ingress Enlightened.
 */

function build_response($chat_id, $text) {
    $returnvalue = 'https://api.telegram.org/bot112493740:AAGW9ZOjyfJZh-DJZ-HYW2aJDLuVs2_wwBE/sendMessage?chat_id='
            . $chat_id . '&text=' . $text;
    return $returnvalue;
}
function build_response_keyboard($chat_id, $text, $message_id, $markup) {
    $markup['resize_keyboard'] = true;
    $markup['one_time_keyboard'] = true;
    $markup['selective'] = true;
    $returnvalue = 'https://api.telegram.org/bot112493740:AAGW9ZOjyfJZh-DJZ-HYW2aJDLuVs2_wwBE/sendMessage?chat_id='
        . $chat_id . '&text=' . $text . '&reply_to_message_id=' . $message_id . '&reply_markup=' . json_encode($markup);
    return $returnvalue;
}
function build_location_response($chat_id, $location) {
    $returnvalue = 'https://api.telegram.org/bot112493740:AAGW9ZOjyfJZh-DJZ-HYW2aJDLuVs2_wwBE/sendLocation?chat_id='
        . $chat_id .'&longitude=' . $location['longitude'] . '&latitude='.$location['latitude'];
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
function build_farm_message($id) {
	include_once ('dbAccess.php');
	$db = dbAccess::getInstance();
    $db->setQuery('select * from farms where id=' . $id);
    $currentfarm = $db->loadAssoc();
	$reply = urlencode('Current farm - ' . $currentfarm['location'] . ' ' . $currentfarm['date_and_time'] . '
');
	$reply .= urlencode('Farm creator - ' . $currentfarm['creator'] .'
');
        $db->setQuery('select * from farmers where farm_id=' . $currentfarm['id']);
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
    $verified = array(-34025370, -15987932, -39583346, -29377682, -38823774, -27924249);
    $sequence_commands = array('/farming','/addmetofarm','/removemefromfarm','/deletefarm',
        '/setfarmlocation','/setfarmtime', '/addfarmer','/removefarmer','/getfarmlocation', '/icametofarm' );
    $selection_questions = array('/farming' => 'Which farm do you want the details of?',
                                 '/addmetofarm' => 'Which farm do you want to be added to?',
                                 '/removemefromfarm' => 'Which farm do you want to be removed from?',
                                 '/deletefarm' => 'Which farm do you want to delete?',
                                 '/setfarmlocation' => 'Which farm do you want to set the location for?',
                                 '/setfarmtime' => 'Which farm do you want to set the time for?',
                                 '/addfarmer' => 'Which farm do you want to add to?',
                                 '/removefarmer' => 'Which farm do you want to remove from?',
                                 '/getfarmlocation' => 'Which farm do you want the location of?',
                                 '/icametofarm' => 'Which farm did you come to?');
    $db = dbAccess::getInstance();
    //$response = send_curl('https://api.telegram.org/bot112493740:AAHBuoGVyX2_T-qOzl8LgcH-xoFyYUjIsdg/getUpdates');
    $input_raw = '{
                      "update_id": 89023643,
                      "message": {
                        "message_id": 9370,
                        "from": {
                          "id": 63477295,
                          "first_name": "Ramindu \"RamdeshLota\"",
                          "last_name": "Deshapriya",
                          "username": "RamdeshLota"
                        },
                        "chat": {
                          "id": -27924249,
                          "title": "Bot Devs & BAs"
                        },
                        "date": 1440704429,
                        "reply_to_message": {
                          "message_id": 9369,
                          "from": {
                            "id": 112493740,
                            "first_name": "SL ENL Farm Bot",
                            "username": "SLEnlFarmBot"
                          },
                          "chat": {
                            "id": -27924249,
                            "title": "Bot Devs & BAs"
                          },
                          "date": 1440704423,
                          "text": "@RamdeshLota, Which farm?"
                        },
                        "text": "/addmetofarm@SLEnlFarmBot"
                      }

                    }';
    // let's log the raw JSON message first
    $log = new stdClass();
    $log->message_text = $input_raw;
    $db->insertObject('message_log', $log);

    $messageobj = json_decode($input_raw, true);
    $message_txt_parts = explode(' ', $messageobj['message']['text']);
    $complete_message = $messageobj['message']['text'];
    $request_message = $message_txt_parts[0];
    $request_message = explode('@', $request_message)[0];
    $chat_id = $messageobj['message']['chat']['id'];
    $message_id = $messageobj['message']['message_id'];
    $farmer_name = '@' . $messageobj['message']['from']['username'];
    $reply = '';
    //check for swear words
    foreach ($swears as $swear) {
        if (strpos($complete_message, $swear) !== false) {
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

    if (in_array($request_message, $sequence_commands)) {
        // This is an initial message in the chain, generate the farm list and send
        $db->setQuery('select * from farms where current=1 and farm_group=' . $chat_id);
        $currentfarms = $db->loadAssocList();
        print_r($currentfarms);
        if (empty($currentfarms)) {
            $reply = urlencode('There are no current farms set up. Use /createfarm LOCATION DATE TIME to set up a new farm.');
            send_curl(build_response($chat_id, $reply));

            return;
        }
        $farmer_name = '@' . $messageobj['message']['from']['username'];
        $keyboard = array('keyboard' => array());
        for($i = 0; $i < count($currentfarms); $i++) {
            $keyboard['keyboard'][$i][0] = $currentfarms[$i]['id'] . '. ' . $currentfarms[$i]['location'] . ' ' . $currentfarms[$i]['date_and_time'];
        }
        $reply = urlencode($farmer_name.", " . $selection_questions[$request_message]);
        send_curl(build_response_keyboard($chat_id, $reply, $message_id, $keyboard));
        return;
    }
    if ($request_message == '/createfarm') {
        $time = $location = '';
        $farmer_name = '@' . $messageobj['message']['from']['username'];
        $reply .= easter_eggs($farmer_name);
        if (!empty($message_txt_parts[1])) {
            $location = $message_txt_parts[1];
        } else {
            $reply .= urlencode('You need to set a location for the farm. Please use command /createfarm LOCATION DATE TIME 
');
	    send_curl(build_response($chat_id, $reply));
	    return;
        
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
        $farm->farm_group = $chat_id;
        $farm->current = 1;
        $db->insertObject('farms', $farm);
        $db->setQuery('select * from farms where current=1 order by id desc limit 1');
        $currentfarm = $db->loadAssoc();
        $reply .= urlencode($farmer_name . ' created a farm - ' . $currentfarm['location'] . ' ' . $currentfarm['date_and_time'] . '
1. ' . $farmer_name);
        $farmer = new stdClass();
        $farmer->farm_id = $currentfarm['id'];
        $farmer->farmer_name = $farmer_name;
        $db->insertObject('farmers', $farmer);
        
        send_curl(build_response($chat_id, $reply));
        
        return;


    }
    if (isset($messageobj['message']['reply_to_message'])) {
        // This is a secondary message on the chain - process it
        $secondary_parts = explode('.', $complete_message);
        $selected_farm_id = $secondary_parts[0];
        $reply_to_message = $messageobj['message']['reply_to_message']['text'];
        $db->setQuery('select * from farms where id=' . $selected_farm_id);
        $currentfarm = $db->loadAssoc();
        if (strpos('details', $reply_to_message) !== false) {
            // Earlier message was /farming
            $reply .= build_farm_message($currentfarm['id']);
            send_curl(build_response($chat_id, $reply));

            return;
        }
        if (strpos('added', $reply_to_message) !== false) {
            $db->setQuery("select * from farmers where farmer_name like '$farmer_name%' and farm_id=" . $currentfarm['id']);
            $farmeravailable = $db->loadAssoc();
            if (!empty($farmeravailable)) {
                $reply = urlencode('You have already been added to this farm, ' . $farmer_name);
                send_curl(build_response($chat_id, $reply));

                return;
            }

            if ($farmer_name == '@Cyan017'){
                $reply .= urlencode('Yeah right, like that lazy bugger is going to come for a farm. Pigs will fly!');
            }

            $reply .= easter_eggs($farmer_name);
            $farmer = new stdClass();
            $farmer->farm_id = $currentfarm['id'];
            $farmer->farmer_name = $farmer_name;
            $db->insertObject('farmers', $farmer);
            $reply .= build_farm_message($currentfarm['id']);
            send_curl(build_response($chat_id, $reply));
            return;
        }
        if (strpos('removed', $reply_to_message) !== false) {
            $db->setQuery("select * from farmers where farmer_name like '$farmer_name%' and farm_id=" . $currentfarm['id']);
            $farmeravailable = $db->loadAssoc();
            if (empty($farmeravailable)) {
                $reply = urlencode('You were not in this farm anyway, ' . $farmer_name);
                send_curl(build_response($chat_id, $reply));

                return;
            }

            if ($farmer_name == '@Cyan017'){
                $reply .= urlencode('Hahaha I knew that lazy ass @Cyan017 would never come for a farm!');
            }

            $db->setQuery("delete from farmers where farmer_name like '$farmer_name%' and farm_id=" . $currentfarm['id'])->loadResult();
            $reply .= build_farm_message($currentfarm['id']);
            send_curl(build_response($chat_id, $reply));

            return;
        }
        if (strpos('delete', $reply_to_message) !== false) {
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
        if (strpos('location for', $reply_to_message) !== false) {
            $location = $message_txt_parts[1];
            $farm = new stdClass();
            $farm->id = $currentfarm['id'];
            $farm->location = $location;
            $db->updateObject('farms', $farm, 'id');
            $reply .= urlencode('Set farm location to '. $location .'
');

            $reply .= build_farm_message($currentfarm['id']);
            send_curl(build_response($chat_id, $reply));

            return;
        }
        if (strpos('time', $reply_to_message) !== false) {
            $date_and_time = $message_txt_parts[1] . ' ' . $message_txt_parts[2];
            $farm = new stdClass();
            $farm->id = $currentfarm['id'];
            $farm->date_and_time = $date_and_time;
            $db->updateObject('farms', $farm, 'id');
            $reply .= urlencode('Set farm date and time to '. $date_and_time .'
');
            $reply .= build_farm_message($currentfarm['id']);
            send_curl(build_response($chat_id, $reply));

            return;
        }
        if (strpos('add to', $reply_to_message) !== false) {
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
            $reply .= build_farm_message($currentfarm['id']);
            send_curl(build_response($chat_id, $reply));

            return;
        }
        if (strpos('remove from', $reply_to_message) !== false) {
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
            $reply .= build_farm_message($currentfarm['id']);
            send_curl(build_response($chat_id, $reply));

            return;
        }
        if (strpos('location of', $reply_to_message) !== false) {
            $farmlocation = $currentfarm['location'];

            if(strripos($farmlocation, 'indi') !== false || strripos($farmlocation, 'inde') !== false){
                $locationobj = array('longitude' => 79.867644, 'latitude' => 6.904088);
            }else if(strripos($farmlocation, 'dewram') !== false || strripos($farmlocation, 'devram') !== false){
                $locationobj = array('longitude' =>  79.942516, 'latitude' =>  6.853475);
            }else if(strripos($farmlocation, 'rajagiri') !== false){
                $locationobj = array('longitude' =>  79.895746, 'latitude' =>  6.908751);
            }else {
                $reply = $farmlocation.' farm location is not recognized.';
                send_curl(build_response($chat_id, $reply));
            }
            // $location = json_encode($locationobj);
            send_curl(build_location_response($chat_id,$locationobj));

            return;
        }
        if (strpos('came', $reply_to_message) !== false) {
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
        }
    }
    if ($request_message == '/changerequest' || $request_message == '/changerequest@SLEnlFarmBot') {
    	$message = strtolower(substr($messageobj['message']['text'], 14));
    	
    	if ($message == '' || $message == null ) {
    		$reply = urlencode('Dear Enlightened LK member,
Bloody say something!
Thank you!');
    		send_curl(build_response($chat_id, $reply));
            
    		return;
    	}
    	
    	if (strpos($message,'please') == false) {
    		$reply = urlencode('Dear Enlightened LK member,
Say please. I am programmed to not accommodate rude people.			
Thank you!');
    		send_curl(build_response($chat_id, $reply));
            
    		return;
    	} 
    	
    	$reply = urlencode('Dear Enlightened LK member,
Your suggestion for improvement has been received and will be processed in the distant future (although this is unlikely). 
In the meantime, please be sure to obtain approval from the SL ENL Security Experts Incompetency Group (SESEIG™), 
as there may be unforeseen and unfathomable dangers associated with your change request. 
Thank you!');
    	send_curl(build_response($chat_id, $reply));
        

        $reply = urlencode('New Change Request from - @'.$messageobj['message']['from']['username'].'
        '.substr($messageobj['message']['text'], 14));
        send_curl(build_response( -34025370, $reply));
        

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
/getfarmlocation - Get the location of the current farm.
/setfarmtime DATE TIME - Sets the date and time for the current farm.(e.g. "Today 6pm")
/deletefarm - Deletes the current farm.
/changerequest - Suggest a change to the bot.        		
/help - Display this help text.');

        send_curl(build_response($chat_id, $reply));
        
        return;
    }
}

send_response(file_get_contents('php://input'));
