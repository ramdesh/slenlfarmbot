let https = require('https');

const ACCESS_TOKEN = '112493740:AAHHFH1HHM95-HgSbVoibojcA6L3AsbhfaI';
const MAINTENANCE_MSG = 'SL ENL Farm bot is down for maintenance as we move to better tech. Please contact @RamdeshLota for more information and perceived completion dates.';

exports.handler = async (req) => {
  function buildResponse(chatId, text) {
    return 'https://api.telegram.org/bot'+ ACCESS_TOKEN + '/sendMessage?chat_id='
      + chatId + '&text=' + text;
  }
  function sendHttp(url) {
    https.get(url, resp => {
      resp.on('end', () => {
        console.log("request sent to tg");
      });
    }).on('error', err => {
      console.error(err);
    });
  }
  let chatId = req.message.chat.id;
  sendHttp(encodeURI(buildResponse(chatId, MAINTENANCE_MSG)));
  const response = {
    statusCode: 200,
    body: 'This is the Lambda version of the SL ENL Farm Bot',
  };

  return response;
};
