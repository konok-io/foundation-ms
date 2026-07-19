@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb', '<li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Settings</a></li>')

<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Settings Menu</h6>
            </div>
            <div class="list-group list-group-flush">
                @foreach($groups as $key => $label)
                <a href="#{{ $key }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-{{ $key === 'general' ? 'gear' : ($key === 'contact' ? 'envelope' : ($key === 'social' ? 'share' : 'palette')) }} me-2"></i>
                    {{ $label }}
                </a>
                @endforeach
                <a href="{{ route('admin.settings.activity-logs') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-clock-history me-2"></i>
                    Activity Logs
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" data-loading>
            @csrf
            
            <!-- General Settings -->
            <div class="card mb-4" id="general">
                <div class="card-header">
                    <h6 class="mb-0">General Settings</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_name" class="form-label">Site Name</label>
                                <input type="text" class="form-control" id="site_name" name="site_name" 
                                       value="{{ old('site_name', $settings['general']['site_name'] ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_tagline" class="form-label">Site Tagline</label>
                                <input type="text" class="form-control" id="site_tagline" name="site_tagline" 
                                       value="{{ old('site_tagline', $settings['general']['site_tagline'] ?? '') }}">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="site_description" class="form-label">Site Description</label>
                        <textarea class="form-control" id="site_description" name="site_description" rows="3">{{ old('site_description', $settings['general']['site_description'] ?? '') }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="currency" class="form-label">Currency</label>
                                <input type="text" class="form-control" id="currency" name="currency" 
                                       value="{{ old('currency', $settings['general']['currency'] ?? 'SAR') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="currency_symbol" class="form-label">Currency Symbol</label>
                                <input type="text" class="form-control" id="currency_symbol" name="currency_symbol" 
                                       value="{{ old('currency_symbol', $settings['general']['currency_symbol'] ?? 'ر.س') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="timezone" class="form-label">Timezone</label>
                                <select class="form-select" id="timezone" name="timezone">
                                    <option value="Asia/Dhaka" {{ old('timezone', $settings['general']['timezone'] ?? '') == 'Asia/Dhaka' ? 'selected' : '' }}>Asia/Dhaka (BST)</option>
                                    <option value="Asia/Riyadh" {{ old('timezone', $settings['general']['timezone'] ?? '') == 'Asia/Riyadh' ? 'selected' : '' }}>Asia/Riyadh (AST)</option>
                                    <option value="UTC" {{ old('timezone', $settings['general']['timezone'] ?? '') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Settings -->
            <div class="card mb-4" id="contact">
                <div class="card-header">
                    <h6 class="mb-0">Contact Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $settings['contact']['address'] ?? '') }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" 
                                       value="{{ old('phone', $settings['contact']['phone'] ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email', $settings['contact']['email'] ?? '') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Social Media Settings -->
            <div class="card mb-4" id="social">
                <div class="card-header">
                    <h6 class="mb-0">Social Media Links</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="facebook" class="form-label">
                                    <i class="bi bi-facebook text-primary me-1"></i> Facebook
                                </label>
                                <input type="url" class="form-control" id="facebook" name="facebook" 
                                       value="{{ old('facebook', $settings['social']['facebook'] ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="twitter" class="form-label">
                                    <i class="bi bi-twitter text-info me-1"></i> Twitter
                                </label>
                                <input type="url" class="form-control" id="twitter" name="twitter" 
                                       value="{{ old('twitter', $settings['social']['twitter'] ?? '') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="instagram" class="form-label">
                                    <i class="bi bi-instagram text-danger me-1"></i> Instagram
                                </label>
                                <input type="url" class="form-control" id="instagram" name="instagram" 
                                       value="{{ old('instagram', $settings['social']['instagram'] ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="linkedin" class="form-label">
                                    <i class="bi bi-linkedin text-primary me-1"></i> LinkedIn
                                </label>
                                <input type="url" class="form-control" id="linkedin" name="linkedin" 
                                       value="{{ old('linkedin', $settings['social']['linkedin'] ?? '') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="youtube" class="form-label">
                                    <i class="bi bi-youtube text-danger me-1"></i> YouTube
                                </label>
                                <input type="url" class="form-control" id="youtube" name="youtube" 
                                       value="{{ old('youtube', $settings['social']['youtube'] ?? '') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Appearance Settings -->
            <div class="card mb-4" id="appearance">
                <div class="card-header">
                    <h6 class="mb-0">Appearance</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="logo" class="form-label">Logo</label>
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                @if(isset($settings['appearance']['logo']))
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $settings['appearance']['logo']) }}" alt="Logo" height="50">
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="favicon" class="form-label">Favicon</label>
                                <input type="file" class="form-control" id="favicon" name="favicon" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="primary_color" class="form-label">Primary Color</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color" id="primary_color" name="primary_color" 
                                           value="{{ old('primary_color', $settings['appearance']['primary_color'] ?? '#0E7490') }}">
                                    <input type="text" class="form-control" value="{{ old('primary_color', $settings['appearance']['primary_color'] ?? '#0E7490') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stripe Settings -->
            <div class="card mb-4" id="payment">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-credit-card me-2"></i>Payment Settings</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Configure your payment gateway credentials below. Leave blank if not using that gateway.
                    </div>
                    
                    <h6 class="mb-3 text-primary">Stripe</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stripe_public_key" class="form-label">Stripe Public Key</label>
                                <input type="text" class="form-control" id="stripe_public_key" name="stripe_public_key" 
                                       value="{{ old('stripe_public_key', $settings['payment']['stripe_public_key'] ?? '') }}"
                                       placeholder="pk_live_xxxxx">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stripe_secret_key" class="form-label">Stripe Secret Key</label>
                                <input type="password" class="form-control" id="stripe_secret_key" name="stripe_secret_key" 
                                       value="{{ old('stripe_secret_key', $settings['payment']['stripe_secret_key'] ?? '') }}"
                                       placeholder="sk_live_xxxxx">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stripe_webhook_secret" class="form-label">Stripe Webhook Secret</label>
                                <input type="password" class="form-control" id="stripe_webhook_secret" name="stripe_webhook_secret" 
                                       value="{{ old('stripe_webhook_secret', $settings['payment']['stripe_webhook_secret'] ?? '') }}"
                                       placeholder="whsec_xxxxx">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stripe_currency" class="form-label">Currency</label>
                                <select class="form-select" id="stripe_currency" name="stripe_currency">
                                    <option value="SAR" {{ old('stripe_currency', $settings['payment']['stripe_currency'] ?? 'SAR') == 'SAR' ? 'selected' : '' }}>SAR - Saudi Riyal</option>
                                    <option value="USD" {{ old('stripe_currency', $settings['payment']['stripe_currency'] ?? '') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                    <option value="EUR" {{ old('stripe_currency', $settings['payment']['stripe_currency'] ?? '') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                    <option value="GBP" {{ old('stripe_currency', $settings['payment']['stripe_currency'] ?? '') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                    <option value="BDT" {{ old('stripe_currency', $settings['payment']['stripe_currency'] ?? '') == 'BDT' ? 'selected' : '' }}>BDT - Bangladeshi Taka</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h6 class="mb-3 text-success">PayPal</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="paypal_client_id" class="form-label">PayPal Client ID</label>
                                <input type="text" class="form-control" id="paypal_client_id" name="paypal_client_id" 
                                       value="{{ old('paypal_client_id', $settings['payment']['paypal_client_id'] ?? '') }}"
                                       placeholder="Client ID">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="paypal_secret" class="form-label">PayPal Secret</label>
                                <input type="password" class="form-control" id="paypal_secret" name="paypal_secret" 
                                       value="{{ old('paypal_secret', $settings['payment']['paypal_secret'] ?? '') }}"
                                       placeholder="Secret">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="paypal_mode" class="form-label">PayPal Mode</label>
                                <select class="form-select" id="paypal_mode" name="paypal_mode">
                                    <option value="sandbox" {{ old('paypal_mode', $settings['payment']['paypal_mode'] ?? 'sandbox') == 'sandbox' ? 'selected' : '' }}>Sandbox (Testing)</option>
                                    <option value="live" {{ old('paypal_mode', $settings['payment']['paypal_mode'] ?? '') == 'live' ? 'selected' : '' }}>Live (Production)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h6 class="mb-3 text-warning">Payment Mode</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_mode" class="form-label">Default Payment Gateway</label>
                                <select class="form-select" id="payment_mode" name="payment_mode">
                                    <option value="stripe" {{ old('payment_mode', $settings['payment']['payment_mode'] ?? 'stripe') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                                    <option value="paypal" {{ old('payment_mode', $settings['payment']['payment_mode'] ?? '') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                    <option value="bank" {{ old('payment_mode', $settings['payment']['payment_mode'] ?? '') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="cash" {{ old('payment_mode', $settings['payment']['payment_mode'] ?? '') == 'cash' ? 'selected' : '' }}>Cash</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i>Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
