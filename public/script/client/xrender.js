export function render(template, context) {
    for (let variable in context) {
        template = template.replace(new RegExp(`{{\\s*${variable}\\s*}}`, "gm"), context[variable]);
    }
    if (template.match(/{{\s*[^}]\s*}}/)) {
        return null;
    }
    let divElement = document.createElement("div");
    divElement.innerHTML = template;
    return divElement.firstChild;
}