<dialog id="login-choice" class="login-choice" aria-labelledby="login-choice-title" aria-describedby="login-choice-description">
    <div class="login-choice-content">
        <!-- Close Form -->
        <form method="dialog">
            <button class="login-choice-close" aria-label="Close dialog" autofocus>&times;</button>
        </form>

        <!-- Brand Icon / Sparkle -->
        <div class="login-choice-mark" aria-hidden="true">
            <i class="fa-solid fa-wand-magic-sparkles text-lg text-[#D4AF37]"></i>
        </div>

        <p class="login-choice-eyebrow">YOUR ROYAL CELEBRATION STARTS HERE</p>
        <h2 id="login-choice-title" class="font-serif-luxury text-slate-900 tracking-tight">Welcome to Shaadi Sense</h2>
        <p id="login-choice-description">
            Experience magnificent event planning. Choose how you would like to proceed.
        </p>

        <!-- 2 Symmetrical Choice Cards -->
        <div class="login-choice-options">
            
            <!-- OPTION 1: USER / COUPLE LOGIN -->
            <a href="{{ route('user.login') }}" class="login-choice-option group">
                <span class="login-choice-icon" aria-hidden="true">
                    <i class="fa-solid fa-champagne-glasses text-lg"></i>
                </span>
                <strong>Couple & Host Sign In</strong>
                <span>Access your saved wedding budgets, AI planner recommendations, and verified vendor quotes.</span>
                <b class="group-hover:shadow-lg transition-all duration-300">
                    <span>Sign In to Account</span>
                    <i class="fa-solid fa-arrow-right text-[10px] ml-1.5 transition-transform group-hover:translate-x-1"></i>
                </b>
            </a>

            <!-- OPTION 2: VENDOR PARTNER ENQUIRY -->
            <a href="{{ route('vendor-enquiry.create') }}" class="login-choice-option login-choice-vendor group">
                <span class="login-choice-icon" aria-hidden="true">
                    <i class="fa-solid fa-handshake text-lg text-[#850625]"></i>
                </span>
                <strong>Vendor Partner Enquiry</strong>
                <span>List your banquet venue, decor, photography, or catering business to connect with couples.</span>
                <b class="group-hover:shadow-lg transition-all duration-300">
                    <span>Partner With Us</span>
                    <i class="fa-solid fa-arrow-right text-[10px] ml-1.5 transition-transform group-hover:translate-x-1"></i>
                </b>
            </a>

        </div>

        <!-- Footer -->
        <p class="login-choice-footer">
            Don't have an account yet? 
            <a href="{{ route('user.register') }}">Create an account</a>
            <span aria-hidden="true" class="text-slate-300 mx-1">·</span> 
            <a href="{{ route('vendor-enquiry.create') }}">List your business</a>
        </p>
    </div>
</dialog>
