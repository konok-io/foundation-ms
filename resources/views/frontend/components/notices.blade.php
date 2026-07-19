<!-- Notices Section -->
<section class="notices-section py-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);">
    <div class="container py-5">
        <!-- Section Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-5">
            <div data-aos="fade-right">
                <span class="px-4 py-2 rounded-pill mb-3 d-inline-block" style="background: rgba(245, 158, 11, 0.2); color: var(--accent); font-weight: 600;">
                    <i class="bi bi-megaphone me-2"></i>Latest Updates
                </span>
                <h2 class="display-5 fw-bold mb-2 text-white">
                    Important <span style="color: var(--accent);">Notices</span>
                </h2>
            </div>
            <div data-aos="fade-left">
                <a href="{{ route('public.notices.index') }}" class="btn btn-outline-light rounded-pill px-4">
                    View All <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>

        <!-- Notices Grid -->
        <div class="row g-4">
            @forelse($activeNotices as $index => $notice)
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                <div class="notice-card p-4 rounded-4 h-100" 
                     style="background: rgba(255,255,255,0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); transition: all 0.4s; border-left: 4px solid {{ $notice->priority === 'urgent' ? 'var(--danger)' : ($notice->priority === 'high' ? 'var(--accent)' : 'var(--primary)) }};">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center gap-2">
                            @if($notice->priority === 'urgent')
                            <span class="badge px-3 py-2 rounded-pill" style="background: var(--danger);">
                                <i class="bi bi-exclamation-circle me-1"></i> Urgent
                            </span>
                            @elseif($notice->priority === 'high')
                            <span class="badge px-3 py-2 rounded-pill" style="background: var(--accent);">
                                <i class="bi bi-bookmark-star me-1"></i> Important
                            </span>
                            @else
                            <span class="badge px-3 py-2 rounded-pill" style="background: var(--primary);">
                                {{ ucfirst(str_replace('_', ' ', $notice->notice_type)) }}
                            </span>
                            @endif
                        </div>
                        <small class="text-white-50">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $notice->publish_date ? \Carbon\Carbon::parse($notice->publish_date)->format('d M Y') : 'N/A' }}
                        </small>
                    </div>
                    <h4 class="fw-bold text-white mb-3">{{ $notice->title }}</h4>
                    <p class="text-white-50 mb-0">{{ Str::limit(strip_tags($notice->content), 150) }}</p>
                </div>
            </div>
            @empty
            <!-- Demo Notice Cards -->
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="notice-card p-4 rounded-4 h-100" style="background: rgba(255,255,255,0.05); border-left: 4px solid var(--danger);">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge px-3 py-2 rounded-pill" style="background: var(--danger);">
                            <i class="bi bi-exclamation-circle me-1"></i> Urgent
                        </span>
                        <small class="text-white-50">
                            <i class="bi bi-calendar3 me-1"></i> 19 Jul 2024
                        </small>
                    </div>
                    <h4 class="fw-bold text-white mb-3">Monthly Contribution Due Date</h4>
                    <p class="text-white-50 mb-0">All members are requested to pay their monthly contributions by the 10th of every month.</p>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                <div class="notice-card p-4 rounded-4 h-100" style="background: rgba(255,255,255,0.05); border-left: 4px solid var(--accent);">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge px-3 py-2 rounded-pill" style="background: var(--accent);">
                            <i class="bi bi-bookmark-star me-1"></i> Important
                        </span>
                        <small class="text-white-50">
                            <i class="bi bi-calendar3 me-1"></i> 15 Jul 2024
                        </small>
                    </div>
                    <h4 class="fw-bold text-white mb-3">Annual General Meeting Announcement</h4>
                    <p class="text-white-50 mb-0">The Annual General Meeting will be held on the last Saturday of this month.</p>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                <div class="notice-card p-4 rounded-4 h-100" style="background: rgba(255,255,255,0.05); border-left: 4px solid var(--primary);">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge px-3 py-2 rounded-pill" style="background: var(--primary);">
                            General
                        </span>
                        <small class="text-white-50">
                            <i class="bi bi-calendar3 me-1"></i> 10 Jul 2024
                        </small>
                    </div>
                    <h4 class="fw-bold text-white mb-3">New Member Registration Open</h4>
                    <p class="text-white-50 mb-0">We are now accepting new member registrations. Visit our office or apply online.</p>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">
                <div class="notice-card p-4 rounded-4 h-100" style="background: rgba(255,255,255,0.05); border-left: 4px solid var(--secondary);">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge px-3 py-2 rounded-pill" style="background: var(--secondary);">
                            Holiday
                        </span>
                        <small class="text-white-50">
                            <i class="bi bi-calendar3 me-1"></i> 05 Jul 2024
                        </small>
                    </div>
                    <h4 class="fw-bold text-white mb-3">Office Holiday Notice</h4>
                    <p class="text-white-50 mb-0">Our office will remain closed on the upcoming public holiday.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<style>
.notice-card {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.notice-card:hover {
    background: rgba(255,255,255,0.08) !important;
    transform: translateX(10px);
}
</style>
