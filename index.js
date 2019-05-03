const axios = require('axios');
const mongodb = require('mongodb');
const shortid = require('shortid');

const Constants = require('./constants');

const BOT_ACCESS_TOKEN = process.env.BOT_ACCESS_TOKEN;
const MONGO_URL = process.env.MONGO_URL;
const DB_NAME = process.env.DB_NAME;

const TG_SENDMESSAGE = 'https://api.telegram.org/bot'+ BOT_ACCESS_TOKEN + '/sendMessage';
const TG_EDITMARKUP = 'https://api.telegram.org/bot'+ BOT_ACCESS_TOKEN + '/editMessageReplyMarkup';

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
    error: null,
    isSelfService: false
  },
  '/addmetofarm': {
    response: 'Which farm do you want to be added to?',
    parts: 0,
    error: null,
    isSelfService: true
  },
  '/removemefromfarm': {
    response: 'Which farm do you want to be removed from?',
    parts: 0,
    error: null,
    isSelfService: true
  },
  '/deletefarm': {
    response: 'Which farm do you want to delete?',
    parts: 0,
    error: null,
    isSelfService: false
  },
  '/setfarmlocation': {
    response: 'Which farm do you want to set the location for?',
    parts: 1,
    error: 'You need to specify a location. Use /setfarmlocation LOCATION.',
    isSelfService: false
  },
  '/setfarmtime': {
    response: 'Which farm do you want to set the time for?',
    parts: 2,
    error: 'You need to specify a date and time. Use /setfarmtime DATE TIME.',
    isSelfService: false
  },
  '/addfarmer': {
    response: 'Which farm do you want to add to?',
    parts: 1,
    error: 'You need to specify who you need to add. Use /addfarmer FARMER_NAME.',
    isSelfService: false
  },
  '/removefarmer': {
    response: 'Which farm do you want to remove from?',
    parts: 1,
    error: 'You need to specify who you need to remove. Use /removefarmer FARMER_NAME.',
    isSelfService: false
  },
  '/icametofarm': {
    response: 'Which farm did you come to?',
    parts: 0,
    error: null,
    isSelfService: true
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
      return await 'There was an error creating the farm.';
    }
    return await farmer + ' created a farm - ' + location + ' ' + time + '.\n' +
      '1. ' + farmer;
  },
  '/help': async(db, message) => {
    return await 'This is the SL ENL Farming Bot created by @RamdeshLota. \nCommands: \n' +
        '/createfarm LOCATION DATE TIME - Creates a new farm.\n' +
      '/addmetofarm - Adds you to the current farm.\n' +
      '/removemefromfarm - Removes you from the selected farm.\n' +
      '/addfarmer USERNAME - Adds the given username to the farm.\n' +
      '/removefarmer USERNAME - Removes the given username from the selected farm.\n' +
      '/deletefarm - Deletes the selected farm. \n' +
      '/help - Display this help text.';
  },
  secondary: {
    '/farming': async (db, msgParts) => {
      let farmId = msgParts[1];
      return await buildSingleFarmMessage(db, farmId);
    },
    '/addmetofarm': async (db, msgParts) => {
      let farmId = msgParts[1];
      let farmer = msgParts[2];
      try {
        let farmers = await db.collection('farmers').find({farm_id: farmId, farmer_name: farmer}).toArray();
        if (farmers.length > 0) {
          return await buildSingleFarmMessage(db, farmId, farmer + ' has already been added to this farm.');
        }
        await db.collection('farmers').insertOne({
          farm_id: farmId,
          farmer_name: farmer
        });
        return await buildSingleFarmMessage(db, farmId, 'Added ' + farmer + '.');
      } catch (err) {
        console.error(err);
        return await 'There was an error updating the farm.';
      }
    },
    '/removemefromfarm': async (db, msgParts) => {
      let farmId = msgParts[1];
      let farmer = msgParts[2];
      try {
        let farmers = await db.collection('farmers').find({farm_id: farmId, farmer_name: farmer}).toArray();
        if (farmers.length === 0) {
          return await buildSingleFarmMessage(db, farmId, farmer + ' is not in this farm anyway.');
        }
        await db.collection('farmers').deleteOne({
          farm_id: farmId,
          farmer_name: farmer
        });
        return await buildSingleFarmMessage(db, farmId, 'Removed ' + farmer + '.');
      } catch (err) {
        console.error(err);
        return await 'There was an error updating the farm.';
      }
    },
    '/addfarmer': async (db, msgParts) => {
      let farmId = msgParts[1];
      let farmer = msgParts[2];
      try {
        let farmers = await db.collection('farmers').find({farm_id: farmId, farmer_name: farmer}).toArray();
        if (farmers.length > 0) {
          return await buildSingleFarmMessage(db, farmId, farmer + ' has already been added to this farm.');
        }
        await db.collection('farmers').insertOne({
          farm_id: farmId,
          farmer_name: farmer
        });
        return await buildSingleFarmMessage(db, farmId, 'Added ' + farmer + '.');
      } catch (err) {
        console.error(err);
        return await 'There was an error updating the farm.';
      }
    },
    '/removefarmer': async (db, msgParts) => {
      let farmId = msgParts[1];
      let farmer = msgParts[2];
      try {
        let farmers = await db.collection('farmers').find({farm_id: farmId, farmer_name: farmer}).toArray();
        if (farmers.length === 0) {
          return await buildSingleFarmMessage(db, farmId, farmer + ' is not in this farm anyway.');
        }
        await db.collection('farmers').deleteOne({
          farm_id: farmId,
          farmer_name: farmer
        });
        return await buildSingleFarmMessage(db, farmId, 'Removed ' + farmer + '.');
      } catch (err) {
        console.error(err);
        return await 'There was an error updating the farm.';
      }
    },
    '/setfarmlocation': async (db, msgParts) => {
      let farmId = msgParts[1];
      let location = msgParts[2];
      try {
        await db.collection('farms').updateOne({_id: farmId}, {$set: {location: location}});
        return await buildSingleFarmMessage(db, farmId, 'Updated farm location.');
      } catch (err) {
        console.error(err);
        return await 'There was an error updating the farm.';
      }
    },
    '/setfarmtime': async (db, msgParts) => {
      let farmId = msgParts[1];
      let dateAndTime = msgParts[2] + ' ' + msgParts[3];
      try {
        await db.collection('farms').updateOne({_id: farmId}, {$set: {date_and_time: dateAndTime}});
        return await buildSingleFarmMessage(db, farmId, 'Updated date and time.');
      } catch (err) {
        console.error(err);
        return await 'There was an error updating the farm.';
      }
    },
    '/deletefarm': async (db, msgParts) => {
      let farmId = msgParts[1];
      try {
        await db.collection('farms').updateOne({_id: farmId}, {$set: {current: 0}});
        return await 'Deleted farm.';
      } catch (err) {
        console.error(err);
        return await 'There was an error deleting the farm.';
      }
    },
    '/icametofarm': async (db, msgParts) => {
      let farmId = msgParts[1];
      let farmer = msgParts[2];
      try {
        await db.collection('farmers').updateOne({
          farm_id: farmId,
          farmer_name: farmer
        }, {$set: {farmer_name: farmer + ' - arrived'}});
        return await buildSingleFarmMessage(db, farmId, farmer + ' has arrived.');
      } catch (err) {
        console.error(err);
        return await 'There was an error deleting the farm.';
      }
    }
  },
  initiateChain: async(command, db, message) => {
    let split = message.text.split(' ');
    let msgParts = split.length > 1 ? split.slice(1, split.length) : [];
    if(msgParts.length < selectionQuestions[command].parts) {
      return await buildResponse(message.chat.id, selectionQuestions[command].error);
    }
    if(selectionQuestions[command].isSelfService) {
      msgParts.push('@' + message.from.username);
    }
    let keyboardMarkup =
      await buildFarmListKeyboard(command, db, message.chat.id, msgParts);
    if(keyboardMarkup) {
      return await buildResponse(message.chat.id, selectionQuestions[command].response, keyboardMarkup);
    } else {
      return await buildResponse(message.chat.id, Constants.NO_FARMS_MSG);
    }

  }

};

async function sendHttp(url, messageBody) {
  try {
    let response = await axios.post(url, messageBody, { headers: {'Content-Type': 'application/json'}});
    return await Constants.GEN_RESP;
  } catch(err) {
    console.error(err);
    return await Constants.GEN_RESP;
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
function buildKillKeyboardResponse(chatId, messageId) {
  return {
    chat_id: chatId,
    message_id: messageId,
    reply_markup: { inline_keyboard: [] }
  };
}
async function buildSingleFarmMessage(db, farmId, extra = undefined) {
  try {
    let farms = await db.collection('farms').find({_id: farmId}).toArray();
    let farmers = await db.collection('farmers').find({farm_id: farmId}).toArray();
    let farm = farms[0];
    let responseText = extra ? extra + '\n' : '';
    responseText += 'Farm - ' + farm['location'] + ' ' + farm['date_and_time'] + '\n' +
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
async function buildFarmListKeyboard(command, db, chatId, messageParts = []) {
  let inlineKeyboard = [];
  try {
    let farms = await db.collection('farms').find({farm_group: chatId, current: 1}).toArray();
    if(farms.length > 0) {
      farms.forEach(farm => {
        inlineKeyboard.push([{
          text: farm['location'] + ' ' + farm['date_and_time'],
          callback_data: command + '|' + farm['_id'] + '|' + messageParts.join('|')
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
      let messageId = chatBody.message.message_id;
      /*if(chatId !== -27924249) {
        return sendHttp(TG_SENDMESSAGE, buildResponse(chatId, Constants.MAINTENANCE_MSG));
      }*/
      let queryParts = chatBody.data.split('|');
      cmd = queryParts[0];
      if(!messageProcessor.secondary[cmd]) {
        await sendHttp(TG_EDITMARKUP, buildKillKeyboardResponse(chatId, messageId));
        return sendHttp(TG_SENDMESSAGE, buildResponse(chatId, 'Sorry, I didn\'t understand that command.'));
      } else {
        let txt = await messageProcessor.secondary[cmd](db, queryParts);
        await sendHttp(TG_EDITMARKUP, buildKillKeyboardResponse(chatId, messageId));
        return sendHttp(TG_SENDMESSAGE, buildResponse(chatId, txt));
      }
    } else {
      let chatId = chatBody.message.chat.id;
      /*if(chatId !== -27924249) {
        return sendHttp(TG_SENDMESSAGE, buildResponse(chatId, Constants.MAINTENANCE_MSG));
      }*/
      let splitByAt = chatBody.message.text.split('@');
      let firstPart = splitByAt[0].split(' ');
      cmd = firstPart[0];
      if(!messageProcessor[cmd] && !selectionQuestions[cmd]) {
        return sendHttp(TG_SENDMESSAGE, buildResponse(chatId, 'Sorry, I didn\'t understand that command.'));
      }
      if(selectionQuestions[cmd]) {
        let chainResponse = await messageProcessor.initiateChain(cmd, db, chatBody.message);
        return sendHttp(TG_SENDMESSAGE, chainResponse);
      } else {
        let responseText = await messageProcessor[cmd](db, chatBody.message);
        return sendHttp(TG_SENDMESSAGE, buildResponse(chatId, responseText));
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
