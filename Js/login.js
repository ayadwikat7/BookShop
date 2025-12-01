// ====================  SELECT INPUTS  ====================
const email = document.querySelector('.emailInput');      // Email input
const pass  = document.querySelector('.passwordInput');   // Password input
const rem   = document.getElementById('remember');        // Checkbox

const loginBtn = document.querySelector(".mainLogin");    // زر تسجيل الدخول

// ==================== LOGIN ACTION ====================
loginBtn.addEventListener("click", function (e) {

    if(email.value.trim() === "" || pass.value.trim() === ""){
        e.preventDefault();
        shakeForm();
        showAlert("Please fill in all fields ❗", "error");
    }
    else {
        showAlert("Logged in Successfully ✨", "success");

        if(rem.checked){
            localStorage.setItem("savedEmail", email.value);
        }else{
            localStorage.removeItem("savedEmail");
        }
    }
});

// ====================  ANIMATION ON ERROR ====================
function shakeForm(){
    const box = document.querySelector(".login");
    box.style.animation = "shake .4s";
    setTimeout(()=> box.style.animation = "", 500);

    email.style.border = pass.style.border = "2px solid #ff4d6d";
}

// ====================  AUTO-FILL EMAIL ====================
window.onload = ()=>{
    if(localStorage.getItem("savedEmail")){
        email.value = localStorage.getItem("savedEmail");
        rem.checked = true;
    }
};

// ====================  ALERT MESSAGE BELOW FORM ====================
function showAlert(message, type){
    let alertBox = document.getElementById("msgBox");

    if(!alertBox){
        alertBox = document.createElement("div");
        alertBox.id = "msgBox";
        document.querySelector(".login").appendChild(alertBox);
    }

    alertBox.innerText = message;

    if(type === "success"){
        alertBox.style.background = "#8ccf84";
    }else{
        alertBox.style.background = "#ff6b81";
    }

    alertBox.style.padding = "8px";
    alertBox.style.marginTop = "12px";
    alertBox.style.color = "#fff";
    alertBox.style.borderRadius = "8px";
    alertBox.style.width = "80%";
    alertBox.style.textAlign = "center";
    alertBox.style.fontWeight = "600";
}
