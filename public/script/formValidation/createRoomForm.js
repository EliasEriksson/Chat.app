const checkbox = document.getElementById("password-protect");

checkbox.addEventListener('change', function () {
    if (this.checked) {
        document.getElementsByClassName("password1-password2-wrapper")[0].style.display = "block";

    } else {
        document.getElementsByClassName("password1-password2-wrapper")[0].style.display = "none";
    }
});