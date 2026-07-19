<!-- Donation Section -->
<section class="donation-section py-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);">
    
    <!-- Animated Shapes -->
    <div class="donation-shapes">
        <div class="donation-shape ds-1"></div>
        <div class="donation-shape ds-2"></div>
        <div class="donation-shape ds-3"></div>
    </div>

    <div class="container py-5 position-relative" style="z-index: 10;">
        <div class="row align-items-center g-5">
            <!-- Left Content -->
            <div class="col-lg-6 text-white" data-aos="fade-right">
                <span class="px-4 py-2 rounded-pill mb-3 d-inline-block" style="background: rgba(255,255,255,0.2);">
                    <i class="bi bi-heart-fill me-2"></i>Make a Difference
                </span>
                <h2 class="display-4 fw-bold mb-4">
                    Your Donation Can<br>Change Lives
                </h2>
                <p class="lead mb-4 opacity-75">
                    Every contribution helps us provide education, healthcare, and support to those who need it most. Join us in building a better tomorrow.
                </p>

                <!-- Trust Indicators -->
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check" style="font-size: 1.5rem;"></i>
                            <span>100% Transparent</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-lock-fill" style="font-size: 1.5rem;"></i>
                            <span>Secure Payment</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-people-fill" style="font-size: 1.5rem;"></i>
                            <span>500+ Donors</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-star-fill" style="font-size: 1.5rem;"></i>
                            <span>Verified Organization</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="d-flex gap-3 align-items-center">
                    <span class="small opacity-75">Payment Methods:</span>
                    <i class="bi bi-credit-card-2-front" style="font-size: 1.5rem;"></i>
                    <i class="bi bi-paypal" style="font-size: 1.5rem;"></i>
                    <i class="bi bi-bank" style="font-size: 1.5rem;"></i>
                    <i class="bi bi-qr-code" style="font-size: 1.5rem;"></i>
                </div>
            </div>

            <!-- Right - Donation Card -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="donation-card p-5 rounded-5" style="background: white; box-shadow: 0 25px 80px rgba(0,0,0,0.3);">
                    <h3 class="fw-bold text-center mb-4" style="color: var(--dark);">Make a Donation</h3>
                    
                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold" style="color: var(--secondary);">৳35,00,000 Raised</span>
                            <span class="text-muted">Goal: ৳50,00,000</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 12px; background: #E2E8F0;">
                            <div class="progress-bar rounded-pill" style="width: 70%; background: linear-gradient(90deg, var(--secondary) 0%, var(--primary) 100%);"></div>
                        </div>
                        <div class="text-center mt-2">
                            <span class="badge px-3 py-2 rounded-pill" style="background: rgba(34, 197, 94, 0.1); color: var(--secondary);">
                                <i class="bi bi-people-fill me-1"></i> 287 Donors This Month
                            </span>
                        </div>
                    </div>

                    <!-- Quick Amount Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">Select Amount</label>
                        <div class="d-flex gap-2 flex-wrap mb-3">
                            <button class="btn btn-outline-primary rounded-pill px-4 quick-amount" data-amount="500">৳500</button>
                            <button class="btn btn-outline-primary rounded-pill px-4 quick-amount" data-amount="1000">৳1,000</button>
                            <button class="btn btn-outline-primary rounded-pill px-4 quick-amount active" data-amount="2500">৳2,500</button>
                            <button class="btn btn-outline-primary rounded-pill px-4 quick-amount" data-amount="5000">৳5,000</button>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" style="background: var(--light); border-color: #E2E8F0;">৳</span>
                            <input type="number" class="form-control form-control-lg" placeholder="Enter custom amount" value="2500" id="donationAmount" style="border-color: #E2E8F0;">
                        </div>
                    </div>

                    <!-- Donation Type -->
                    <div class="mb-4">
                        <div class="btn-group w-100">
                            <input type="radio" class="btn-check" name="donationType" id="oneTime" checked>
                            <label class="btn btn-outline-primary rounded-start-pill" for="oneTime">One Time</label>
                            
                            <input type="radio" class="btn-check" name="donationType" id="monthly">
                            <label class="btn btn-outline-primary rounded-end-pill" for="monthly">Monthly</label>
                        </div>
                    </div>

                    <!-- Donate Button -->
                    <a href="{{ route('donate') }}" class="btn btn-lg w-100 py-3 rounded-pill mb-3" style="background: linear-gradient(135deg, var(--accent) 0%, var(--danger) 100%); color: white; box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);">
                        <i class="bi bi-heart-fill me-2"></i> Donate Now
                    </a>

                    <p class="text-center text-muted small mb-0">
                        <i class="bi bi-lock me-1"></i> Your donation is secure and encrypted
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.donation-section {
    position: relative;
}

.donation-shapes .donation-shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.1;
}

.ds-1 {
    width: 400px;
    height: 400px;
    background: white;
    top: -150px;
    right: -100px;
}

.ds-2 {
    width: 300px;
    height: 300px;
    background: var(--accent);
    bottom: -100px;
    left: -50px;
}

.ds-3 {
    width: 200px;
    height: 200px;
    background: white;
    top: 30%;
    left: 20%;
}

.quick-amount {
    transition: all 0.3s;
}

.quick-amount.active,
.quick-amount:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}
</style>

<script>
document.querySelectorAll('.quick-amount').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.quick-amount').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('donationAmount').value = this.dataset.amount;
    });
});
</script>
