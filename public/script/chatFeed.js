// Not working 100% yet
// You can load messages when scrolling to the top of the feed,
// but it scrolls all the way down when content is loaded

const chatFeedEl = document.getElementById("chat-feed");

chatFeedEl.addEventListener("scroll", function (e) {
    if (chatFeedEl.scrollTop < 50) document.getElementById('load-history').click();
})

chatFeedEl.addEventListener("DOMNodeInserted", function (e) {
    chatFeedEl.scrollTop = chatFeedEl.scrollHeight;
});