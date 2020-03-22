let Redis = require('ioredis'),
    io  = require('socket.io')(6001),
    redis = new Redis(),
    axios = require('axios');

redis.psubscribe('*', function (error, count) {
//
});
io.on('connection', function () {
    console.log('New connection');
    io.emit('channel', 'hello');
});

redis.on('pmessage', function (pattern, channel, message) {
    message = JSON.parse(message);
    io.emit(channel + ':' + message.event, message.data);
    // switch (message.event) {
    //     case 'newPlayer':
    //     case 'newAnswer':
    //     case 'notifyPlayer':
    //     case 'newGame':
    //         io.emit(channel + ':' + message.event, message.data);
    //         break;
    //     case 'startTimer':
    //         StartTimer(message.data.time);
    //         break;
    //     case 'startGame':
    //         io.emit(channel + ':' + message.event, message.data);
    //         FinishTimer(message.data.game_duration);
    //         break;
    // }
    console.log(channel + ':' + message.event)
});


