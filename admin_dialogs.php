<?php

global $connection;
include "db.php";?>

 <!-- ================= ADD AUTHOR ================= -->


<dialog id="AddAuthor" class="DialogS">
    <h2 class="DialogTitle" id="AuthorDialogTitle">Add Author</h2>


    <form method="post"
          action="actions/add_author.php"
          enctype="multipart/form-data">

        <!-- Author Name -->
        <div class="dialogDiv">
            <label>Author Name:</label>
            <input type="text"
                   name="name"
                   placeholder="Enter author name"
                   required>
        </div>

        <!-- Birthdate -->
        <div class="dialogDiv">
            <label>Birthdate:</label>
            <input type="date"
                   name="birthdate"
                   required>
        </div>

        <!-- Rating -->
        <div class="dialogDiv">
            <label>Rating (0 - 5):</label>
            <input type="number"
                   name="rating"
                   step="0.1"
                   min="0"
                   max="5"
                   placeholder="4.8"
                   required>
        </div>

        <!-- Books Written -->
        <div class="dialogDiv">
            <label>Books Written:</label>
            <input type="number"
                   name="books_written"
                   min="0"
                   placeholder="Number of books"
                   required>
        </div>

        <!-- Author Image -->
        <div class="dialogDiv CenterRow">
            <label for="imgUploadAuthor" class="UploadImage">
                Upload Author Image
            </label>
            <input type="file"
                   name="image"
                   id="imgUploadAuthor"
                   accept="image/*"
                   required>
        </div>

        <!-- Buttons -->
        <div class="dialogDiv ButtonsRow">
            <button type="submit" class="UploadBtn">
                Add Author
            </button>

            <button type="button"
                    class="CancelBtn"
                    onclick="document.getElementById('AddAuthor').close()">
                Cancel
            </button>
        </div>

    </form>
</dialog>

<!-- ================= ADD BOOK ================= -->
<dialog id="AddBook" class="DialogS">
    <h2 class="DialogTitle" id="BookDialogTitle">Add Book</h2>

    <form method="post"
          action="actions/add_book.php"
          enctype="multipart/form-data">

        <!-- Book title -->
        <div class="dialogDiv">
            <div class="innerDiv">
                <label>Book Title:</label>
                <input type="text" name="title"
                       placeholder="Enter Book Title" required>
            </div>
        </div>

        <!-- Upload Image -->
        <div class="dialogDiv">
            <div class="innerDiv">
                <label>Upload Image:</label>
                <input type="file" name="book_image"
                       accept="image/*" required>
            </div>
        </div>

        <!-- Pages & Sections -->
        <div class="dialogDiv">
            <div class="innerDiv">
                <label>Pages:</label>
                <input type="number" name="pages">
            </div>

            <div class="innerDiv">
                <label>Sections:</label>
                <input type="number" name="sections">
            </div>
        </div>

        <!-- Author & Category (بالاسم) -->
        <div class="dialogDiv">
            <div class="innerDiv">
                <label>Author Name:</label>
                <input type="text" name="author_name"
                       placeholder="Enter Author Name" required>
            </div>

            <div class="innerDiv">
                <label>Category Name:</label>
                <input type="text" name="category_name"
                       placeholder="Enter Category Name" required>
            </div>
        </div>

        <!-- Description -->
        <div class="dialogDiv">
            <div class="innerDiv">
                <label>Description:</label>
                <textarea name="description" rows="4"
                          placeholder="Write description line by line..."></textarea>
            </div>
        </div>

        <!-- Price -->
        <div class="dialogDiv">
            <div class="innerDiv">
                <label>Price:</label>
                <input type="number" step="0.01"
                       name="price" required>
            </div>

            <div class="innerDiv">
                <label>New Price:</label>
                <input type="number" step="0.01"
                       name="newPrice">
            </div>
        </div>

        <!-- Rating & Stock -->
        <div class="dialogDiv">
            <div class="innerDiv">
                <label>Rating:</label>
                <input type="number" step="0.1"
                       name="rating">
            </div>

            <div class="innerDiv">
                <label>Stock:</label>
                <input type="number"
                       name="stock">
            </div>
        </div>

        <!-- Sale -->
        <div class="dialogDiv">
            <div class="innerDiv">
                <label>Is On Sale:</label>
                <select name="isSale">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>

            <div class="innerDiv">
                <label>Sale Value (%):</label>
                <input type="number" name="SaleValuePers">
            </div>
        </div>

        <!-- Buttons -->
        <div class="dialogDiv ButtonsRow">
            <input type="submit" name="add_book"
                   value="Submit Book" class="UploadBtn">

            <input type="button" value="Cancel"
                   class="CancelBtn"
                   onclick="document.getElementById('AddBook').close()">
        </div>

    </form>
</dialog>


<!-- ================= REMOVE BOOK ================= -->
<dialog id="RemoveBook" class="DialogS">
    <h2 class="DialogTitle">Remove Book</h2>

    <form method="post" action="actions/remove_book.php">

        <div class="dialogDiv">
            <div class="innerDiv">
                <label>Book Title:</label>
                <input type="text"
                       name="book_title"
                       placeholder="Enter Book Title"
                       required>
            </div>
        </div>

        <div class="dialogDiv ButtonsRow">
            <label for="RemoveBookBtn" class="UploadBtn">Remove Book</label>
            <input type="submit"
                   name="remove_book"
                   id="RemoveBookBtn">

            <label for="CancelRemoveBook" class="CancelBtn">Cancel</label>
            <input type="button"
                   id="CancelRemoveBook"
                   onclick="document.getElementById('RemoveBook').close()">
        </div>

    </form>
</dialog>


<!-- ================= REMOVE AUTHOR ================= -->
<dialog id="RemoveAuthor" class="DialogS">
    <h2 class="DialogTitle">Remove Author</h2>

    <form method="post" action="actions/remove_author.php">

        <div class="dialogDiv">
            <div class="innerDiv">
                <label>Author Name:</label>
                <input type="text"
                       name="author_name"
                       placeholder="Enter Author Name"
                       required>
            </div>
        </div>

        <div class="dialogDiv ButtonsRow">
            <button type="submit"
                    name="remove_author"
                    class="UploadBtn">
                Remove Author
            </button>

            <button type="button"
                    class="CancelBtn"
                    onclick="document.getElementById('RemoveAuthor').close()">
                Cancel
            </button>
        </div>

    </form>
</dialog>


<!-- ================= ADD CATEGORY ================= -->
<dialog id="AddCategory" class="DialogS">
    <h2 class="DialogTitle" id="CategoryDialogTitle">Add Category</h2>

    <form method="post"
          action="actions/add_category.php"
          enctype="multipart/form-data">

        <div class="dialogDiv">
            <div class="innerDiv">
                <label>Category Name:</label>
                <input type="text"
                       name="category_name"
                       placeholder="Enter Category Name"
                       required>
            </div>
        </div>

        <div class="dialogDiv CenterRow">
            <label for="imgUploadCategory" class="UploadImage">
                Upload Image
            </label>
            <input type="file"
                   name="category_image"
                   id="imgUploadCategory"
                   accept="image/*"
                   required>
        </div>

        <div class="dialogDiv ButtonsRow">
            <button type="submit"
                    name="add_category"
                    class="UploadBtn">
                Add Category
            </button>

            <button type="button"
                    class="CancelBtn"
                    onclick="document.getElementById('AddCategory').close()">
                Cancel
            </button>
        </div>

    </form>
</dialog>

<!-- ================= REMOVE CATEGORY ================= -->
<dialog id="RemoveCategory" class="DialogS">
    <h2 class="DialogTitle">Remove Category</h2>

    <form method="post" action="actions/remove_category.php">

        <div class="dialogDiv">
            <div class="innerDiv">
                <label>Category Name:</label>
                <input type="text"
                       name="category_name"
                       placeholder="Enter Category Name"
                       required>
            </div>
        </div>

        <div class="dialogDiv ButtonsRow">
            <button type="submit"
                    name="remove_category"
                    class="UploadBtn">
                Remove Category
            </button>

            <button type="button"
                    class="CancelBtn"
                    onclick="document.getElementById('RemoveCategory').close()">
                Cancel
            </button>
        </div>

    </form>
</dialog>


<!-- ================= ADD SALE ================= -->
<!-- ================= ADD SALE ================= -->
<?php
$booksForSale = $connection->query("
    SELECT id, title, price
    FROM books
    WHERE isSale = 0
    ORDER BY title ASC
");
?>

<dialog id="AddSale" class="DialogS">
    <h2 class="DialogTitle" id="SalesDialogTitle">Add New Sale</h2>

    <form method="post" action="actions/add_sale.php">

        <div class="dialogDiv">
            <label style="font-weight: bold;">Select Books for Sale:</label>

            <div class="BooksCheckboxList">
                <?php if ($booksForSale && $booksForSale->num_rows > 0): ?>
                    <?php while ($book = $booksForSale->fetch_assoc()): ?>
                        <label class="BookCheckItem">
                            <input type="checkbox"
                                   name="book_ids[]"
                                   value="<?= $book['id'] ?>">

                            <span>
                                <?= htmlspecialchars($book['title']) ?>
                                <small>($<?= number_format($book['price'], 2) ?>)</small>
                            </span>
                        </label>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="color:#666;">No books available for sale</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="dialogDiv">
            <label>Sale Percentage (%):</label>
            <input type="number"
                   name="sale_percent"
                   min="1"
                   max="90"
                   required>
        </div>

        <div class="dialogDiv ButtonsRow">
            <button type="submit"
                    name="add_sale"
                    class="UploadBtn">
                Apply Sale
            </button>

            <button type="button"
                    class="CancelBtn"
                    onclick="document.getElementById('AddSale').close()">
                Cancel
            </button>
        </div>

    </form>
</dialog>





<!-- ================= REMOVE SALE ================= -->
<?php
$booksOnSale = $connection->query("
    SELECT id, title, price, newPrice, SaleValuePers
    FROM books
    WHERE isSale = 1
    ORDER BY title ASC
");
?>

<dialog id="RemoveSale" class="DialogS">
    <h2 class="DialogTitle">Remove Sale</h2>

    <form method="post" action="actions/remove_sale.php">

        <div class="dialogDiv">
            <label style="font-weight:bold;">Books on Sale:</label>

            <div class="BooksCheckboxList">

                <?php if ($booksOnSale && $booksOnSale->num_rows > 0): ?>
                    <?php while ($book = $booksOnSale->fetch_assoc()): ?>
                        <label class="BookCheckItem">
                            <!-- ✅ checked افتراضياً -->
                            <input type="checkbox"
                                   name="book_ids[]"
                                   value="<?= $book['id'] ?>"
                                   checked>

                            <div class="BookCard">
                                <div class="BookTitle">
                                    <?= htmlspecialchars($book['title']) ?>
                                </div>

                                <div class="BookPrice">
                                    <del>$<?= number_format($book['price'], 2) ?></del>
                                    →
                                    <strong>$<?= number_format($book['newPrice'], 2) ?></strong>
                                    (<?= $book['SaleValuePers'] ?>%)
                                </div>
                            </div>
                        </label>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="color:#666;">No active sales</p>
                <?php endif; ?>

            </div>
        </div>

        <div class="dialogDiv ButtonsRow">
            <button type="submit"
                    name="remove_sale"
                    class="UploadBtn">
                Apply Changes
            </button>

            <button type="button"
                    class="CancelBtn"
                    onclick="document.getElementById('RemoveSale').close()">
                Cancel
            </button>
        </div>

    </form>
</dialog>

<dialog id="ResultDialog" class="DialogS">
    <h2 class="DialogTitle" id="ResultTitle"></h2>
    <p id="ResultMessage" style="text-align:center;"></p>

    <div class="dialogDiv ButtonsRow">
        <button class="UploadBtn"
                onclick="document.getElementById('ResultDialog').close()">
            OK
        </button>
    </div>
</dialog>

<script>
    function showResult(title, message) {
        document.getElementById("ResultTitle").innerText = title;
        document.getElementById("ResultMessage").innerText = message;
        document.getElementById("ResultDialog").showModal();
    }
</script>
