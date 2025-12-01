// ================= Recommended Auto 3D Loop =================

const recContainer = document.querySelector("#recCarousel");
const recCards = recContainer.querySelectorAll(".BookCard");

let recIndex = 0;
const posREC = ["center","right1","right2","left2","left1"]; // نفس الأسلوب

function updateRecCarousel(){
    recCards.forEach(card =>
        card.classList.remove("center","right1","right2","left1","left2","hiddenCard")
    );

    recCards.forEach((card,i)=>{
        let offset = (i - recIndex + recCards.length) % recCards.length;

        if(offset < posREC.length){
            card.classList.add(posREC[offset]);
        } else {
            card.classList.add("hiddenCard");
        }
    });
}

// تشغيل في البداية
updateRecCarousel();

// تدوير تلقائي كل 3 ثواني
setInterval(()=>{
    recIndex = (recIndex + 1) % recCards.length;
    updateRecCarousel();
},3000);
