import axios from 'axios';
import mongodb from 'mongodb';
import shortid from 'shortid';

import Constants from './constants';

const BOT_ACCESS_TOKEN = process.env.BOT_ACCESS_TOKEN;
const MONGO_URL = process.env.MONGO_URL;
const DB_NAME = process.env.DB_NAME;

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
//This array is used to store the questions to be asked when a user sends a message which would require secondary processing for farm selection.
//[0] - Farm selection question - this is used in later processing to identify which message the bot should reply to
// [1] - How many segments should there be other than the request message - this is used for validation.
// [2] - Response to send if validation on message segments fails.
const selectionQuestions = {
  '/farming': ['Which farm do you want the details of?', 0],
  '/addmetofarm': ['Which farm do you want to be added to?', 0],
  '/removemefromfarm': ['Which farm do you want to be removed from?', 0],
  '/deletefarm': ['Which farm do you want to delete?', 0],
  '/setfarmlocation': ['Which farm do you want to set the location for?', 1,
    'You need to specify a location. Use /setfarmlocation LOCATION.'],
  '/setfarmtime': ['Which farm do you want to set the time for?', 2,
     'You need to specify a date and time. Use /setfarmtime DATE TIME.'],
  '/addfarmer': ['Which farm do you want to add to?', 1,
    'You need to specify who you need to add. Use /addfarmer FARMER_NAME.'],
  '/removefarmer': ['Which farm do you want to remove from?', 1,
    'You need to specify who you need to remove. Use /removefarmer FARMER_NAME.'],
  '/icametofarm': ['Which farm did you come to?', 0]
};

const messageProcessor = {
  '/createfarm': (db, message) => {
    let messageParts = message.split(' ');
    let farmer = '@' + message.from.username;
    let location = messageParts[1] ? messageParts[1] : undefined;
    let time = messageParts[2] && messageParts[3] ?
      messageParts[1] + ' ' + messageParts[3] : undefined;
    if(!location) {
      return new Promise('You cannot set up a farm without specifying a location for it.' +
        'Use /createfarm LOCATION DATE TIME.');
    }
    if(!time) {
      return new Promise('You cannot set up a farm without specifying a date and time for it.' +
        'Use /createfarm LOCATION DATE TIME.');
    }
    let farmId = shortid.generate();
    db.collection('farms').insertOne({
      _id: farmId,
      date_and_time: time,
      location: location,
      creator: farmer,
      farm_group: message.chat.id,
      current: 1
    }, (err, r) => {
      if(err) {
        console.error(err);
      }
    });
    db.collection('farmers').insertOne({
      farm_id: farmId,
      farmer_name: farmer
    }, (err, r) => {
      if(err) {
        console.error(err);
      }
    });

  }

};

exports.handler = async (req) => {
  initDb().then((dbCon) => {
    db = dbCon;
  });
  async function sendHttp(url) {
    let response = await axios.get(url);
    return {
      "statusCode" : 200,
      "body" : JSON.stringify({message: "This is the SL ENL Farm Bot"}),
      "isBase64Encoded": false
    };
  }
  function buildResponse(chatId, text) {
    return 'https://api.telegram.org/bot'+ BOT_ACCESS_TOKEN + '/sendMessage?chat_id='
      + chatId + '&text=' + text;
  }

  let chatBody = JSON.parse(req.body);
  if(chatBody.message.text.startsWith('/')) {
    let chatId = chatBody.message.chat.id;
    return sendHttp(encodeURI(buildResponse(chatId, Constants.MAINTENANCE_MSG)));
  }
  return {
    "statusCode" : 200,
    "body" : JSON.stringify({message: "This is the SL ENL Farm Bot"}),
    "isBase64Encoded": false
  };

};
