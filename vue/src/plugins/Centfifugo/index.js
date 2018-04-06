import Centrifuge from 'centrifuge'
import store from './../../store'

export class CentManager extends Centrifuge {
  constructor(options) {
    super(options)
    this.subscriptions = {}
  }

  subscribe(channel, events) {
    this.subscriptions[channel] = super.subscribe(channel, events)

    return this.subscriptions[channel]
  }

  getSubscription(channel) {
    const sub = this.subscriptions[channel]
    if (!sub) {
      return null
    }

    return sub
  }
}

export function conf() {
  return {
    url: process.env.CENTRIFUGO_URL,
    user: String(store.getters.id),
    timestamp: String(store.getters.centrifugo_timestamp),
    token: store.getters.centrifugo_token,
    info: store.getters.centrifugo_info,
    debug: true,
    onTransportClose: (ctx) => {
      console.log('onTransportClose: ', ctx)
    }
  }
}
