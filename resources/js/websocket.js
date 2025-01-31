window.Echo.channel("websocketTest")
    .listen('.messageSend', (e) => {
    console.log(e);
});
