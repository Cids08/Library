<?php
session_start();
// Destroy all session data
session_unset();
session_destroy();

// Redirect to login/signup page
header("Location: auth.php");
exit();
?>