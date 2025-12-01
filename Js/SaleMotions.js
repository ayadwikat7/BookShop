const saleCards = document.querySelectorAll(".SaleCard");
let maxDiscount = -1;
let bestCard = null;

// Extract % and detect highest discount
saleCards.forEach(card=>{
    let percent = card.querySelector(".discount-badge").innerText.replace("%","").replace("-","");
    percent = parseInt(percent);

    if(percent > maxDiscount){
        maxDiscount = percent;
        bestCard = card;
    }
});

// Apply attention effect to highest discount
if(bestCard){
    bestCard.classList.add("hotSale");
}
