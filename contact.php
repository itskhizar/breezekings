<?php 
include 'include/classes/session.php'; 

$site_url_c = bk_base_url();
$cur_url_c  = $site_url_c . '/contact';
$msg_status = '';

// Handle Contact Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    // 1. Honeypot check
    if (!empty($_POST['hp_company'])) {
        // Silent bot discard
        header("Location: /contact?status=success");
        exit();
    }

    $name    = trim(htmlspecialchars($_POST['name'] ?? ''));
    $email   = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
    $subject = trim(htmlspecialchars($_POST['subject'] ?? ''));
    $message = trim(htmlspecialchars($_POST['message'] ?? ''));

    if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($message)) {
        $msg_status = 'error';
    } else {
        // Send via PHPMailer (falls back to PHP mail() automatically)
        $sent = $mailer->sendContactForm($name, $email, $subject, $message);
        $msg_status = $sent ? 'success' : 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Breezekings</title>
    <meta name="title" content="Contact Us | Breezekings">
    <meta name="description" content="Contact the Breezekings editorial and advertising teams. Send story tips, corrections, partnership inquiries, or general feedback to azamwaseem44@gmail.com.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo $cur_url_c; ?>">
    <link rel="icon" type="image/svg+xml" href="/images/breezekings-icon-red.svg">
    <meta name="theme-color" content="#0B1F3A">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Breezekings">
    <meta property="og:title" content="Contact Us | Breezekings">
    <meta property="og:description" content="Get in touch with Breezekings. Send us your story tips, feedback or advertising enquiries.">
    <meta property="og:url" content="<?php echo $cur_url_c; ?>">
    <meta property="og:image" content="<?php echo $site_url_c; ?>/images/breezekings-icon-red.svg">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Contact Us | Breezekings">
    <meta name="twitter:description" content="Get in touch with Breezekings.">

    <!-- Structured Data: ContactPage -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ContactPage",
        "name": "Contact Us | Breezekings",
        "url": "<?php echo $cur_url_c; ?>",
        "description": "Get in touch with Breezekings. Send us your story tips, feedback or advertising enquiries.",
        "publisher": {
            "@type": "Organization",
            "name": "Breezekings",
            "url": "<?php echo $site_url_c; ?>/"
        }
    }
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    colors: {
                        navy: { 800: '#1e2336', 900: '#0B1F3A', 950: '#071526' },
                        crimson: { 500: '#e12b38', 600: '#C8102E', 700: '#a31a23' }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #fafafa; }
        .font-serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="text-slate-800 antialiased bg-slate-50">

    <?php include 'include/frontend_header.php'; ?>

    <div class="bg-navy-900 py-16 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <span class="inline-block px-3 py-1 bg-crimson-600 text-white text-xs font-bold uppercase tracking-widest rounded-full mb-3">Get in Touch</span>
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-4">Contact Breezekings</h1>
            <p class="text-slate-300 max-w-xl mx-auto text-sm md:text-base">
                We'd like to hear from you &mdash; whether it's a correction, a story tip, a partnership or advertising inquiry, or general feedback.
            </p>
        </div>
    </div>

    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Contact Info -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200/80 flex items-start gap-5">
                    <div class="w-12 h-12 bg-crimson-50 text-crimson-600 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-envelope text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy-900 mb-1">General Inquiries</h3>
                        <p class="text-xs text-slate-500 mb-2">Editorial desk, general questions &amp; feedback</p>
                        <a href="mailto:azamwaseem44@gmail.com" class="text-crimson-600 font-bold hover:underline text-sm break-all">azamwaseem44@gmail.com</a>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200/80 flex items-start gap-5">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-bullhorn text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy-900 mb-1">Advertising &amp; Partnerships</h3>
                        <p class="text-xs text-slate-500 mb-2">Sponsorships, ad placements &amp; brand collaborations</p>
                        <a href="mailto:azamwaseem44@gmail.com" class="text-blue-600 font-bold hover:underline text-sm break-all">azamwaseem44@gmail.com</a>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200/80 flex items-start gap-5">
                    <div class="w-12 h-12 bg-slate-100 text-slate-700 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-clock text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy-900 mb-1">Response Time &amp; Location</h3>
                        <p class="text-xs text-slate-500 mb-1">We aim to reply within <strong>2–3 business days</strong>.</p>
                        <p class="text-slate-700 text-xs font-semibold mt-1">Breezekings &bull; Lahore, Pakistan / Worldwide</p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2 bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-slate-200/80">
                <h2 class="text-2xl font-bold text-navy-900 mb-3 font-serif">Send Us a Direct Message</h2>
                <p class="text-xs text-slate-500 mb-8">Fill out the form below and our editorial desk will review your submission promptly.</p>

                <?php if ($msg_status === 'success' || (isset($_GET['status']) && $_GET['status'] === 'success')): ?>
                    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-5 rounded-r-lg text-sm text-emerald-900 mb-6 flex items-start gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-base"></i>
                        <div>
                            <p class="font-bold">Thank you! Your message has been sent successfully.</p>
                            <p class="text-xs text-emerald-700 mt-1">Our editorial desk has received your note at azamwaseem44@gmail.com and will respond within 2–3 business days.</p>
                        </div>
                    </div>
                <?php elseif ($msg_status === 'error'): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-5 rounded-r-lg text-sm text-red-900 mb-6 flex items-start gap-3">
                        <i class="fa-solid fa-circle-exclamation text-red-600 mt-0.5 text-base"></i>
                        <div>
                            <p class="font-bold">Please fill in all required fields.</p>
                            <p class="text-xs text-red-700 mt-1">Ensure your name, a valid email address, and message are provided.</p>
                        </div>
                    </div>
                <?php endif; ?>

                <form action="/contact" method="POST" class="space-y-6">
                    <input type="hidden" name="contact_submit" value="1">
                    
                    <!-- Anti-bot honeypot -->
                    <div class="hidden" aria-hidden="true">
                        <input type="text" name="hp_company" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Full Name *</label>
                            <input type="text" name="name" required placeholder="Jane Doe" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-navy-900/20 focus:border-navy-900 transition-all text-slate-800">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Email Address *</label>
                            <input type="email" name="email" required placeholder="jane@example.com" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-navy-900/20 focus:border-navy-900 transition-all text-slate-800">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Subject</label>
                        <input type="text" name="subject" placeholder="Story tip, correction, partnership inquiry..." class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-navy-900/20 focus:border-navy-900 transition-all text-slate-800">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Your Message *</label>
                        <textarea name="message" rows="6" required placeholder="Tell us how we can help..." class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-navy-900/20 focus:border-navy-900 transition-all text-slate-800 resize-none"></textarea>
                    </div>
                    <button type="submit" class="bg-crimson-600 hover:bg-crimson-700 text-white font-bold py-3.5 px-8 rounded-lg text-xs uppercase tracking-wider transition-all shadow-md hover:shadow-lg flex items-center gap-2">
                        <span>Send Message</span>
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                    </button>
                </form>
            </div>

        </div>
    </div>

    <?php include 'include/frontend_footer.php'; ?>

</body>
</html>
