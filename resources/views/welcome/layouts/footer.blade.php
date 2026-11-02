@php
    $footerSocials = array_filter([
        'facebook'  => [general()->facebook_link, 'fa-brands fa-facebook-f', 'Facebook'],
        'instagram' => [general()->instagram_link, 'fa-brands fa-instagram', 'Instagram'],
        'youtube'   => [general()->youtube_link, 'fa-brands fa-youtube', 'YouTube'],
        'twitter'   => [general()->twitter_link, 'fa-brands fa-x-twitter', 'X'],
        'linkedin'  => [general()->linkedin_link, 'fa-brands fa-linkedin-in', 'LinkedIn'],
        'tiktok'    => [general()->pinterest_link, 'fa-brands fa-tiktok', 'TikTok'],
        'whatsapp'  => [general()->mobile ? 'https://wa.me/88'.general()->mobile : null, 'fa-brands fa-whatsapp', 'WhatsApp'],
    ], fn($s) => !empty($s[0]));
@endphp
<!--Footer Part Start-->
<footer class="ft">
    <div class="ft-main">
        <div class="container">

            <div class="ft-pay">
                <div class="ft-pay-intro">
                    <span class="ft-pay-lock"><i class="fa-solid fa-lock"></i></span>
                    <div>
                        <strong>Secure Payment Options</strong>
                        <span>Cash on delivery or pay with any app through Bangla QR</span>
                    </div>
                </div>
                <ul class="ft-pay-list">
                    <li title="Cash on Delivery"><img src="{{asset('nihon/images/payment/14267324.png')}}" alt="Cash on Delivery" loading="lazy"><span>COD</span></li>
                    <li title="bKash"><img src="{{asset('nihon/images/payment/unnamed.jpg')}}" alt="bKash" loading="lazy"><span>bKash</span></li>
                    <li title="Nagad"><img src="{{asset('nihon/images/payment/63188bd74aba63d32140648b5a8ba5f8_icon.png')}}" alt="Nagad" loading="lazy"><span>Nagad</span></li>
                    <li title="Rocket"><b class="ft-pay-rocket">Rocket</b><span>Rocket</span></li>
                    <li title="Bangla QR"><i class="fa-solid fa-qrcode ft-pay-qr"></i><span>Bangla QR</span></li>
                    <li title="Visa"><i class="fa-brands fa-cc-visa ft-pay-visa"></i><span>Visa</span></li>
                    <li title="Mastercard"><i class="fa-brands fa-cc-mastercard ft-pay-mc"></i><span>Mastercard</span></li>
                </ul>
            </div>

            <div class="ft-grid">
                <div class="ft-brand">
                    <a href="{{route('index')}}" class="ft-logo">
                        <img src="{{asset(general()->logo())}}" alt="{{general()->title}}" loading="lazy" />
                    </a>
                    @if(general()->subtitle)
                    <p class="ft-tagline">{{ general()->subtitle }}</p>
                    @endif
                    <p class="ft-about">Shop quality products online from {{ general()->title }} with secure checkout and cash on delivery.</p>
                    <a href="{{route('pageView','latest-products')}}" class="ft-cta">Shop Now <i class="fa-solid fa-arrow-right"></i></a>
                </div>

                <div class="ft-col">
                    <h4>Quick Links</h4>
                    <ul class="ft-links">
                        <li><a href="{{route('index')}}"><i class="fa-solid fa-house"></i> Home</a></li>
                        <li><a href="{{route('offerProducts')}}"><i class="fa-solid fa-tags"></i> Offer Products</a></li>
                        <li><a href="{{route('pageView','latest-products')}}"><i class="fa-solid fa-box-open"></i> Latest Products</a></li>
                        <li><a href="{{route('myWishlist')}}"><i class="fa-regular fa-heart"></i> Wishlist</a></li>
                        <li><a href="{{ Auth::check() ? route('customer.dashboard') : route('login') }}"><i class="fa-regular fa-user"></i> My Account</a></li>
                    </ul>
                </div>

                @if($footerInfo = menu('Footer Two'))
                <div class="ft-col">
                    <h4>{{ $footerInfo->name }}</h4>
                    <ul class="ft-links">
                        @foreach($footerInfo->subMenus as $item)
                        <li><a href="{{asset($item->munuLink())}}" target="{{$item->target?'_blank':'_self'}}"><i class="fa-regular fa-file-lines"></i> {{$item->munuName()}}</a></li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="ft-col ft-contact">
                    <h4>Get in Touch</h4>
                    @if(general()->mobile)
                    <a href="tel:{{general()->mobile}}" class="ft-contact-row">
                        <span class="ft-contact-icon"><i class="fa-solid fa-phone"></i></span>
                        <span>{{general()->mobile}}</span>
                    </a>
                    @endif
                    @if(general()->email)
                    <a href="mailto:{{general()->email}}" class="ft-contact-row">
                        <span class="ft-contact-icon"><i class="fa-solid fa-envelope"></i></span>
                        <span>{{general()->email}}</span>
                    </a>
                    @endif
                    @if(general()->address_one)
                    <div class="ft-contact-row">
                        <span class="ft-contact-icon"><i class="fa-solid fa-location-dot"></i></span>
                        <span>{!! general()->address_one !!}</span>
                    </div>
                    @endif

                    @if(count($footerSocials))
                    <h4 class="ft-follow-title">Follow Us</h4>
                    <div class="ft-social">
                        @foreach($footerSocials as $social)
                        <a href="{{ $social[0] }}" target="_blank" rel="noopener" aria-label="{{ $social[2] }}" title="{{ $social[2] }}"><i class="{{ $social[1] }}"></i></a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <svg class="ft-wave" viewBox="0 0 1440 120" preserveAspectRatio="none" aria-hidden="true">
            <path d="M0,64 C240,112 480,8 720,40 C960,72 1200,120 1440,72 L1440,120 L0,120 Z" fill="rgba(255,0,94,.10)"></path>
            <path d="M0,88 C300,40 560,120 860,84 C1100,56 1280,72 1440,96 L1440,120 L0,120 Z" fill="rgba(255,0,94,.16)"></path>
            <path d="M0,40 C260,96 520,0 760,32 C1000,64 1220,104 1440,48" fill="none" stroke="rgba(255,61,132,.45)" stroke-width="1.5"></path>
        </svg>
    </div>

    <div class="ft-bottom">
        <div class="container ft-bottom-inner">
            <p>&copy; {{ date('Y') }} {{general()->title}}. All Rights Reserved.</p>
            <div class="ft-bottom-right">
                @if($footerLegal = menu('Footer One'))
                <ul class="ft-legal">
                    @foreach($footerLegal->subMenus as $item)
                    <li><a href="{{asset($item->munuLink())}}">{{$item->munuName()}}</a></li>
                    @endforeach
                </ul>
                @endif
                <button type="button" class="ft-top" aria-label="Back to top" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"><i class="fa-solid fa-arrow-up"></i></button>
            </div>
        </div>
    </div>
</footer>
<!--Footer Part End-->
