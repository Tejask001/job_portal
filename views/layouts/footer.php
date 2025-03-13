</div> <!-- Close container -->

<footer class="bg-dark text-light py-4">
    <div class="container bg-dark">
        <div class="row">
            <!-- About The Company -->
            <div class="col-md-3 mb-3">
                <h5><i class="bi bi-info-circle me-1"></i> About The Company</h5>
                <p class="mb-0">People Consultancy</p>
            </div>

            <!-- Quick Links -->
            <div class="col-md-3 mb-3">
                <h5><i class="bi bi-link-45deg me-1"></i> Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo generate_url('views/misc/privacy.php'); ?>" class="text-light"><i class="bi bi-shield-lock me-1"></i> Privacy Policy</a></li>
                    <li><a href="<?php echo generate_url('views/misc/copyright.php'); ?>" class="text-light"><i class="bi bi-c-circle me-1"></i> Copyright Policy</a></li>
                    <li><a href="<?php echo generate_url('views/misc/cookies.php'); ?>" class="text-light"><i class="bi bi-cookie me-1"></i> Cookie Policy</a></li>
                </ul>
            </div>

            <!-- Let's Connect -->
            <div class="col-md-3 mb-3">
                <h5><i class="bi bi-chat-dots me-1"></i> Let's Connect</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo generate_url('views/about.php'); ?>" class="text-light"><i class="bi bi-question-circle me-1"></i> About Us</a></li>
                    <li><a href="<?php echo generate_url('views/contact.php'); ?>" class="text-light"><i class="bi bi-envelope me-1"></i> Contact</a></li>
                </ul>
            </div>

            <!-- Follow Us -->
            <div class="col-md-3 mb-3">
                <h5><i class="bi bi-share-fill me-1"></i> Follow Us</h5>
                <div class="d-flex gap-3">
                    <a href="#" class="text-light"><i class="bi bi-facebook fa-lg"></i></a>
                    <a href="#" class="text-light"><i class="bi bi-twitter fa-lg"></i></a>
                    <a href="#" class="text-light"><i class="bi bi-linkedin fa-lg"></i></a>
                    <a href="#" class="text-light"><i class="bi bi-instagram fa-lg"></i></a>
                </div>
            </div>
        </div>

        <hr class="border-secondary">

        <div class="text-center">
            <p class="mb-0"><i class="bi bi-c-circle me-1"></i><?php echo date('Y'); ?> All rights reserved</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>