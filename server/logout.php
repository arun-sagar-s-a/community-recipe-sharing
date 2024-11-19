<?php
session_start();
session_destroy();
header("Location: ../pages/index.php"); // Redirect to the main dashboard
exit;
?>