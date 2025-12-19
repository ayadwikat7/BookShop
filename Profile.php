<?php
global $connection;
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit;
}

$userId = $_SESSION['user_id'];

/* ===== Fetch all user data ===== */
$stmt = $connection->prepare(
        "SELECT name, email, phone, location, favorite, role, avatar
     FROM users
     WHERE id = ? LIMIT 1"
);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    session_destroy();
    header("Location: auth.php");
    exit;
}

$user = $result->fetch_assoc();

$name     = $user['name'];
$email    = $user['email'];
$phone    = $user['phone'];
$location = $user['location'];
$favorite = $user['favorite'];
$role     = $user['role'];
$avatar   = $user['avatar'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Account</title>

    <link rel="stylesheet" href="cssfolder/footer.css">
    <link rel="stylesheet" href="cssfolder/profileStyle.css">
    <link rel="stylesheet" href="cssfolder/headerSecond.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

<?php include "topheader.php"; ?>

<div class="Profile">
    <div class="AllItemsProfile">

        <!-- Avatar -->
        <button type="button" class="imgeButton">
            <img src="imge/<?= htmlspecialchars($avatar) ?>"
                 class="avatar"
                 alt="Your Profile">
        </button>
        <!-- Avatar -->
        <button type="button" class="imgeButton" id="avatarBtn">
            <img src="imge/<?= htmlspecialchars($avatar) ?>"
                 class="avatar"
                 id="avatarImg"
                 alt="Your Profile">
        </button>

        <input type="file" id="avatarInput" accept="image/*" hidden>

        <!-- Basic Info -->
        <h2 class="name"><?= htmlspecialchars($name) ?></h2>
        <p class="Email"><?= htmlspecialchars($email) ?></p>

        <?php if ($role === 'admin'): ?>
            <p style="color:#a63e64; font-weight:600;">Admin Account</p>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="design">

            <div class="infor">
                <button type="button" class="buttopnprofile" data-field="email">
                    <i class="fa-solid fa-envelope"></i>Email
                </button>

                <button type="button" class="buttopnprofile" data-field="name">
                    <i class="fa-solid fa-user-tie"></i>Name
                </button>
            </div>

            <div class="infor">
                <button type="button" class="buttopnprofile" data-field="password">
                    <i class="fa-solid fa-lock"></i>Password
                </button>

                <button type="button" class="buttopnprofile" data-field="location">
                    <i class="fa-solid fa-location-dot"></i>Location
                </button>
            </div>

            <div class="infor">
                <button type="button" class="buttopnprofile" data-field="phone">
                    <i class="fa-solid fa-mobile-screen"></i>Phone
                </button>

                <button type="button" class="buttopnprofile" data-field="favorite">
                    <i class="fa-solid fa-heart"></i>Favorite
                </button>
            </div>

            <div class="infor">
                <button type="button" class="buttopnprofileEdit" id="editProfileBtn">
                    <i class="fa-solid fa-pen-to-square"></i>Edit Profile image
                </button>

                </button>

                <a href="logout.php">
                    <button type="button" class="buttopnprofile">
                        <i class="fa-solid fa-right-from-bracket"></i>Logout
                    </button>
                </a>
            </div>

        </div>
    </div>
</div>

<?php include "footer.php"; ?>

<!-- ===== Popup Modal ===== -->
<div id="profilePopup" class="profile-popup">
    <div class="popup-box">
        <span class="close-popup">&times;</span>

        <h3 id="popupTitle">Edit</h3>

        <form id="popupForm">
            <input type="text" id="popupInput" placeholder="">
            <button type="submit">Save Changes</button>
        </form>
    </div>
</div>

<script src="Js/profilePop.js"></script>
<!-- ===== Message Popup ===== -->
<div id="messagePopup" class="popup">
    <div class="popup-content">
        <span id="closeMessage">&times;</span>
        <p id="messageText"></p>
    </div>
</div>

</body>
</html>
