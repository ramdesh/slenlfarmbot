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
const sequenceCommands = [
  '/farming',
  '/addmetofarm',
  '/removemefromfarm',
  '/deletefarm',
  '/setfarmlocation',
  '/setfarmtime',
  '/addfarmer',
  '/removefarmer',
  '/icametofarm'
];
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
  replyChainHandler: {
    '/farming': async(db, message) => {

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
    return {
      "statusCode" : 200,
      "body" : JSON.stringify({message: "This is the SL ENL Farm Bot"}),
      "isBase64Encoded": false
    };
  }


}
function buildResponse(chatId, text) {
  return {
    chat_id: chatId,
    text: text
  };
}
async function buildSingleFarmMessage(db, farmId) {
  try {
    let farm = await db.collection('farms').find({_id: farmId}).limit(1).toArray()[0];
    let farmers = await db.collection('farmers').find({farm_id: farmId}).toArray();
    let responseText = 'Farm - ' + farm['location'] + ' ' + farm['date_and_time'] + '\n' +
      'Farm creator - ' + farm['creator'];
    for(let i = 1; i <= farmers.length; i++) {
      responseText += '\n' + i + '. '+ farmers[i-1]['farmer_name'];
    }
    return await responseText;
  } catch(err) {
    console.error(err);
    return await 'There was a problem fetching farm details';
  }
}
async function buildFarmListKeyboard(db, chatId) {

}

exports.handler = async (req) => {
  db = await initDb();
  try {
    let chatBody = JSON.parse(req.body);
    let chatId = chatBody.message.chat.id;
    let splitByAt = chatBody.message.text.split('@');
    let firstPart = splitByAt[0].split(' ');
    if(!messageProcessor[firstPart[0]]) {
      return sendHttp(buildResponse(chatId, 'Sorry, I didn\'t understand that command.'));
    }
    let responseText = await messageProcessor[firstPart[0]](db, chatBody.message);
    return sendHttp(buildResponse(chatId, responseText));
  } catch(err) {
    console.error(err);
    return {
      "statusCode" : 200,
      "body" : JSON.stringify({message: "This is the SL ENL Farm Bot"}),
      "isBase64Encoded": false
    };
  }



};
