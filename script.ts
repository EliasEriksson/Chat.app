function multiplication(a:number, b:number){
    return a*b
}

const appEl = document.getElementById("app")!;

const chosen: number = 6;
for(let i = 0; i < 10; i++){
    let product: number = multiplication(chosen, i);
    appEl.innerHTML += `<span>${chosen} x ${i} = ${product}</span><br>`;
}
