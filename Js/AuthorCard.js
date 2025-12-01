let currentIndex = 0;
const cards = document.querySelectorAll(".AuthorBlock");

cards[currentIndex].classList.add("active");

function scrollToNext(){
    if(currentIndex < cards.length-1){
        cards[currentIndex].classList.remove("active");
        currentIndex++;
        cards[currentIndex].classList.add("active");
    }
}
