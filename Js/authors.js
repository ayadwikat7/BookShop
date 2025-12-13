document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("authorsContainer");

    fetch("get_authors.php")
        .then(res => res.json())
        .then(authors => {
            authors.forEach(author => {
                const block = document.createElement("div");
                block.className = "AuthorBlock reveal";

                block.innerHTML = `
                    <div class="author-img">
                        <img src="${author.image}" alt="${author.name}">
                    </div>
                    <div class="author-info">
                        <p class="hello">HELLO! I'M</p>
                        <h1 class="name">${author.name}</h1>
                        <h3 class="role">${author.role ?? "Author"}</h3>
                        <p class="bio">${author.short_bio ?? ""}</p>

                        <a href="author.php?id=${author.id}" class="read-btn">Read Bio →</a>
                        <button class="scrollNext" onclick="scrollToNext()">↓ Scroll</button>
                    </div>
                `;

                container.appendChild(block);
            });
        })
        .catch(err => {
            console.error("Error loading authors:", err);
            container.innerHTML = "<p>Failed to load authors.</p>";
        });
});
