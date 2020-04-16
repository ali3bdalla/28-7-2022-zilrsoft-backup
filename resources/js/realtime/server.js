import Echo from "laravel-echo"


window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '7ad4728a0b3b2e2b4232',
    cluster: 'ap2',
    forceTLS: true
});


var channel = window.Echo.channel('my-channel');
channel.listen('.my-event', function(data) {
    alert(JSON.stringify(data));
});

//
// window.Echo.private(`test_broadcast`)
//     .listen('TestBroadcastEvent', (e) => {
//         console.log(e);
//     });
