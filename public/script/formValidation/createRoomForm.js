const checkbox = document.getElementById("password-protect");

const togglePrivate = () => {
    if (checkbox.checked) {
        document.getElementsByClassName("password1-password2-wrapper")[0].style.display = "block";
        checkbox.value = "private";
    } else {
        document.getElementsByClassName("password1-password2-wrapper")[0].style.display = "none";
        checkbox.value = "public";
    }
}

checkbox.addEventListener('change', togglePrivate);
window.addEventListener("load", () => {
    if (checkbox.value === "private") {
        checkbox.checked = true;
    } else {
        checkbox.value = false;
    }
    togglePrivate()
});