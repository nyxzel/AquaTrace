<h2>New Contact Message</h2>

<p><strong>Name:</strong> {{ $name }}</p>
<p><strong>Email:</strong> {{ $email }}</p>
<p><strong>Phone:</strong> {{ $phone ?? 'No phone provided' }}</p>
<p><strong>Subject:</strong> {{ $subject }}</p>

<p><strong>Message:</strong></p>
<p>{{ $body }}</p>