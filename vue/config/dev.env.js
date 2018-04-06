'use strict'
const merge = require('webpack-merge')
const prodEnv = require('./prod.env')

module.exports = merge(prodEnv, {
  NODE_ENV: '"development"',
  BASE_API: '"http://api.phasty.local"',  
  CENTRIFUGO_URL: '"ws://centrifugo.phasty.local/connection/websocket"',
  CENTRIFUGO_DEBUG: true
})
