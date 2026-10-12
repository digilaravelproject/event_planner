<!DOCTYPE html><html><body style="margin:0;background:#f8f4ef;font-family:Arial,sans-serif;color:#1e293b">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:32px 12px"><tr><td align="center">
<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;background:#fff;border-radius:24px;overflow:hidden;box-shadow:0 15px 40px rgba(65,9,25,.12)">
<tr><td style="padding:36px;background:linear-gradient(135deg,#850625,#4b0719);color:#fff"><div style="font-size:23px;font-weight:800">Shaadi <span style="color:#e7c65d">Sense</span></div><div style="margin-top:28px;font-size:12px;letter-spacing:2px;color:#e7c65d;font-weight:bold">WELCOME TO OUR VENDOR NETWORK</div><h1 style="font-size:32px;line-height:1.2;margin:10px 0 0">Your business deserves the spotlight.</h1></td></tr>
<tr><td style="padding:36px"><p style="font-size:17px">Hello {{ $vendor->name }},</p><p style="font-size:15px;line-height:1.7;color:#475569">Your vendor account for <strong>{{ $vendor->business_name }}</strong> is ready. You can now create detailed business listings, add service attributes, upload images and keep your offerings current.</p>
<p style="margin:28px 0"><a href="{{ route('vendor.dashboard') }}" style="display:inline-block;background:#850625;color:#fff;text-decoration:none;padding:14px 24px;border-radius:12px;font-size:14px;font-weight:bold">Open Vendor Dashboard</a></p>
<div style="background:#faf7f2;border-radius:14px;padding:18px;color:#64748b;font-size:13px;line-height:1.6">Next step: complete your profile, then add your first business or service listing.</div>
<p style="margin-top:28px;font-size:14px;color:#475569">Warm regards,<br><strong>The Shaadi Sense Team</strong></p></td></tr>
</table></td></tr></table></body></html>
