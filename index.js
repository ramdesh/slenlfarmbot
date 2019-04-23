import axios from 'axios';
import mongodb from 'mongodb';

import Constants from './constants';

const MongoClient = mongodb.MongoClient;

exports.handler = async (req) => {
  function buildResponse(chatId, text) {
    return 'https://api.telegram.org/bot'+ Constants.BOT_ACCESS_TOKEN + '/sendMessage?chat_id='
      + chatId + '&text=' + text;
  }
  async function sendHttp(url) {
    let response = await axios.get(url);
    return {
      "statusCode" : 200,
      "body" : JSON.stringify({message: "This is the SL ENL Farm Bot"}),
      "isBase64Encoded": false
    };
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
