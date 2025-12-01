const popup = document.getElementById("popupBox");
const popupInner = document.getElementById("popupInner");
const popupMessage = document.getElementById("popupMessage");
const closeBtn = document.getElementById("closePopup");
function showSuccess(msg){


    popup.style.display="flex";
    popupInner.className="popup-content success";
    popupMessage.innerHTML=`<i class="fa-solid fa-circle-check"></i> ${msg}`;
}

function showError(msg){


    popup.style.display="flex";
    popupInner.className="popup-content error";
    popupMessage.innerHTML=`<i class="fa-solid fa-circle-xmark"></i> ${msg}`;
}

function showWarning(msg){


    popup.style.display="flex";
    popupInner.className="popup-content warning";
    popupMessage.innerHTML=`<i class="fa-solid fa-triangle-exclamation"></i> ${msg}`;
}
closeBtn.onclick = ()=> popup.style.display = "none";
window.onclick = (e)=>{ if(e.target == popup) popup.style.display="none"; }