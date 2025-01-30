console.log(123123123132);
window.Echo.channel("messageToLock").listen('MessageToLock', (e) => {
    console.log(e);
});
