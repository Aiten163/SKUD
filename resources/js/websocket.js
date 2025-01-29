window.Echo.channel("delivery").listen("PackageSent", (event) => {
    console.log(event);
});
