module.exports = {
  MAINTENANCE_MSG: 'SL ENL Farm bot is down for maintenance as we move to better tech. Please contact RamdeshLota for more information and perceived completion dates.',
  REPLY_CHAIN_CODES: {
    '/farming': '001',
    '/addmetofarm': '002',
    '/removemefromfarm': '003',
    '/deletefarm': '004',
    '/setfarmlocation': '005',
    '/setfarmtime': '006',
    '/addfarmer': '007',
    '/removefarmer': '008',
    '/getfarmlocation': '009',
    '/icametofarm': '010'
  },
  ERR_MSG: 'There was a problem with the bot. If you wish, you can report this as an issue at https://github.com/ramdesh/slenlfarmbot',
  GEN_RESP: {
    "statusCode" : 200,
    "body" : JSON.stringify({message: "This is the SL ENL Farm Bot"}),
    "isBase64Encoded": false
  },
  NO_FARMS_MSG: 'There are no farms associated with this group. Use /createfarm LOCATION DATE TIME to create a new farm.'
};