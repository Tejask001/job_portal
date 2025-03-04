<?php
if (isset($_SESSION['success_message'])) {
    echo '<div class="success">' . html_escape($_SESSION['success_message']) . '</div>';
    unset($_SESSION['success_message']); // Clear the message
}

if (isset($_SESSION['error_message'])) {
    echo '<div class="error">' . html_escape($_SESSION['error_message']) . '</div>';
    unset($_SESSION['error_message']); // Clear the message
}
