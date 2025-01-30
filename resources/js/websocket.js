console.log('вебсокет');
window.Echo.channel("auth").listenToAll((event, data) => {
    console.log(event, data);
});
