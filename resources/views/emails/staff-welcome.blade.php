<!DOCTYPE html><html><body style="margin:0;background:#f1f5f9;font-family:Arial,sans-serif;color:#1e293b">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:32px 12px"><tr><td align="center">
<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 15px 40px rgba(15,23,42,.12)">
<tr><td style="padding:32px;background:linear-gradient(135deg,#3950a2,#263875);color:#fff"><div style="font-size:23px;font-weight:800">Event<span style="color:#34d399">Planner</span></div><h1 style="font-size:28px;margin:22px 0 0">Your staff account is ready</h1></td></tr>
<tr><td style="padding:34px"><p style="font-size:16px">Hello {{ $staff->first_name }},</p><p style="font-size:14px;line-height:1.7;color:#475569">An administrator created an EventPlanner staff account for you. Use the credentials below to sign in. You will only see the sections assigned to your account.</p>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0;background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px"><tr><td style="padding:18px;font-size:14px;line-height:2"><strong>Email:</strong> {{ $staff->email }}<br><strong>Password:</strong> {{ $plainPassword }}</td></tr></table>
<p style="margin:24px 0"><a href="{{ route('admin.login') }}" style="display:inline-block;background:#3950a2;color:#fff;text-decoration:none;padding:13px 22px;border-radius:10px;font-size:14px;font-weight:bold">Sign in to Admin Panel</a></p>
<p style="font-size:13px;color:#64748b">For security, sign in and change your password from My Profile.</p></td></tr>
</table></td></tr></table></body></html>
