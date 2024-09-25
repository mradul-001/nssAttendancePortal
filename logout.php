<?php
session_start();

// destroying all session variables
session_unset();

session_destroy();

header("Location: index.php");

exit;