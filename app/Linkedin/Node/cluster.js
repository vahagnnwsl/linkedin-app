const cluster = require('cluster');
const fs = require('fs');
const axios = require('axios');
const EventSource = require('eventsource');


let strUsers = fs.readFileSync('./storage/linkedin/linkedin_users.json');
let users = JSON.parse(strUsers);



require('dotenv').config()

if (cluster.isMaster) {


    users.map(function (user) {

        cluster.schedulingPolicy = cluster.SCHED_NONE;

        const worker = cluster.fork();

        worker.on('exit', (code, signal) => {
            console.log('exit', Object.keys(cluster.workers).length)
            let newWorker = cluster.fork()
            newWorker.send({data: user, url: process.env.APP_URL})
        });

        worker.send({data: user, url: process.env.APP_URL});
    })


} else if (cluster.isWorker) {
    process.on('message', (msg) => {
        console.log('PID', process.pid, msg)


        let strCookie = fs.readFileSync('./storage/linkedin/cookies/' + msg.data.login + '.json');
        let cookies = JSON.parse(strCookie);
        let key = 'com.linkedin.realtimefrontend.DecoratedEvent';

        let eventSourceInitDict = {
            headers: {
                'accept': 'text/event-stream',
                'accept-encoding': 'gzip, deflate, br',
                'accept-language': 'en-US,en;q=0.9',
                'origin': 'https://www.linkedin.com',
                'referer': 'https://www.linkedin.com/',
                'cookie': cookies.str,
                'csrf-token': cookies.crfToken,
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
                            axios.post(msg.url + `/api/conversations`, {
                                payload: payload,
                                login: msg.data.login
                            }).then((response) => {
                                console.log(response.data)
                            }).catch((e) => {
                                console.log(e.response)
                            })
                        }
                    }
                }
            }
        };

        es.onerror = err => {
            console.log('EventSource error: ', err);
        };

    });
}



