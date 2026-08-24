<!doctype html><html><body style="font-family:Arial,sans-serif;color:#1e293b;line-height:1.6">
<h2>Shaadi Sense replied to your query</h2>
<p>Hello {{ $query->name }},</p>
<p><strong>Your query:</strong> {{ $query->subject }}</p>
<blockquote style="border-left:3px solid #850625;padding-left:14px;color:#475569">{{ $query->message }}</blockquote>
<p><strong>Admin reply:</strong></p><p>{{ $query->admin_reply }}</p>
<p>You can also view this reply in the Queries section of your user panel.</p>
</body></html>
