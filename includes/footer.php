    <!-- Subscribe Section -->
    <div class="subscribe-section">
        <div class="subscribe-content">
            <h2><i class="fas fa-paper-plane"></i> Subscribe Now</h2>
            <form class="subscribe-form" method="POST" action="<?php echo SITE_URL; ?>/includes/subscribe.php">
                <input type="email" name="email" placeholder="Enter Your Email" required>
                <button type="submit" class="subscribe-btn">Subscribe Now <i class="fas fa-arrow-right"></i></button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Get in touch!</h3>
                <p>Wimply Dummy Text Of The Printing And Typesetting Industry Orem Ipsum Has Been The Industry's</p>
                <p><a href="#">View Map →</a></p>
            </div>
            
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>/pages/about.php">About us</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/donation.php">Give Donation</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/events.php">Education Support</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/events.php">Our Campaign</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/contact.php">Contact us</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Our Gallery</h3>
                <div class="gallery-grid">
                    <div class="gallery-item"><img src="<?php echo SITE_URL; ?>/assets/images/gallery1.jpg" alt="Gallery 1"></div>
                    <div class="gallery-item"><img src="<?php echo SITE_URL; ?>/assets/images/gallery2.jpg" alt="Gallery 2"></div>
                    <div class="gallery-item"><img src="<?php echo SITE_URL; ?>/assets/images/gallery3.jpg" alt="Gallery 3"></div>
                    <div class="gallery-item"><img src="<?php echo SITE_URL; ?>/assets/images/gallery4.jpg" alt="Gallery 4"></div>
                    <div class="gallery-item"><img src="<?php echo SITE_URL; ?>/assets/images/gallery5.jpg" alt="Gallery 5"></div>
                    <div class="gallery-item"><img src="<?php echo SITE_URL; ?>/assets/images/gallery6.jpg" alt="Gallery 6"></div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; Copyright 2023 Company.com All Rights Reserved</p>
        </div>
    </footer>

    <script src="<?php echo SITE_URL; ?>/assets/js/script.js"></script>
</body>
</html>
