<?php
session_start();
session_destroy();
header("Location: ../pages/dashboard.php"); // Redirect to the main dashboard
exit;
?>