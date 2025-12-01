const popContainer = document.querySelector("#loopCarousel");
const popCards = popContainer.querySelectorAll(".BookCard");

let popIndex = 0;
const positions = ["center","right1","right2","left2","left1"]; // نفس فكرة الدور

function updatePopularCarousel(){
    popCards.forEach(card =>
        card.classList.remove("center","right1","right2","left1","left2","hiddenCard")
    );

    popCards.forEach((card,i)=>{
        let offset = (i - popIndex + popCards.length) % popCards.length;

        if(offset < positions.length){
            card.classList.add(positions[offset]);
        } else {
            card.classList.add("hiddenCard");
        }
    });
}

// أول مرة
updatePopularCarousel();

// دوران تلقائي كل 2.5 ثانية
setInterval(()=>{
    popIndex = (popIndex + 1) % popCards.length;
    updatePopularCarousel();
},2500);
