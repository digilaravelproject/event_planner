<dialog id="login-choice" class="login-choice" aria-labelledby="login-choice-title" aria-describedby="login-choice-description">
    <div class="login-choice-content">
        <form method="dialog"><button class="login-choice-close" aria-label="Close sign in options" autofocus>&times;</button></form>
        <div class="login-choice-mark" aria-hidden="true">✦</div>
        <p class="login-choice-eyebrow">YOUR NEXT CELEBRATION STARTS HERE</p>
        <h2 id="login-choice-title">Welcome to Shaadi Sense</h2>
        <p id="login-choice-description">A beautiful celebration. The right people.<br>Choose how you’d like to sign in.</p>
        <div class="login-choice-options">
            <a href="{{ route('user.login') }}" class="login-choice-option">
                <span class="login-choice-icon" aria-hidden="true"><i class="fa-solid fa-champagne-glasses"></i></span>
                <strong>User Login</strong><span>Plan your event, explore vendors<br>and keep every detail together.</span>
                <b>Plan my celebration <span aria-hidden="true">→</span></b>
            </a>
            <a href="{{ route('vendor.login') }}" class="login-choice-option login-choice-vendor">
                <span class="login-choice-icon" aria-hidden="true"><i class="fa-solid fa-store"></i></span>
                <strong>Vendor Login</strong><span>Showcase your services, manage<br>your business and connect.</span>
                <b>Open my business <span aria-hidden="true">→</span></b>
            </a>
        </div>
        <p class="login-choice-footer">New here? <a href="{{ route('user.register') }}">Create an account</a> <span aria-hidden="true">·</span> <a href="{{ route('vendor.register') }}">Join as a vendor</a></p>
    </div>
</dialog>
