<!-- CTA Section -->
<section class="cta-section py-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);">
    
    <!-- Animated Shapes -->
    <div class="cta-shapes">
        <div class="cta-shape cs-1"></div>
        <div class="cta-shape cs-2"></div>
        <div class="cta-shape cs-3"></div>
    </div>

    <div class="container py-5 position-relative" style="z-index: 10;">
        <div class="row align-items-center">
            <div class="col-lg-8 text-white" data-aos="fade-right">
                <h2 class="display-5 fw-bold mb-3">
                    Ready to Make a Difference?
                </h2>
                <p class="lead mb-0 opacity-75">
                    Join our community of changemakers and help us create positive impact in the lives of those who need it most.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0" data-aos="fade-left">
                <div class="d-flex flex-wrap gap-3 justify-content-lg-end">
                    <a href="{{ route('donate') }}" class="btn btn-lg px-5 rounded-pill" style="background: white; color: var(--primary); box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                        <i class="bi bi-heart-fill me-2"></i> Donate Now
                    </a>
                    <a href="#" class="btn btn-lg btn-outline-light rounded-pill px-5">
                        <i class="bi bi-person-plus me-2"></i> Join Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.cta-section {
    position: relative;
}

.cta-shapes .cta-shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.1;
}

.cs-1 {
    width: 300px;
    height: 300px;
    background: white;
    top: -150px;
    right: 10%;
}

.cs-2 {
    width: 200px;
    height: 200px;
    background: var(--accent);
    bottom: -100px;
    left: 5%;
}

.cs-3 {
    width: 150px;
    height: 150px;
    background: white;
    top: 50%;
    left: 30%;
}
</style>

<!-- Footer -->
<footer class="footer-section pt-5 pb-3" style="background: var(--dark);">
    <div class="container">
        <div class="row g-4 mb-5">
            <!-- About Column -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="footer-about">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="logo-icon" style="width: 50px; height: 50px; background: var(--gradient-primary); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                            <i class="bi bi-heart-pulse-fill"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-white" style="font-family: 'Poppins', sans-serif;">{{ $siteSettings['site_name'] ?? 'Bangladesh Welfare Foundation' }}</div>
                            <div class="small text-white-50">{{ $siteSettings['site_tagline'] ?? 'Serving Humanity, Building Hope' }}</div>
                        </div>
                    </div>
                    <p class="text-white-50 mb-4">
                        We are dedicated to making a positive impact in our community through education, healthcare, and welfare programs.
                    </p>
                    <!-- Social Links -->
                    <div class="d-flex gap-3">
                        <a href="#" class="social-link" style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; transition: all 0.3s;">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="social-link" style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; transition: all 0.3s;">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                        <a href="#" class="social-link" style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; transition: all 0.3s;">
                            <i class="bi bi-youtube"></i>
                        </a>
                        <a href="#" class="social-link" style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; transition: all 0.3s;">
                            <i class="bi bi-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <h5 class="text-white fw-bold mb-4">Quick Links</h5>
                <ul class="list-unstyled footer-links">
                    <li class="mb-2"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                    <li class="mb-2"><a href="{{ route('frontend.page', 'about-us') }}" class="text-white-50 text-decoration-none">About Us</a></li>
                    <li class="mb-2"><a href="{{ route('public.events.index') }}" class="text-white-50 text-decoration-none">Events</a></li>
                    <li class="mb-2"><a href="{{ route('public.activities.index') }}" class="text-white-50 text-decoration-none">Activities</a></li>
                    <li class="mb-2"><a href="{{ route('public.gallery.index') }}" class="text-white-50 text-decoration-none">Gallery</a></li>
                    <li class="mb-2"><a href="{{ route('frontend.page', 'contact') }}" class="text-white-50 text-decoration-none">Contact</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div class="col-lg-2 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <h5 class="text-white fw-bold mb-4">Services</h5>
                <ul class="list-unstyled footer-links">
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Member Management</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Blood Donation</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Emergency Fund</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Scholarships</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Medical Aid</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Food Distribution</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <h5 class="text-white fw-bold mb-4">Contact Info</h5>
                <div class="footer-contact">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <i class="bi bi-geo-alt-fill text-primary mt-1"></i>
                        <span class="text-white-50">House 12, Road 5, Dhanmondi, Dhaka 1205, Bangladesh</span>
                    </div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <i class="bi bi-telephone-fill text-primary"></i>
                        <span class="text-white-50">+880 1700-000000</span>
                    </div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <i class="bi bi-envelope-fill text-primary"></i>
                        <span class="text-white-50">info@foundation.org</span>
                    </div>
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <i class="bi bi-clock-fill text-primary"></i>
                        <span class="text-white-50">Sat - Thu: 9:00 AM - 5:00 PM</span>
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="newsletter-box p-3 rounded-4" style="background: rgba(255,255,255,0.05);">
                    <h6 class="text-white fw-bold mb-3">Newsletter</h6>
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Your email" style="background: rgba(255,255,255,0.1); border: none; color: white;">
                        <button class="btn btn-primary" type="button"><i class="bi bi-send"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-top border-secondary pt-4 mt-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-white-50 small mb-0">
                        &copy; {{ date('Y') }} {{ $siteSettings['site_name'] ?? 'Bangladesh Welfare Foundation' }}. All Rights Reserved.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                    <a href="#" class="text-white-50 small text-decoration-none me-3">Privacy Policy</a>
                    <a href="#" class="text-white-50 small text-decoration-none me-3">Terms of Service</a>
                    <a href="#" class="text-white-50 small text-decoration-none">Sitemap</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
.footer-section {
    position: relative;
}

.social-link:hover {
    background: var(--primary) !important;
    transform: translateY(-3px);
}

.footer-links li a {
    transition: all 0.3s;
}

.footer-links li a:hover {
    color: var(--accent) !important;
    padding-left: 5px;
}

.newsletter-box input::placeholder {
    color: rgba(255,255,255,0.5);
}

.newsletter-box input:focus {
    background: rgba(255,255,255,0.15) !important;
}
</style>

<!-- Back to Top Button -->
<button id="backToTop" class="btn btn-primary rounded-circle position-fixed bottom-0 end-0 m-4" style="width: 50px; height: 50px; display: none; z-index: 1000; box-shadow: 0 5px 20px rgba(14, 116, 144, 0.4);">
    <i class="bi bi-arrow-up"></i>
</button>

<script>
// Back to Top
const backToTop = document.getElementById('backToTop');
window.addEventListener('scroll', function() {
    if (window.scrollY > 300) {
        backToTop.style.display = 'block';
    } else {
        backToTop.style.display = 'none';
    }
});

backToTop.addEventListener('click', function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
</script>
