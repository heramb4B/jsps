<?php
/**
 * includes/footer.php
 * Site-wide footer — included by every public page.
 */
?>

<footer class="site-footer">
    <div class="container-xl">
        <div class="footer-grid">

            <!-- Brand -->
            <div class="footer-brand">
                <div class="logo-box footer-logo">JS<br>PS</div>
                <p>JSPS Accounting Solutions Pvt. Ltd. is a full-service CA firm providing end-to-end financial, taxation, and compliance services across India.</p>
                <div class="social-links">
                    <a href="#" class="social-link" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="social-link" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-link" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="social-link" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="<?= APP_URL ?>/">Home</a></li>
                    <li><a href="<?= APP_URL ?>/pages/about.php">About Us</a></li>
                    <li><a href="<?= APP_URL ?>/pages/services.php">Services</a></li>
                    <li><a href="<?= APP_URL ?>/pages/blog.php">Blog</a></li>
                    <li><a href="<?= APP_URL ?>/pages/contact.php">Contact Us</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div class="footer-col">
                <h4>Our Services</h4>
                <ul>
                    <li><a href="<?= APP_URL ?>/pages/services.php">GST Registration</a></li>
                    <li><a href="<?= APP_URL ?>/pages/services.php">Income Tax Return</a></li>
                    <li><a href="<?= APP_URL ?>/pages/services.php">Company Registration</a></li>
                    <li><a href="<?= APP_URL ?>/pages/services.php">TDS Compliance</a></li>
                    <li><a href="<?= APP_URL ?>/pages/services.php">MSME Registration</a></li>
                    <li><a href="<?= APP_URL ?>/pages/services.php">Accounting Services</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="footer-col">
                <h4>Contact Info</h4>
                <ul>
                    <li><a><i class="bi bi-geo-alt me-1"></i>302 Shree Complex, Linking Road,<br>&nbsp;&nbsp;&nbsp;&nbsp;Borivali West, Mumbai – 400092</a></li>
                    <li><a href="tel:+919876543210"><i class="bi bi-telephone me-1"></i>+91 98765 43210</a></li>
                    <li><a href="mailto:info@jspsaccounting.com"><i class="bi bi-envelope me-1"></i>info@jspsaccounting.com</a></li>
                    <li><a><i class="bi bi-clock me-1"></i>Mon–Sat: 9:30 AM – 7:00 PM</a></li>
                </ul>
            </div>

        </div>

        <div class="footer-bottom">
            <p>© <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</p> <p>Developed and Maintained By <a href="https://vyomark.co.in">Vyomark Digital Solutions</a></p>
            <p>
                <a href="<?= APP_URL ?>/pages/privacy.php">Privacy Policy</a> &nbsp;·&nbsp;
                <a href="<?= APP_URL ?>/pages/terms.php">Terms of Service</a>
            </p>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Global Script -->
<script src="<?= ASSETS_URL ?>/js/script.js"></script>

<?php if (!empty($extraJs)): ?>
    <?php foreach ($extraJs as $js): ?>
        <script src="<?= ASSETS_URL ?>/js/<?= e($js) ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
