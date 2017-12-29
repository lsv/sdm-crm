import amqp from 'amqplib/callback_api';
import { AMQP_CONNECTION, AMQP_PRODUCER_NAME, DEBUG, FIRMAFON_COMPANY_ID } from './config';
import firmafon from './firmafon';

amqp.connect(AMQP_CONNECTION, (err1, conn) => {
    conn.createChannel((err2, ch) => {
        firmafon.subscribe(`/call2/company/${FIRMAFON_COMPANY_ID}`, async (message) => {
            ch.assertQueue(AMQP_PRODUCER_NAME);
            ch.sendToQueue(AMQP_PRODUCER_NAME, Buffer.from(JSON.stringify(message)));
            if (DEBUG) {
                console.log(`[x] Sent: ${JSON.stringify(message)}`);
            }
        });
    });
});
