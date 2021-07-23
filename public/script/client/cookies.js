export function getSessionID() {
    let cookies = document.cookie;
    let cookieName, cookieValue;
    for (let cookie of cookies.split(";")) {
        [cookieName, cookieValue] = cookie.trim().split("=");
        if (cookieName === "PHPSESSID") {
            return cookieValue;
        }
    }
    return "";
}
