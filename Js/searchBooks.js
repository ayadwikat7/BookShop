document.addEventListener("DOMContentLoaded", () => {

    const searchInput = document.querySelector(".SearchInput");
    const books = document.querySelectorAll(".SaleCard");

    searchInput.addEventListener("keyup", () => {

        const keyword = searchInput.value.toLowerCase();

        books.forEach(book => {

            const title = book.querySelector("h2").textContent.toLowerCase();
            const author = book.querySelector(".author span").textContent.toLowerCase();
            const desc = book.querySelector(".desc").textContent.toLowerCase();

            if (
                title.includes(keyword) ||
                author.includes(keyword) ||
                desc.includes(keyword)
            ) {
                book.parentElement.style.display = "block";
            } else {
                book.parentElement.style.display = "none";
            }

        });

    });

});
