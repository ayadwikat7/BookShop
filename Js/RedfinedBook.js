const horSection = document.querySelector(".HorezantalBook");
const horBooks   = document.querySelectorAll(".HorezantalBook .Books");

let ii = 0;

function animateHorizontal(){

    horBooks.forEach(card => card.classList.remove("activeHor"));

    horBooks[ii].classList.add("activeHor");

    ii = (ii + 1) % horBooks.length;  // loop rotation
}

animateHorizontal();
setInterval(animateHorizontal, 1000); // كل 2.2 ثانية تتغير البطاقة
