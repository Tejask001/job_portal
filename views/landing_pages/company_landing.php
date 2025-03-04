<?php
$page_title = "Company Landing Page";
include __DIR__ . '/../layouts/header.php';
?>

<h1>Welcome Companies!</h1>
<p>Post your job openings and find the best talent.</p>
<a href="<?php echo generate_url('views/auth/register.php'); ?>" class="btn">Post a Job</a>

<?php include __DIR__ . '/../layouts/footer.php'; ?>