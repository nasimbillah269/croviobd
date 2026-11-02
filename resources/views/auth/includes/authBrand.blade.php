<div class="au-brand">
    <div>
        <a href="{{ route('index') }}" class="au-logo"><img src="{{ asset(general()->logo()) }}" alt="{{ general()->title }}"></a>
        <h2>{{ $heading }}</h2>
        <p>{{ $tagline }}</p>
        <ul class="au-perks">
            <li><i class="fa fa-box"></i> Track every order in one place</li>
            <li><i class="fa fa-bolt"></i> Faster checkout with saved details</li>
            <li><i class="fa fa-star"></i> Review the products you bought</li>
        </ul>
    </div>
    <small>&copy; {{ date('Y') }} {{ general()->title }}</small>
</div>
