<?php include 'include/classes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | BlogName</title>
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
                        navy: { 800: '#1e2336', 900: '#151928', 950: '#0d101a' },
                        crimson: { 500: '#e12b38', 600: '#c5202b' }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #fafafa; }
    </style>
</head>
<body class="text-slate-800 antialiased">

    <?php include 'include/frontend_header.php'; ?>

    <div class="bg-navy-900 py-16 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-4">Get in Touch</h1>
            <p class="text-slate-400">Have a story idea or feedback? We'd love to hear from you.</p>
        </div>
    </div>

    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Contact Info -->
            <div class="lg:col-span-1 space-y-8">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 flex items-start gap-6">
                    <div class="w-12 h-12 bg-crimson-50 text-crimson-600 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-envelope text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy-900 mb-1">Email Us</h3>
                        <p class="text-sm text-slate-500 mb-2">General inquiries & feedback</p>
                        <a href="mailto:hello@blogname.com" class="text-crimson-600 font-bold hover:underline">hello@blogname.com</a>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 flex items-start gap-6">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-newspaper text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy-900 mb-1">Press Room</h3>
                        <p class="text-sm text-slate-500 mb-2">Media & advertising queries</p>
                        <a href="mailto:press@blogname.com" class="text-blue-600 font-bold hover:underline">press@blogname.com</a>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 flex items-start gap-6">
                    <div class="w-12 h-12 bg-slate-50 text-slate-600 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-location-dot text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy-900 mb-1">Visit Us</h3>
                        <p class="text-sm text-slate-500 mb-2">Editorial Office</p>
                        <p class="text-slate-800 font-medium">123 Media Plaza, Digital City, DC 1024</p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2 bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-slate-100">
                <h2 class="text-2xl font-bold text-navy-900 mb-8">Send us a Message</h2>
                <form action="#" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Full Name</label>
                            <input type="text" placeholder="John Doe" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-sm focus:outline-none focus:border-crimson-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Email Address</label>
                            <input type="email" placeholder="john@example.com" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-sm focus:outline-none focus:border-crimson-500 transition-colors">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Subject</label>
                        <input type="text" placeholder="How can we help?" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-sm focus:outline-none focus:border-crimson-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Your Message</label>
                        <textarea rows="6" placeholder="Tell us more about your query..." class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-sm focus:outline-none focus:border-crimson-500 transition-colors resize-none"></textarea>
                    </div>
                    <button type="submit" class="bg-crimson-600 hover:bg-crimson-500 text-white font-bold py-4 px-8 rounded-lg text-sm uppercase tracking-wider transition-all shadow-lg shadow-crimson-600/20">Send Message</button>
                </form>
            </div>

        </div>
    </div>

    <?php include 'include/frontend_footer.php'; ?>

</body>
</html>
