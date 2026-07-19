<!-- Gallery Section -->
<section class="gallery-section py-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);">
    <div class="container py-5">
        <!-- Section Header -->
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="px-4 py-2 rounded-pill mb-3 d-inline-block" style="background: rgba(139, 92, 246, 0.2); color: #A855F7; font-weight: 600;">
                <i class="bi bi-images me-2"></i>Photo Gallery
            </span>
            <h2 class="display-5 fw-bold text-white mb-3">
                Captured <span style="color: #A855F7;">Moments</span>
            </h2>
            <p class="lead text-white-50 mx-auto" style="max-width: 600px;">
                Explore our journey through images - each photo tells a story of hope, resilience, and community impact.
            </p>
        </div>

        <!-- Gallery Grid -->
        <div class="row g-4">
            @forelse($featuredAlbums as $index => $album)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                <div class="gallery-card rounded-4 overflow-hidden position-relative" style="height: 300px;">
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #0E7490 0%, #16A34A 100%);">
                        <i class="bi bi-images text-white" style="font-size: 5rem; opacity: 0.5;"></i>
                    </div>
                    <div class="gallery-overlay position-absolute inset-0 d-flex flex-column justify-content-end p-4">
                        <div class="text-white">
                            <span class="badge mb-2" style="background: rgba(255,255,255,0.2);">
                                {{ ucfirst($album->album_type) }}
                            </span>
                            <h4 class="fw-bold mb-2">{{ $album->title }}</h4>
                            <p class="small opacity-75 mb-3">{{ Str::limit($album->description, 60) }}</p>
                            <a href="{{ route('public.gallery.show', $album) }}" class="btn btn-light rounded-pill btn-sm">
                                <i class="bi bi-eye me-1"></i> View Gallery
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <!-- Demo Gallery Cards -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="gallery-card rounded-4 overflow-hidden position-relative" style="height: 300px;">
                    <img src="https://images.unsplash.com/photo-1531545514256-b1400bc00f31?w=400&h=300&fit=crop" alt="Gallery" class="w-100 h-100" style="object-fit: cover;">
                    <div class="gallery-overlay position-absolute inset-0 d-flex flex-column justify-content-end p-4">
                        <div class="text-white">
                            <span class="badge mb-2" style="background: rgba(255,255,255,0.2);">Annual</span>
                            <h4 class="fw-bold mb-2">Annual Gathering 2024</h4>
                            <p class="small opacity-75 mb-3">Celebrating our achievements together</p>
                            <a href="{{ route('public.gallery.index') }}" class="btn btn-light rounded-pill btn-sm">
                                <i class="bi bi-eye me-1"></i> View Gallery
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="gallery-card rounded-4 overflow-hidden position-relative" style="height: 300px;">
                    <img src="https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=400&h=300&fit=crop" alt="Gallery" class="w-100 h-100" style="object-fit: cover;">
                    <div class="gallery-overlay position-absolute inset-0 d-flex flex-column justify-content-end p-4">
                        <div class="text-white">
                            <span class="badge mb-2" style="background: rgba(255,255,255,0.2);">Education</span>
                            <h4 class="fw-bold mb-2">Education Program</h4>
                            <p class="small opacity-75 mb-3">Empowering through knowledge</p>
                            <a href="{{ route('public.gallery.index') }}" class="btn btn-light rounded-pill btn-sm">
                                <i class="bi bi-eye me-1"></i> View Gallery
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="gallery-card rounded-4 overflow-hidden position-relative" style="height: 300px;">
                    <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=400&h=300&fit=crop" alt="Gallery" class="w-100 h-100" style="object-fit: cover;">
                    <div class="gallery-overlay position-absolute inset-0 d-flex flex-column justify-content-end p-4">
                        <div class="text-white">
                            <span class="badge mb-2" style="background: rgba(255,255,255,0.2);">Medical</span>
                            <h4 class="fw-bold mb-2">Medical Camp</h4>
                            <p class="small opacity-75 mb-3">Serving the community</p>
                            <a href="{{ route('public.gallery.index') }}" class="btn btn-light rounded-pill btn-sm">
                                <i class="bi bi-eye me-1"></i> View Gallery
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="gallery-card rounded-4 overflow-hidden position-relative" style="height: 300px;">
                    <img src="https://images.unsplash.com/photo-1593113598332-cd288d649433?w=400&h=300&fit=crop" alt="Gallery" class="w-100 h-100" style="object-fit: cover;">
                    <div class="gallery-overlay position-absolute inset-0 d-flex flex-column justify-content-end p-4">
                        <div class="text-white">
                            <span class="badge mb-2" style="background: rgba(255,255,255,0.2);">Relief</span>
                            <h4 class="fw-bold mb-2">Food Distribution</h4>
                            <p class="small opacity-75 mb-3">Helping those in need</p>
                            <a href="{{ route('public.gallery.index') }}" class="btn btn-light rounded-pill btn-sm">
                                <i class="bi bi-eye me-1"></i> View Gallery
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="gallery-card rounded-4 overflow-hidden position-relative" style="height: 300px;">
                    <img src="https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=400&h=300&fit=crop" alt="Gallery" class="w-100 h-100" style="object-fit: cover;">
                    <div class="gallery-overlay position-absolute inset-0 d-flex flex-column justify-content-end p-4">
                        <div class="text-white">
                            <span class="badge mb-2" style="background: rgba(255,255,255,0.2);">Community</span>
                            <h4 class="fw-bold mb-2">Community Events</h4>
                            <p class="small opacity-75 mb-3">Building connections</p>
                            <a href="{{ route('public.gallery.index') }}" class="btn btn-light rounded-pill btn-sm">
                                <i class="bi bi-eye me-1"></i> View Gallery
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                <div class="gallery-card rounded-4 overflow-hidden position-relative" style="height: 300px;">
                    <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=400&h=300&fit=crop" alt="Gallery" class="w-100 h-100" style="object-fit: cover;">
                    <div class="gallery-overlay position-absolute inset-0 d-flex flex-column justify-content-end p-4">
                        <div class="text-white">
                            <span class="badge mb-2" style="background: rgba(255,255,255,0.2);">Youth</span>
                            <h4 class="fw-bold mb-2">Youth Programs</h4>
                            <p class="small opacity-75 mb-3">Nurturing future leaders</p>
                            <a href="{{ route('public.gallery.index') }}" class="btn btn-light rounded-pill btn-sm">
                                <i class="bi bi-eye me-1"></i> View Gallery
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <!-- View All Button -->
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="{{ route('public.gallery.index') }}" class="btn btn-lg px-5 rounded-pill" style="background: linear-gradient(135deg, #8B5CF6 0%, #A855F7 100%); color: white; box-shadow: 0 10px 40px rgba(139, 92, 246, 0.4);">
                <i class="bi bi-images me-2"></i> View Full Gallery
            </a>
        </div>
    </div>
</section>

<style>
.gallery-card {
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.gallery-card:hover {
    transform: scale(1.02);
    box-shadow: 0 30px 60px rgba(0,0,0,0.3);
}

.gallery-card img {
    transition: transform 0.5s;
}

.gallery-card:hover img {
    transform: scale(1.1);
}

.gallery-overlay {
    background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, transparent 100%);
    opacity: 0.8;
    transition: all 0.4s;
}

.gallery-card:hover .gallery-overlay {
    opacity: 1;
}

.gallery-overlay .btn {
    transform: translateY(20px);
    transition: transform 0.4s;
}

.gallery-card:hover .gallery-overlay .btn {
    transform: translateY(0);
}
</style>
