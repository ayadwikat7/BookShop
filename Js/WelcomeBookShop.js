const text = "Welcome to BookShop";
let i = 0;
function typing(){
    if(i < text.length){
        document.querySelector(".textBox h1").innerHTML += text.charAt(i);
        i++;
        setTimeout(typing, 80);
    }
}
document.querySelector(".textBox h1").innerHTML = "";
typing();
