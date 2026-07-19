<!-- About Section -->
<section class="about-section py-5 position-relative overflow-hidden" style="background: var(--light);">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <!-- Left - Image -->
            <div class="col-lg-6" data-aos="fade-right">
                <div class="about-image-wrapper position-relative">
                    <!-- Main Image -->
                    <div class="about-main-image rounded-5 overflow-hidden shadow-xl">
                        <img src="https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=800&h=600&fit=crop" 
                             alt="Foundation Team" 
                             class="img-fluid w-100"
                             style="min-height: 400px; object-fit: cover;">
                    </div>
                    
                    <!-- Experience Badge -->
                    <div class="experience-badge" data-aos="zoom-in" data-aos-delay="300">
                        <div class="badge-content text-center text-white p-4" style="background: var(--gradient-primary); border-radius: 20px;">
                            <div class="display-3 fw-bold">10+</div>
                            <div class="text-uppercase small">Years of<br>Excellence</div>
                        </div>
                    </div>
                    
                    <!-- Support Badge -->
                    <div class="support-badge" data-aos="zoom-in" data-aos-delay="500">
                        <div class="d-flex align-items-center gap-3 p-3 bg-white rounded-4 shadow">
                            <div class="rounded-circle p-3" style="background: rgba(245, 158, 11, 0.1);">
                                <i class="bi bi-headset text-warning" style="font-size: 2rem;"></i>
                            </div>
                            <div>
                                <div class="fw-bold">24/7 Support</div>
                                <div class="small text-muted">Always here for you</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right - Content -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="about-content">
                    <span class="section-badge px-3 py-2 rounded-pill mb-3 d-inline-block" style="background: rgba(14, 116, 144, 0.1); color: var(--primary); font-weight: 600;">
                        <i class="bi bi-buildings me-2"></i>About Our Foundation
                    </span>
                    
                    <h2 class="display-5 fw-bold mb-4" style="color: var(--dark);">
                        Building a Better Tomorrow Through <span style="color: var(--primary);">Compassion</span> & <span style="color: var(--secondary);">Service</span>
                    </h2>
                    
                    <p class="lead text-muted mb-4">
                        We are a non-profit organization dedicated to serving communities through welfare programs, educational initiatives, and humanitarian aid. Our mission is to create positive change and empower those in need.
                    </p>

                    <!-- Mission Vision Cards -->
                    <div class="row g-4 mb-4">
                        <div class="col-sm-6">
                            <div class="p-4 rounded-4 h-100" style="background: linear-gradient(135deg, rgba(14, 116, 144, 0.1) 0%, rgba(14, 116, 144, 0.05) 100%); border-left: 4px solid var(--primary);">
                                <div class="mb-3">
                                    <i class="bi bi-bullseye" style="font-size: 2.5rem; color: var(--primary);"></i>
                                </div>
                                <h4 class="fw-bold mb-2">Our Mission</h4>
                                <p class="text-muted small mb-0">To empower communities through education, welfare, and sustainable development programs.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-4 rounded-4 h-100" style="background: linear-gradient(135deg, rgba(22, 163, 74, 0.1) 0%, rgba(22, 163, 74, 0.05) 100%); border-left: 4px solid var(--secondary);">
                                <div class="mb-3">
                                    <i class="bi bi-eye" style="font-size: 2.5rem; color: var(--secondary);"></i>
                                </div>
                                <h4 class="fw-bold mb-2">Our Vision</h4>
                                <p class="text-muted small mb-0">To be the leading foundation in community development and humanitarian service.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Preview -->
                    <div class="about-timeline">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge rounded-circle p-3 me-2" style="background: var(--primary);">2010</span>
                                <div class="border-start border-2 ps-3" style="border-color: var(--primary) !important;">
                                    <div class="fw-bold">Foundation Established</div>
                                    <div class="small text-muted">Started with 50 members</div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge rounded-circle p-3 me-2" style="background: var(--secondary);">2020</span>
                                <div class="border-start border-2 ps-3" style="border-color: var(--secondary) !important;">
                                    <div class="fw-bold">Expanded Nationwide</div>
                                    <div class="small text-muted">Operations in 8 districts</div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="badge rounded-circle p-3 me-2" style="background: var(--accent);">2024</span>
                                <div class="border-start border-2 ps-3" style="border-color: var(--accent) !important;">
                                    <div class="fw-bold">500+ Members</div>
                                    <div class="small text-muted">Growing stronger together</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('frontend.page', 'about-us') }}" class="btn btn-lg px-5 rounded-pill" style="background: var(--gradient-primary); color: white;">
                            Learn More About Us <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.about-section {
    position: relative;
}

.about-main-image {
    position: relative;
}

.about-main-image::before {
    content: '';
    position: absolute;
    top: 30px;
    left: 30px;
    right: -30px;
    bottom: -30px;
    border: 3px solid var(--primary);
    border-radius: 20px;
    z-index: -1;
}

.experience-badge {
    position: absolute;
    bottom: -30px;
    right: -30px;
    z-index: 10;
}

.support-badge {
    position: absolute;
    top: -20px;
    left: -30px;
    z-index: 10;
}

@media (max-width: 991px) {
    .experience-badge {
        right: 20px;
        bottom: -20px;
    }
    
    .support-badge {
        left: 20px;
        top: 20px;
    }
}
</style>
