<?php
$page_title = "Job Seeker Landing Page";
include __DIR__ . '/../layouts/header.php';
?>

<h1>Welcome Job Seekers!</h1>
<p>Find your dream job here.</p>
<a href="<?php echo generate_url('views/jobs/job_listing.php'); ?>" class="btn">Browse Jobs</a>

<?php include __DIR__ . '/../layouts/footer.php'; ?>