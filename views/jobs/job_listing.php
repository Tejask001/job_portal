<?php
$page_title = "Job Listings";
include __DIR__ . '/../layouts/header.php';

require_once __DIR__ . '/../../models/Job.php';
$jobModel = new Job($pdo);

// Handle search query
$searchTerm = $_GET['search'] ?? '';

// Handle filter values
$postingTypes = $_GET['posting_type'] ?? [];
$employmentTypes = $_GET['employment_type'] ?? [];
$workTypes = $_GET['work_type'] ?? [];

// Determine current page
$page = $_GET['page'] ?? 1;
$page = max(1, intval($page));

// Determine which jobs to retrieve and get the total count
if ($searchTerm) {
    $jobs = $jobModel->searchJobs($searchTerm, $page);
    $totalJobs = $jobModel->getTotalSearchResults($searchTerm);
    if (!empty($postingTypes) || !empty($employmentTypes) || !empty($workTypes)) {
        $jobs = array_filter($jobs, function ($job) use ($postingTypes, $employmentTypes, $workTypes) {
            $postingTypeCondition = empty($postingTypes) || in_array($job['posting_type'], $postingTypes);
            $employmentTypeCondition = empty($employmentTypes) || in_array($job['employment_type'], $employmentTypes);
            $workTypeCondition = empty($workTypes) || in_array($job['work_type'], $workTypes);
            return $postingTypeCondition && $employmentTypeCondition && $workTypeCondition;
        });
    }
} elseif (!empty($postingTypes) || !empty($employmentTypes) || !empty($workTypes)) {
    $jobs = $jobModel->filterJobs($postingTypes, $employmentTypes, $workTypes, $page);
    $totalJobs = $jobModel->getTotalFilteredJobs($postingTypes, $employmentTypes, $workTypes);
} else {
    $jobs = $jobModel->getAllJobs(true, null, $page); // Get only approved jobs
    $totalJobs = $jobModel->getTotalJobs(true);
}

$totalPages = ceil($totalJobs / JOBS_PER_PAGE);

function format_text($text)
{
    $text = str_replace('_', ' ', $text);  // Replace underscores with spaces
    $text = ucwords($text);               // Capitalize the first letter of each word
    return html_escape($text);             // Escape HTML entities
}
?>

<div class="container-fluid bg-light py-5">
    <div class="container">
        <h1 class="mb-4"><i class="bi bi-briefcase me-2"></i> Job Listings</h1>

        <form action="" method="GET" class="mb-4">
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" name="search" placeholder="Search jobs..." value="<?php echo html_escape($searchTerm); ?>">
                <button class="btn btn-primary" type="submit"><i class="bi bi-binoculars"></i> Search</button>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <fieldset class="border p-3 rounded">
                        <legend class="float-none w-auto px-2"><i class="bi bi-tags"></i> Opportunity Type</legend>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="posting_type[]" value="regular_job" id="regular_job" <?php if (in_array('regular_job', $postingTypes)) echo 'checked'; ?>>
                            <label class="form-check-label" for="regular_job"><i class="bi bi-file-earmark-text"></i> Regular Job</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="posting_type[]" value="internship" id="internship" <?php if (in_array('internship', $postingTypes)) echo 'checked'; ?>>
                            <label class="form-check-label" for="internship"><i class="bi bi-mortarboard"></i> Internship</label>
                        </div>
                    </fieldset>
                </div>

                <div class="col-md-4">
                    <fieldset class="border p-3 rounded">
                        <legend class="float-none w-auto px-2"><i class="bi bi-clock"></i> Employment Status</legend>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="employment_type[]" value="fulltime" id="fulltime" <?php if (in_array('fulltime', $employmentTypes)) echo 'checked'; ?>>
                            <label class="form-check-label" for="fulltime"><i class="bi bi-sun"></i> Full-time</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="employment_type[]" value="parttime" id="parttime" <?php if (in_array('parttime', $employmentTypes)) echo 'checked'; ?>>
                            <label class="form-check-label" for="parttime"><i class="bi bi-moon"></i> Part-time</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="employment_type[]" value="contract" id="contract" <?php if (in_array('contract', $employmentTypes)) echo 'checked'; ?>>
                            <label class="form-check-label" for="contract"><i class="bi bi-moon"></i>Contract</label>
                        </div>
                    </fieldset>
                </div>

                <div class="col-md-4">
                    <fieldset class="border p-3 rounded">
                        <legend class="float-none w-auto px-2"><i class="bi bi-building"></i> Work Arrangement</legend>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="work_type[]" value="onsite" id="onsite" <?php if (in_array('onsite', $workTypes)) echo 'checked'; ?>>
                            <label class="form-check-label" for="onsite"><i class="bi bi-geo-alt"></i> On-site</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="work_type[]" value="remote" id="remote" <?php if (in_array('remote', $workTypes)) echo 'checked'; ?>>
                            <label class="form-check-label" for="remote"><i class="bi bi-ethernet"></i> Remote</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="work_type[]" value="hybrid" id="hybrid" <?php if (in_array('hybrid', $workTypes)) echo 'checked'; ?>>
                            <label class="form-check-label" for="hybrid"><i class="bi bi-diagram-3"></i> Hybrid</label>
                        </div>
                    </fieldset>
                </div>
            </div>

            <button type="submit" class="btn btn-success"><i class="bi bi-funnel-fill"></i> Apply Filters</button>
        </form>

        <?php if (empty($jobs)): ?>
            <div class="alert alert-info" role="alert"><i class="bi bi-info-circle me-2"></i> No jobs available matching your criteria. Please check back later.</div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($jobs as $job): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-file-earmark-text"></i> <?php echo html_escape($job['title']); ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted"><i class="bi bi-building"></i> <?php echo html_escape($job['company_name']); ?></h6>
                                <p class="card-text">
                                    <i class="bi bi-tag"></i> <strong>Opportunity Type:</strong> <?php echo format_text($job['posting_type']); ?><br>
                                    <i class="bi bi-clock"></i> <strong>Employment Status:</strong> <?php echo format_text($job['employment_type']); ?><br>
                                    <i class="bi bi-diagram-3"></i> <strong>Work Arrangement:</strong> <?php echo format_text($job['work_type']); ?><br>
                                    <i class="bi bi-person-bounding-box"></i> <strong>Age:</strong> <?php echo html_escape($job['age']); ?><br>
                                    <i class="bi bi-stars"></i> <strong>Experience:</strong> <?php echo html_escape($job['experience']); ?><br>
                                    <i class="bi bi-card-text"></i> <?php echo substr(html_escape($job['description']), 0, 100); ?>...<br>
                                    <i class="bi bi-geo-alt"></i> <strong>Location:</strong> <?php echo html_escape($job['job_location']); ?>
                                </p>
                                <a href="<?php echo generate_url('views/jobs/job_details.php?id=' . $job['id']); ?>" class="btn btn-primary"><i class="bi bi-eye"></i> View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Pagination Links -->
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php
                    $startPage = max(1, $page - floor(PAGINATION_LINKS / 2));
                    $endPage = min($totalPages, $startPage + PAGINATION_LINKS - 1);
                    $startPage = max(1, $endPage - PAGINATION_LINKS + 1); // Adjust start page
                    ?>

                    <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="<?php echo getCurrentUrlWithNewPage($page - 1); ?>" aria-label="Previous">
                            <span aria-hidden="true"><i class="bi bi-arrow-left"></i></span>
                        </a>
                    </li>

                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                            <a class="page-link" href="<?php echo getCurrentUrlWithNewPage($i); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php if ($page >= $totalPages) echo 'disabled'; ?>">
                        <a class="page-link" href="<?php echo getCurrentUrlWithNewPage($page + 1); ?>" aria-label="Next">
                            <span aria-hidden="true"><i class="bi bi-arrow-right"></i></span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php
function getCurrentUrlWithNewPage($page)
{
    $url = $_SERVER['REQUEST_URI'];
    $url_parts = parse_url($url);
    $query = [];

    if (!empty($url_parts['query'])) {
        parse_str($url_parts['query'], $query);
    }

    $query['page'] = $page;
    return $url_parts['path'] . '?' . http_build_query($query);
}
?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>