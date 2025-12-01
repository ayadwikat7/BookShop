const catItems = document.querySelectorAll("#CategoriesCarousel .CatCard");
let catIndex = 0;
const catOrder = ["centerCat","rightCat","rightFar","leftFar","leftCat"];

function updateCatCarousel(){
    catItems.forEach(card => card.classList.remove(...catOrder,"hiddenCat"));

    catItems.forEach((card,i)=>{
        let pos = (i - catIndex + catItems.length) % catItems.length;
        pos < catOrder.length ?
            card.classList.add(catOrder[pos]) :
            card.classList.add("hiddenCat");
    });
}

updateCatCarousel();
setInterval(()=>{ catIndex = (catIndex+1)%catItems.length; updateCatCarousel();},2500);
