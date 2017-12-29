import Faye from 'faye';
import { DEBUG, FIRMAFON_ACCESS_TOKEN } from './config';

const client = new Faye.Client('https://pubsub.firmafon.dk/faye');

if (DEBUG) {
    Faye.logger = console.log;
}

client.addExtension({
    outgoing: (message, callback) => {
        /* eslint no-param-reassign: ["error", { "props": false }] */
        message.ext = { app: 'firmafon.live.example', access_token: FIRMAFON_ACCESS_TOKEN };
        callback(message);
    },
});

export default client;
