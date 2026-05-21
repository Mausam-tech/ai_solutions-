<!-- ============================================================
     FOOTER
     ============================================================ -->
<footer class="site-footer">
    <div class="footer-top">
        <div class="container">
            <div class="row g-4">
                <!-- Col 1: Brand -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand d-flex align-items-center gap-2 mb-3">
                        <div class="brand-icon-wrap brand-icon-sm">
                            <i class="bi bi-cpu-fill"></i>
                        </div>
                        <span class="brand-name text-white">AI<span class="brand-accent">-Solutions</span></span>
                    </div>
                    <p class="footer-desc">
                        Leveraging AI to transform the digital employee experience — speeding up design,
                        engineering, and innovation across industries worldwide.
                    </p>
                    <div class="footer-socials d-flex gap-3 mt-3">
                        <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                        <a href="#" aria-label="Twitter/X"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div class="col-lg-2 col-md-6 col-6">
                    <h6 class="footer-heading">Quick Links</h6>
                    <ul class="footer-links">
                        <li><a href="<?= BASE_URL ?>/index.php">Home</a></li>
                        <li><a href="<?= BASE_URL ?>/services.php">Services</a></li>
                        <li><a href="<?= BASE_URL ?>/portfolio.php">Portfolio</a></li>
                        <li><a href="<?= BASE_URL ?>/articles.php">Articles</a></li>
                        <li><a href="<?= BASE_URL ?>/events.php">Events</a></li>
                        <li><a href="<?= BASE_URL ?>/contact.php">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Col 3: Services -->
                <div class="col-lg-3 col-md-6 col-6">
                    <h6 class="footer-heading">Our Services</h6>
                    <ul class="footer-links">
                        <li><a href="<?= BASE_URL ?>/services.php">AI Virtual Assistant</a></li>
                        <li><a href="<?= BASE_URL ?>/services.php">Digital Employee Platform</a></li>
                        <li><a href="<?= BASE_URL ?>/services.php">AI Prototyping</a></li>
                        <li><a href="<?= BASE_URL ?>/services.php">Process Automation</a></li>
                        <li><a href="<?= BASE_URL ?>/services.php">Data Analytics</a></li>
                        <li><a href="<?= BASE_URL ?>/services.php">Industry AI Integration</a></li>
                    </ul>
                </div>

                <!-- Col 4: Contact -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="footer-heading">Get in Touch</h6>
                    <ul class="footer-contact-list">
                        <li>
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>15 Derwent Street, Sunderland, SR1 2BB, UK</span>
                        </li>
                        <li>
                            <i class="bi bi-envelope-fill"></i>
                            <a href="mailto:info@ai-solutions.co.uk">info@ai-solutions.co.uk</a>
                        </li>
                        <li>
                            <i class="bi bi-telephone-fill"></i>
                            <a href="tel:+441915550100">+44 (0)191 555 0100</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="mb-0">&copy; <?= date('Y') ?> AI-Solutions Ltd. All rights reserved.</p>
            <p class="mb-0 text-muted small">Sunderland, UK &nbsp;·&nbsp; Registered in England &amp; Wales</p>
        </div>
    </div>
</footer>

<!-- ============================================================
     CHATBOT WIDGET
     ============================================================ -->
<div id="chatbot-toggle" title="Chat with AI Assistant">
    <i class="bi bi-chat-dots-fill" id="chatbot-icon-open"></i>
    <i class="bi bi-x-lg d-none"    id="chatbot-icon-close"></i>
</div>

<div id="chatbot-window" class="d-none">
    <div id="chatbot-header">
        <div class="d-flex align-items-center gap-2">
            <div class="chatbot-avatar"><i class="bi bi-robot"></i></div>
            <div>
                <div class="chatbot-name">AI Assistant</div>
                <div class="chatbot-status"><span class="chatbot-dot"></span> Online</div>
            </div>
        </div>
    </div>
    <div id="chatbot-messages"></div>
    <div id="chatbot-input-area">
        <input type="text" id="chatbot-input" placeholder="Type your message…" autocomplete="off">
        <button id="chatbot-send" aria-label="Send message"><i class="bi bi-send-fill"></i></button>
    </div>
</div>

<!-- Back to top -->
<button id="back-to-top" aria-label="Back to top" class="d-none">
    <i class="bi bi-arrow-up"></i>
</button>

<!-- Bootstrap JS bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script>const BASE_URL = '<?= BASE_URL ?>';</script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
<script src="<?= BASE_URL ?>/assets/js/chatbot.js"></script>
<script src="<?= BASE_URL ?>/assets/js/gallery.js"></script>
</body>
</html>
