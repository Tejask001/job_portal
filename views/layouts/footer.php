</div> <!-- Close container -->

<footer class="bg-dark text-light py-4">
    <div class="container bg-dark">
        <div class="row">

            <!-- About The Company -->
            <div class="col-md-4 mb-3">
                <h5> <img src="<?php echo generate_url('public/images/logo.jpeg'); ?>" alt="People's Consulting Logo" style="width: 80px; margin-right:10px">People's Consulting</h5>
                <p class="mb-0">Your trusted partner for job opportunities and career advancement.</p>
            </div>

            <!-- Quick Links -->
            <div class="col-md-4 mb-3">
                <h5><i class="bi bi-link-45deg me-1"></i> Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo generate_url('views/misc/privacy_policy.php'); ?>" class="text-light"><i class="bi bi-shield-lock me-1"></i> Privacy Policy</a></li>
                    <li><a href="<?php echo generate_url('views/misc/copyright.php'); ?>" class="text-light"><i class="bi bi-c-circle me-1"></i> Copyright Policy</a></li>
                </ul>
            </div>

            <!-- Follow Us -->
            <div class="col-md-4 mb-3">
                <h5><i class="bi bi-share-fill me-1"></i> Stay Connected</h5>
                <p>Follow us on social media for updates and opportunities!</p>
                <div class="d-flex gap-3">
                    <a href="https://wa.me/916283951834" class="text-light"><i class="bi bi-whatsapp fa-lg"></i></a>
                    <a href="https://www.linkedin.com/in/peoples-consulting-4a1b73220?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app" class="text-light"><i class="bi bi-linkedin fa-lg"></i></a>
                    <a href="https://www.instagram.com/peoples.consulting?igsh=MXdwejZuZXphZWt2OQ==" class="text-light"><i class="bi bi-instagram fa-lg"></i></a>
                </div>
            </div>
        </div>

        <hr class="border-secondary">

        <div class="text-center">
            <p class="mb-0"><i class="bi bi-c-circle me-1"></i> <?php echo date('Y'); ?> People's Consulting. All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>