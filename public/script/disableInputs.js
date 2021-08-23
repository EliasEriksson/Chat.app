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

// TODO change this script to not rely on document.currentScript so defer can be added to script
const disableTargets = (targetElements) => {
    for (let targetElement of targetElements) {
        targetElement.disabled = false;
    }
}

const enableTargets = (targetElements) => {
    for (const targetElement of targetElements) {
        targetElement.disabled = true;
    }
}

const main = () => {
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

        if (enableElement.checked) {
            enableTargets(targetElements);
        } else {
            disableTargets(targetElements);
        }

        enableElement.addEventListener("change", (event) => {
            if (event.target.checked) {
                enableTargets(targetElements);
            }
        })

        disableElement.addEventListener("change", (event) => {
            if (event.target.checked) {
                disableTargets(targetElements)
            }
        })
    });
}

main();