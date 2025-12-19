const popup = document.getElementById("popupBox");
const popupMessage = document.getElementById("popupMessage");
const closeBtn = document.getElementById("closePopup");

// نجيب popup-content بالطريقة الصح
const popupContent = popup ? popup.querySelector(".popup-content") : null;

function showSuccess(msg){
    if(!popup || !popupContent || !popupMessage) return;

    popup.style.display = "flex";
    popupContent.className = "popup-content success";
    popupMessage.innerHTML = `<i class="fa-solid fa-circle-check"></i> ${msg}`;
}

function showError(msg){
    if(!popup || !popupContent || !popupMessage) return;

    popup.style.display = "flex";
    popupContent.className = "popup-content error";
    popupMessage.innerHTML = `<i class="fa-solid fa-circle-xmark"></i> ${msg}`;
}

function showWarning(msg){
    if(!popup || !popupContent || !popupMessage) return;

    popup.style.display = "flex";
    popupContent.className = "popup-content warning";
    popupMessage.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> ${msg}`;
}

if(closeBtn){
    closeBtn.onclick = () => popup.style.display = "none";
}

window.onclick = (e)=>{
    if(popup && e.target === popup){
        popup.style.display = "none";
    }
};
