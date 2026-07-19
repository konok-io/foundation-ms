<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden" style="min-height: 90vh; background: linear-gradient(135deg, #0F172A 0%, #0E7490 50%, #16A34A 100%);">
    
    <!-- Animated Background Shapes -->
    <div class="hero-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
    </div>

    <!-- Floating Particles -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <!-- Gradient Overlay -->
    <div class="hero-overlay"></div>

    <!-- Wave Divider -->
    <div class="hero-wave">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#F8FAFC"/>
        </svg>
    </div>

    <div class="container position-relative" style="z-index: 10;">
        <div class="row align-items-center min-vh-90">
            <!-- Left Content -->
            <div class="col-lg-7" data-aos="fade-right" data-aos-delay="200">
                <div class="hero-content text-white">
                    <span class="hero-badge px-4 py-2 rounded-pill mb-4 d-inline-block" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px);">
                        <i class="bi bi-heart-fill text-danger me-2"></i>
                        Building Hope Together
                    </span>
                    
                    <h1 class="display-3 fw-bold mb-4" style="line-height: 1.2;">
                        {{ $settings['site_name'] ?? 'Foundation MS' }}
                    </h1>
                    
                    <p class="lead mb-5 opacity-75" style="max-width: 600px; font-size: 1.25rem;">
                        {{ $settings['site_tagline'] ?? 'Serving Humanity, Building Hope' }}
                    </p>

                    <!-- Impact Stats -->
                    <div class="hero-stats row g-4 mb-5">
                        <div class="col-6 col-md-3">
                            <div class="hero-stat-item text-center">
                                <div class="stat-number display-4 fw-bold" data-count="500">0</div>
                                <div class="stat-label opacity-75">Active Members</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="hero-stat-item text-center">
                                <div class="stat-number display-4 fw-bold" data-count="1000">0</div>
                                <div class="stat-label opacity-75">Families Helped</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="hero-stat-item text-center">
                                <div class="stat-number display-4 fw-bold" data-count="50">0</div>
                                <div class="stat-label opacity-75">Events Held</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="hero-stat-item text-center">
                                <div class="stat-number display-4 fw-bold" data-count="10">0</div>
                                <div class="stat-label opacity-75">Years of Service</div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('donate') }}" class="btn btn-lg px-5 py-3 rounded-pill" style="background: var(--accent); color: white; box-shadow: 0 10px 40px rgba(245, 158, 11, 0.4);">
                            <i class="bi bi-heart-fill me-2"></i> Donate Now
                        </a>
                        <a href="{{ route('public.events.index') }}" class="btn btn-lg btn-outline-light px-5 py-3 rounded-pill">
                            <i class="bi bi-calendar-event me-2"></i> Our Events
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Content - Image/Illustration -->
            <div class="col-lg-5 text-center mt-5 mt-lg-0" data-aos="fade-left" data-aos-delay="400">
                <div class="hero-image-wrapper position-relative">
                    <!-- Glass Card -->
                    <div class="hero-glass-card p-4" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(20px); border-radius: 30px; border: 1px solid rgba(255,255,255,0.2);">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 rounded-4 text-center" style="background: rgba(255,255,255,0.15);">
                                    <i class="bi bi-people-fill text-warning" style="font-size: 3rem;"></i>
                                    <div class="mt-2 fw-bold">Community</div>
                                    <div class="small opacity-75">United Together</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-4 text-center" style="background: rgba(255,255,255,0.15);">
                                    <i class="bi bi-heart-fill text-danger" style="font-size: 3rem;"></i>
                                    <div class="mt-2 fw-bold">Charity</div>
                                    <div class="small opacity-75">Helping Others</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-4 text-center" style="background: rgba(255,255,255,0.15);">
                                    <i class="bi bi-shield-check text-success" style="font-size: 3rem;"></i>
                                    <div class="mt-2 fw-bold">Trust</div>
                                    <div class="small opacity-75">Transparent Work</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-4 text-center" style="background: rgba(255,255,255,0.15);">
                                    <i class="bi bi-globe-americas text-info" style="font-size: 3rem;"></i>
                                    <div class="mt-2 fw-bold">Impact</div>
                                    <div class="small opacity-75">Nationwide</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Elements -->
                    <div class="hero-float-1" style="position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; background: var(--accent); border-radius: 20px; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; animation: float 4s ease-in-out infinite;">
                        <i class="bi bi-gift"></i>
                    </div>
                    <div class="hero-float-2" style="position: absolute; bottom: -15px; left: -15px; width: 60px; height: 60px; background: var(--secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; animation: float 5s ease-in-out infinite 0.5s;">
                        <i class="bi bi-hand-thumbs-up"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mouse Scroll Indicator -->
    <div class="scroll-indicator">
        <div class="mouse">
            <div class="wheel"></div>
        </div>
        <span>Scroll Down</span>
    </div>
</section>

<style>
.hero-section {
    position: relative;
}

.hero-shapes .shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.1;
}

.shape-1 {
    width: 600px;
    height: 600px;
    background: var(--accent);
    top: -200px;
    right: -100px;
    animation: pulse 8s ease-in-out infinite;
}

.shape-2 {
    width: 400px;
    height: 400px;
    background: var(--secondary);
    bottom: -100px;
    left: -100px;
    animation: pulse 6s ease-in-out infinite 1s;
}

.shape-3 {
    width: 200px;
    height: 200px;
    background: var(--primary);
    top: 30%;
    left: 10%;
    animation: pulse 5s ease-in-out infinite 2s;
}

.shape-4 {
    width: 150px;
    height: 150px;
    background: var(--accent);
    bottom: 40%;
    right: 30%;
    animation: pulse 7s ease-in-out infinite 0.5s;
}

.shape-5 {
    width: 100px;
    height: 100px;
    background: var(--secondary);
    top: 20%;
    right: 40%;
    animation: pulse 4s ease-in-out infinite 1.5s;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.1; }
    50% { transform: scale(1.2); opacity: 0.2; }
}

.particles {
    position: absolute;
    inset: 0;
    overflow: hidden;
}

.particle {
    position: absolute;
    width: 10px;
    height: 10px;
    background: rgba(255,255,255,0.3);
    border-radius: 50%;
    animation: particleFloat 15s linear infinite;
}

.particle:nth-child(1) { left: 10%; animation-delay: 0s; animation-duration: 20s; }
.particle:nth-child(2) { left: 20%; animation-delay: 2s; animation-duration: 18s; }
.particle:nth-child(3) { left: 35%; animation-delay: 4s; animation-duration: 22s; }
.particle:nth-child(4) { left: 50%; animation-delay: 1s; animation-duration: 16s; }
.particle:nth-child(5) { left: 70%; animation-delay: 3s; animation-duration: 19s; }
.particle:nth-child(6) { left: 85%; animation-delay: 5s; animation-duration: 21s; }

@keyframes particleFloat {
    0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
    10% { opacity: 1; }
    90% { opacity: 1; }
    100% { transform: translateY(-100vh) rotate(720deg); opacity: 0; }
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(14, 116, 144, 0.8) 50%, rgba(22, 163, 74, 0.8) 100%);
}

.hero-wave {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
}

.hero-wave svg {
    display: block;
    width: 100%;
    height: 100px;
}

.stat-number {
    background: linear-gradient(135deg, #fff 0%, rgba(255,255,255,0.7) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.scroll-indicator {
    position: absolute;
    bottom: 120px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    color: rgba(255,255,255,0.7);
    font-size: 12px;
    z-index: 20;
}

.mouse {
    width: 26px;
    height: 40px;
    border: 2px solid rgba(255,255,255,0.5);
    border-radius: 20px;
    position: relative;
}

.wheel {
    width: 4px;
    height: 8px;
    background: rgba(255,255,255,0.7);
    border-radius: 2px;
    position: absolute;
    top: 8px;
    left: 50%;
    transform: translateX(-50%);
    animation: scrollWheel 2s ease-in-out infinite;
}

@keyframes scrollWheel {
    0%, 100% { transform: translateX(-50%) translateY(0); opacity: 1; }
    50% { transform: translateX(-50%) translateY(10px); opacity: 0.3; }
}

.min-vh-90 {
    min-height: 90vh;
}
</style>

<script>
// Counter Animation
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.stat-number');
    
    const animateCounter = (counter) => {
        const target = parseInt(counter.getAttribute('data-count'));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        const updateCounter = () => {
            current += step;
            if (current < target) {
                counter.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target + '+';
            }
        };
        
        updateCounter();
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => observer.observe(counter));
});
</script>
