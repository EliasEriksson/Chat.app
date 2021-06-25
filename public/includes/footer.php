
    <footer>
        &copy; 2021 - Rights reserved osv<br>
        <a href="mailto:info@example.com">info@chatapp.com</a>
    </footer>

    <!-- <div class="wave">
        <img src="assets/img/wave.svg" alt="">
    </div> -->

    <script src="script/script.js" type="text/javascript"></script>

    <script>
        let socket = new Websocket("ws://localhost:5500");
        let buttonElement = document.getElementById("send-message");
        async function sendMessage(message) {
            await socket.send(message);
            let response = await socket.receive();
            console.log(response);
        }

        buttonElement.addEventListener("click", async () => {
            await sendMessage("Clicked the button");
        });

        window.addEventListener("load", async () => {
            await socket.open();
            await sendMessage("Opened the connection");
        });
    </script>
</body>

</html>