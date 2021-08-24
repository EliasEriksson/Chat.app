export const formatUnixTime = (unixTime) => {
    let dateFormat = new Intl.DateTimeFormat("sv-se", {
        minute: "numeric", second: "numeric"
    });
    return `Today at ${dateFormat.format(new Date(unixTime * 1000))}`;
}