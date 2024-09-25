<?php
session_start();
$loggedin = false;
if (isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] === true && isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $nameOfStudent = $_SESSION["nameOfStudent"];
    $deptOfStudent = $_SESSION["deptOfStudent"];
    $firstName = $_SESSION["firstName"];
    $loggedin = true;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_navbar.css">
    <!-- <link rel="stylesheet" href="navbarMobile.css"> -->
    <link rel="stylesheet" href="style_hero.css">
    <link rel="stylesheet" href="style_about.css">
    <link rel="stylesheet" href="style_footer.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="assets/nss_logo_.png" type="image/x-icon">
    <title>Attendance Portal | NSS IITB</title>
</head>

<body>

    <div class="navbar">
        <div class="logo">
            <img src="assets/nss_logo_.png" alt="Logo">
            <span>NSS IITB</span>
        </div>

        <div class="links">
            <a href="admin_login.php"><button>Admin</button></a>
            <a href="https://docs.google.com/forms/d/e/1FAIpQLSdlQEjMT2q95-LvYhMgCZCOOfGorvXEobkBWWUt4e8HDrMPDw/viewform?pli=1"
                , target="_blank"><button>Review</button></a>


            <?php if (!$loggedin): ?>
                <a href="login.php"><button>Login</button></a>
            <?php else: ?>
                <a href="logout.php"><button>Logout</button></a>
            <?php endif; ?>

        </div>

    </div>

    <div class="hero">
        <div class="image">
            <img src="assets/hero.png" alt="Image here">
        </div>
        <div class="otherContent">


            <?php if (!$loggedin): ?>
                <h1>
                    Select Role
                </h1>
            <?php else: ?>
                <h1>
                    Hello, <?php echo "$firstName"; ?>
                </h1>
            <?php endif ?>


            <!-- Displaying the login in button only if the user is not logged in -->
            <?php if (!$loggedin): ?>
                <button id="to_loginPage">Login</button>
            <?php endif; ?>

            <!-- Displaying the registration button only if the user is not logged in -->
            <?php if (!$loggedin): ?>
                <button id="to_registerPage">Register</button>
            <?php endif; ?>

            <?php if (!$loggedin): ?>
                <button id="to_AdminPage">Admin</button>
            <?php endif; ?>

            <!-- Displaying the attendance and logout button only if the user is logged in -->

            <?php if ($loggedin): ?>
                <button id="to_attendancePage">Mark Attendance</button>
            <?php endif; ?>

            <?php if ($loggedin): ?>
                <button id="to_logout">Logout</button>
            <?php endif; ?>

        </div>
    </div>

    <div id="about">
        <h1>About</h1>
        <div class="textOfAbout">

            <h3>🌟 NOCS 01 & NOCS 02: A Freshman Essential at IIT Bombay
                🌟</h3>
            <br>
            <span>Welcome to your first year at IIT Bombay! As part of your
                academic journey, you'll embark on two essential courses,
                NOCS 01 and NOCS 02, crafted under the guidance of the
                Ministry of Youth Affairs and Sports, Government of India.
                🎓</span>

            <br><br>
            <span>These courses aren't just another check on your academic
                to-do list—they're a mandatory part of your undergraduate
                experience. And here's the exciting part: you get to choose
                your own adventure! 🚀
            </span>
            <br>
            <br>
            <span>To get started, you'll fill out a preference form. Based
                on availability, you'll be assigned to one of these amazing
                options.

                So, get ready to dive in, explore, and make the most of your
                first year at IIT Bombay! 🌟</span>
        </div>
        <a id="reviewButton"
            href="https://docs.google.com/forms/d/e/1FAIpQLSdlQEjMT2q95-LvYhMgCZCOOfGorvXEobkBWWUt4e8HDrMPDw/viewform?pli=1">
            <button>
                Leave A Review
            </button>
        </a>
    </div>

    <div class="footer_page">
        <div class="footer">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3735.023124586669!2d72.9110964750876!3d19.134860750122282!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7b91e8db254e1%3A0xeeff1f39c15c1d3!2sNSS%20IIT-Bombay!5e1!3m2!1sen!2sin!4v1724006593022!5m2!1sen!2sin"
                allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            <div class="other_content">
                <h2>Have A Question?</h2>
                <p><i class="fa-solid fa-location-dot"></i><span>NSS Room,
                        Old
                        SAC, IIT Bombay, Powai-400076</span></p>
                <p><i class="fa-solid fa-phone"></i><span>+919890733750,
                        +918690625357</span></p>
                <p><i class="fa-solid fa-envelope"></i>
                    <span><a href="mailto:team.nss.iitb@gmail.com">team.nss.iitb@gmail.com</a></span>
                </p>
                <h2>Find Us On</h2>
                <div> <span><a href="https://instagram.com/nssiitb"><img src="assets/insta.png" alt></a></span>
                    <span><a href="https://in.linkedin.com/company/nssiitbombay"><img src="assets/linkedin.png"
                                alt></a></span>
                    <span><a href="https://www.youtube.com/c/NationalServiceSchemeIITBombay"><img src="assets/yt.png"
                                alt></a></span>

                </div>
            </div>
            <hr style="width: 100%; margin-top: 20px;">
        </div>
    </div>

    <script src="script.js"></script>
</body>

</html>