<?php
session_start();
session_unset();
session_destroy();
session_start();
session_regenerate_id(true);
session_unset();
session_destroy();
header("Location: ../login/login.php");
echo"session detruite";
exit();
?>