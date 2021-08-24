export const exhaust = (xpathIterator) => {
    let elements = [];
    let element;
    while (element = xpathIterator.iterateNext()) {
        elements.push(element);
    }
    return elements;
}

export const addTimezone = () => {
    let elements = document.evaluate("//input[@name='timezone']", document);
    let inputElements = exhaust(elements);
    let timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    for (const inputElement of inputElements) {
        inputElement.value = timezone;
    }
}