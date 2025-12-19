document.addEventListener("DOMContentLoaded", function(){


    const input = document.querySelector(".AdminBox input");
    const btn = document.querySelector(".AdminBox button");
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


    btn.addEventListener("click", function(event){
        event.preventDefault();
        let code = input.value.trim();

        if(code == ""){
            showWarning("Please enter the admin code!");
        }
        else if(code !== "ADMIN123"){
            showError("Incorrect Code! Try Again.");
        }
        else{
            showSuccess("Login Successful! Redirecting...");
            setTimeout(()=> window.location.href="adminDashboard.php",1500);
        }
    });

});
