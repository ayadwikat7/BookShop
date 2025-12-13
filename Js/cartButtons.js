document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll(".AddRemoveCart").forEach(card => {

        const bookId = card.dataset.bookId;
        const qtySpan = card.querySelector(".qty");

        const sendAction = (action) => {
            fetch("cart_action.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `book_id=${bookId}&action=${action}`
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        qtySpan.textContent = data.qty;
                    }
                });
        };

        card.querySelector(".add-btn").addEventListener("click", e => {
            e.preventDefault();
            e.stopPropagation();
            sendAction("add");
        });

        card.querySelector(".remove-btn").addEventListener("click", e => {
            e.preventDefault();
            e.stopPropagation();
            sendAction("remove");
        });

    });

});
