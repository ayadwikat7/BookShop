document.querySelectorAll("[data-link]").forEach(item =>{
    item.addEventListener("click", function(){

        let account = localStorage.getItem("hasAccount") === "true";

        if(account){
            window.location.href = this.getAttribute("data-link");
        }else{
            showWarning("You must create an account before browsing this page! 📝");
        }

    });
});
