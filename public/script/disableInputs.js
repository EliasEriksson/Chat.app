/**
 * to make use of this script the script tag must contain the attributes
 * data-enable, data-disable, data-targets
 *
 * data-enable is the ID of a radio button that should enable the targets
 * data-disable is the ID of a radio button that should disable the targets
 *
 * data-targets is space separated classes and all elements with the given
 * classes will be enabled and disabled by the enabler and disabler.
 */
main = () => {
    let script = document.currentScript;
    window.addEventListener("load", () => {
        let enableElement = document.getElementById(script.getAttribute("data-enable"));
        let disableElement = document.getElementById(script.getAttribute("data-disable"));

        let targetClasses = script.getAttribute("data-targets").split(" ");
        let targetElements = [];
        for (const targetClass of targetClasses) {
            for (let target of document.getElementsByClassName(targetClass)) {
                targetElements.push(target);
            }
        }

        enableElement.addEventListener("change", (event) => {
            if (event.target.checked) {
                for (let targetElement of targetElements) {
                    targetElement.disabled = false;
                }
            }
        })

        disableElement.addEventListener("change", (event) => {
            if (event.target.checked) {
                for (const targetElement of targetElements) {
                    targetElement.disabled = true;
                }
            }
        })
        console.log("room", script);
    });
}

main();