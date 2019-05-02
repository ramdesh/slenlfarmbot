const axios = require('axios');
const mongodb = require('mongodb');
const shortid = require('shortid');

const Constants = require('./constants');

const BOT_ACCESS_TOKEN = process.env.BOT_ACCESS_TOKEN;
const MONGO_URL = process.env.MONGO_URL;
const DB_NAME = process.env.DB_NAME;

const TG_URL = 'https://api.telegram.org/bot'+ BOT_ACCESS_TOKEN + '/sendMessage';

const MongoClient = mongodb.MongoClient;
let db = null;

async function initDb() {
  try {
    const client = await MongoClient.connect(MONGO_URL);
    return client.db(DB_NAME);
  } catch (err) {
    console.error(err);
  }
}
//This object is used to store the questions to be asked when a user sends a message which would require secondary processing for farm selection.
//response - Farm selection question - this is used in later processing to identify which message the bot should reply to
// parts - How many segments should there be other than the request message - this is used for validation.
// error - Response to send if validation on message segments fails.
const selectionQuestions = {
  '/farming': {
    response: 'Which farm do you want the details of?',
    parts: 0,
    error: null
  },
  '/addmetofarm': {
    response: 'Which farm do you want to be added to?',
    parts: 0,
    error: null
  },
  '/removemefromfarm': {
    response: 'Which farm do you want to be removed from?',
    parts: 0,
    error: null
  },
  '/deletefarm': {
    response: 'Which farm do you want to delete?',
    parts: 0,
    error: null
  },
  '/setfarmlocation': {
    response: 'Which farm do you want to set the location for?',
    parts: 1,
    error: 'You need to specify a location. Use /setfarmlocation LOCATION.'
  },
  '/setfarmtime': {
    response: 'Which farm do you want to set the time for?',
    parts: 2,
    error: 'You need to specify a date and time. Use /setfarmtime DATE TIME.'
  },
  '/addfarmer': {
    response: 'Which farm do you want to add to?',
    parts: 1,
    error: 'You need to specify who you need to add. Use /addfarmer FARMER_NAME.'
  },
  '/removefarmer': {
    response: 'Which farm do you want to remove from?',
    parts: 1,
    error: 'You need to specify who you need to remove. Use /removefarmer FARMER_NAME.'
  },
  '/icametofarm': {
    response: 'Which farm did you come to?',
    parts: 0,
    error: null
  }
};

const messageProcessor = {
  '/createfarm': async(db, message) => {
    let messageParts = message.text.split(' ');
    let farmer = '@' + message.from.username;
    let location = messageParts[1] ? messageParts[1] : undefined;
    let time = messageParts[2] && messageParts[3] ?
      messageParts[1] + ' ' + messageParts[3] : undefined;
    if(!location) {
      return await 'You cannot set up a farm without specifying a location for it.' +
        'Use /createfarm LOCATION DATE TIME.';
    }
    if(!time) {
      return await 'You cannot set up a farm without specifying a date and time for it.' +
        'Use /createfarm LOCATION DATE TIME.';
    }
    let farmId = shortid.generate();
    try {
      await db.collection('farms').insertOne({
        _id: farmId,
        date_and_time: time,
        location: location,
        creator: farmer,
        farm_group: message.chat.id,
        current: 1
      });
      await db.collection('farmers').insertOne({
        farm_id: farmId,
        farmer_name: farmer
      });
    } catch(err) {
      console.error(err);
      return await 'There was an error creating the farm.'
    }
    return await farmer + ' created a farm - ' + location + ' ' + time + '.\n' +
      '1. ' + farmer;
  },
  secondary: {
    '/farming': async(db, msgParts) => {
      let farmId = msgParts[1];
      return await buildSingleFarmMessage(db, farmId);
    }
  },
  initiateChain: async(command, db, message) => {
    let keyboardMarkup = await buildFarmListKeyboard(command, db, message.chat.id);
    if(keyboardMarkup) {
      return await buildResponse(message.chat.id, selectionQuestions[command].response, keyboardMarkup);
    } else {
      return await buildResponse(message.chat.id, Constants.NO_FARMS_MSG);
    }

  }

};

async function sendHttp(messageBody) {
  try {
    let response = await axios.post(TG_URL, messageBody, { headers: {'Content-Type': 'application/json'}});
    return {
      "statusCode" : 200,
      "body" : JSON.stringify({message: "This is the SL ENL Farm Bot"}),
      "isBase64Encoded": false
    };
  } catch(err) {
    console.error(err);
    return Constants.GEN_RESP;
  }


}
function buildResponse(chatId, text, replyMarkup = undefined) {
  let response = {
    chat_id: chatId,
    text: text
  };
  if (replyMarkup) {
    response.reply_markup = replyMarkup;
  }
  return response;
}
async function buildSingleFarmMessage(db, farmId) {
  try {
    let farms = await db.collection('farms').find({_id: farmId}).toArray();
    let farmers = await db.collection('farmers').find({farm_id: farmId}).toArray();
    let farm = farms[0];
    let responseText = 'Farm - ' + farm['location'] + ' ' + farm['date_and_time'] + '\n' +
      'Farm creator - ' + farm['creator'];
    for(let i = 1; i <= farmers.length; i++) {
      responseText += '\n' + i + '. '+ farmers[i-1]['farmer_name'];
    }
    return await responseText;
  } catch(err) {
    console.error(err);
    return await Constants.ERR_MSG;
  }
}
async function buildFarmListKeyboard(command, db, chatId) {
  let inlineKeyboard = [];
  try {
    let farms = await db.collection('farms').find({farm_group: chatId, current: 1}).toArray();
    if(farms.length > 0) {
      farms.forEach(farm => {
        inlineKeyboard.push([{
          text: farm['location'] + ' ' + farm['date_and_time'],
          callback_data: command + '|' + farm['_id']
        }])
      });
      return await { inline_keyboard: inlineKeyboard };
    } else return await null;

  } catch(err) {
    console.error(err);
    return await Constants.ERR_MSG;
  }
}

exports.handler = async (req) => {
  db = await initDb();
  try {
    let chatBody = JSON.parse(req.body);
    let cmd = '';
    if(chatBody.callback_query) {
      // This is a message sent through the inline keyboard
      chatBody = chatBody.callback_query;
      let chatId = chatBody.message.chat.id;
      let queryParts = chatBody.data.split('|');
      cmd = queryParts[0];
      if(!messageProcessor.secondary[cmd]) {
        return sendHttp(buildResponse(chatId, 'Sorry, I didn\'t understand that command.'));
      } else {
        let txt = await messageProcessor.secondary[cmd](db, queryParts);
        return sendHttp(buildResponse(chatId, txt));
      }
    } else {
      let chatId = chatBody.message.chat.id;
      let splitByAt = chatBody.message.text.split('@');
      let firstPart = splitByAt[0].split(' ');
      cmd = firstPart[0];
      if(!messageProcessor[cmd] && !selectionQuestions[cmd]) {
        return sendHttp(buildResponse(chatId, 'Sorry, I didn\'t understand that command.'));
      }
      if(selectionQuestions[cmd]) {
        let chainResponse = await messageProcessor.initiateChain(cmd, db, chatBody.message);
        return sendHttp(chainResponse);
      } else {
        let responseText = await messageProcessor[cmd](db, chatBody.message);
        return sendHttp(buildResponse(chatId, responseText));
      }
    }

  } catch(err) {
    console.error(err);
    return Constants.GEN_RESP;
  }



};

/**
 * callback from inline button:
 {
    update_id: 89119936,
    callback_query: {
        id: '272632906681473807',
        from: {
            id: 63477295,
            is_bot: false,
            first_name: 'Ramindu [RamdeshLota|SL]',
            last_name: 'Deshapriya',
            username: 'RamdeshLota',
            language_code: 'en'
        },
        message: {
            message_id: 71125,
            from: [Object],
            chat: [Object],
            date: 1556744461,
            text: 'Which farm do you want the details of?'
        },
        chat_instance: '-2749590899155798484',
        data: 'BihHYd2m1'
    }
}
 */
