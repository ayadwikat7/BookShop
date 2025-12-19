document.addEventListener("DOMContentLoaded", () => {

    const popup = document.getElementById("profilePopup");
    const popupTitle = document.getElementById("popupTitle");
    const popupInput = document.getElementById("popupInput");
    const closePopup = document.querySelector(".close-popup");
    const form = document.getElementById("popupForm");

    const messagePopup = document.getElementById("messagePopup");
    const messageText = document.getElementById("messageText");
    const closeMessage = document.getElementById("closeMessage");

    let currentField = "";

    /* ========= Helpers ========= */
    function showMessage(msg, isError = false) {
        messageText.innerText = msg;
        messageText.style.color = isError ? "#b00020" : "#2e7d32";
        messagePopup.style.display = "flex";
    }

    closeMessage.onclick = () => messagePopup.style.display = "none";

    /* ========= Open Edit Popup ========= */
    document.querySelectorAll(".buttopnprofile").forEach(btn => {
        btn.addEventListener("click", () => {
            currentField = btn.dataset.field;

            popupTitle.innerText = "Edit " + currentField;
            popupInput.value = "";
            popupInput.type = (currentField === "password") ? "password" : "text";

            popup.style.display = "flex";
        });
    });

    closePopup.onclick = () => popup.style.display = "none";

    popup.onclick = (e) => {
        if (e.target === popup) popup.style.display = "none";
    };

    /* ========= Validation ========= */
    function validate(field, value) {

        if (value.trim() === "") {
            return "Field cannot be empty";
        }

        if (field === "email") {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) return "Invalid email format";
        }

        if (field === "phone") {
            if (!/^[0-9+\-\s]{6,20}$/.test(value))
                return "Invalid phone number";
        }

        if (field === "password") {
            if (value.length < 6)
                return "Password must be at least 6 characters";
        }

        return null;
    }

    /* ========= Submit ========= */
    form.addEventListener("submit", (e) => {
        e.preventDefault();

        const value = popupInput.value;
        const error = validate(currentField, value);

        if (error) {
            showMessage(error, true);
            return;
        }

        fetch("updateProfile.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `field=${currentField}&value=${encodeURIComponent(value)}`
        })
            .then(res => res.json())
            .then(data => {
                popup.style.display = "none";

                if (data.success) {
                    showMessage("Updated successfully");
                    setTimeout(() => location.reload(), 1200);
                } else {
                    showMessage(data.error, true);
                }
            })
            .catch(() => {
                showMessage("Server error occurred", true);
            });
    });

});
const avatarBtn = document.getElementById("avatarBtn");
const avatarImg = document.getElementById("avatarImg");
const avatarInput = document.getElementById("avatarInput");
const editProfileBtn = document.getElementById("editProfileBtn");

/* فتح اختيار صورة */
avatarBtn.onclick = () => avatarInput.click();
editProfileBtn.onclick = () => avatarInput.click();

/* عند اختيار صورة */
avatarInput.addEventListener("change", () => {
    const file = avatarInput.files[0];

    if (!file) return;

    if (!file.type.startsWith("image/")) {
        showMessage("Please select a valid image", true);
        return;
    }

    if (file.size > 2 * 1024 * 1024) {
        showMessage("Image must be less than 2MB", true);
        return;
    }

    const formData = new FormData();
    formData.append("avatar", file);

    fetch("updateAvatar.php", {
        method: "POST",
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                avatarImg.src = "imge/" + data.avatar + "?t=" + new Date().getTime();
                showMessage("Profile picture updated");
            } else {
                showMessage(data.error, true);
            }
        })
        .catch(() => {
            showMessage("Upload failed", true);
        });
});
