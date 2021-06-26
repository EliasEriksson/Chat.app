function multiplication(a, b) {
    return a * b;
}
var appEl = document.getElementById("app");
var chosen = 6;
for (var i = 0; i < 10; i++) {
    var product = multiplication(chosen, i);
    // appEl.innerHTML += `<span>${chosen} x ${i} = ${product}</span><br>`;
}
