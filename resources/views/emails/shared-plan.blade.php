<!doctype html>
<html lang="en">
<body style="margin:0;background:#fbf7f2;font-family:Arial,sans-serif;color:#1f2937">
    <div style="max-width:620px;margin:32px auto;background:#fff;border:1px solid #f3d2da;border-radius:18px;padding:32px">
        <div style="font-size:12px;font-weight:700;letter-spacing:.15em;color:#850625">SHAADI SENSE</div>
        <h1 style="margin:14px 0 8px;font-size:26px">{{ $plan->title }}</h1>
        <p style="margin:0 0 18px;line-height:1.6;color:#64748b">{{ $plan->user->name }} shared this wedding plan with you. The complete plan is attached as a PDF.</p>
        <div style="padding:16px;border-radius:12px;background:#fff5f7"><strong>{{ number_format($plan->guest_count) }} guests</strong><br>Estimated total: ₹{{ number_format((float) $plan->total_cost) }}</div>
    </div>
</body>
</html>
