const container = document.getElementById("authContainer");

document.getElementById("switchToRegister")?.addEventListener("click", () => {
    container.classList.add("register-mode");
});

document.getElementById("switchToLogin")?.addEventListener("click", () => {
    container.classList.remove("register-mode");
});
