export const formatUnixTime = (unixTime) => {
    let dateFormat = new Intl.DateTimeFormat("sv-se", {
        hour:"numeric", minute: "numeric"
    });
    return `Today at ${dateFormat.format(new Date(unixTime * 1000))}`;
}