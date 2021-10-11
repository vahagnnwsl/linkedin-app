const key = 'com.linkedin.realtimefrontend.DecoratedEvent';
const EventSource = require('eventsource');
const axios = require('axios');
const fs = require('fs');
const cookie = JSON.parse(process.env.COOKIE);

const eventSourceInitDict = {
    headers: {
        'accept': 'text/event-stream',
        'accept-encoding': 'gzip, deflate, br',
        'accept-language': 'en-US,en;q=0.9',
        'origin': 'https://www.linkedin.com',
        'referer': 'https://www.linkedin.com/',
        'cookie': cookie.str,
        'csrf-token': cookie.crfToken,
        'user-agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36',
        'x-li-accept': 'application/vnd.linkedin.normalized+json+2.1',
        'x-li-page-instance': 'urn:li:page:feed_index_index;4x/cLqhlTY6xYQJ1S0umfQ==',
        'x-li-track': '{"clientVersion":"1.8.2073","mpVersion":"1.8.2073","osName":"web","timezoneOffset":4,"timezone":"Asia/Yerevan","deviceFormFactor":"DESKTOP","mpName":"voyager-web","displayDensity":1,"displayWidth":1920,"displayHeight":1080}',
        'x-restli-protocol-version': '2.0.0'
    }
};

var es = new EventSource('https://realtime.www.linkedin.com/realtime/connect', eventSourceInitDict);

es.onmessage = result => {

    const data = JSON.parse(result.data);
    if (data.hasOwnProperty(key)) {
        var eventContent = data[key];

        if (eventContent.hasOwnProperty('topic')) {

            if (eventContent.hasOwnProperty('publisherTrackingId')) {

                let payload = eventContent.payload;

                if (payload.included.length) {

                    axios.post(`${process.env.APP_URL}/api/conversations`, {
                        payload: payload,
                        login: process.env.ACCOUNT_LOGIN
                    }).then((d) => {
                        return d.data
                    }).catch((e) => {
                        console.log(err.message);
                    })
                }
            }
        }
    }
    sentLifeInfo(1)
};

es.onerror = err => {
    sentLifeInfo(0)
    console.log(err);
};

process.on('message', function (msg) {
    sentLifeInfo(0);
});

function sentLifeInfo( status ){
    axios.post(`${process.env.APP_URL}/api/accounts/${process.env.ACCOUNT_ID}`, {
        is_online: status
    }).then((resp) => {
        console.log(resp.data);
    })
}
