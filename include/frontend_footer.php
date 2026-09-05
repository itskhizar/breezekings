<!-- Footer -->
<footer class="bg-navy-900 text-white pt-16 pb-8 border-t-4 border-crimson-600">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-12 mb-16">
            
            <!-- Brand -->
            <div class="lg:col-span-2">
                <a href="index.php" class="font-serif font-bold text-3xl tracking-tight mb-6 block">The Blog<span class="text-crimson-500">.</span></a>
                <p class="text-slate-400 text-sm leading-relaxed mb-6 max-w-sm">
                    Independent journalism for the digital age. Bringing clarity to the most complex issues of our time through rigorous reporting and expert analysis.
                </p>
                <div class="flex gap-3">
                    <a href="#" class="w-8 h-8 rounded-full bg-navy-800 flex items-center justify-center text-slate-300 hover:bg-crimson-600 hover:text-white transition-colors"><i class="fa-solid fa-share-nodes text-xs"></i></a>
                    <a href="#" class="w-8 h-8 rounded-full bg-navy-800 flex items-center justify-center text-slate-300 hover:bg-crimson-600 hover:text-white transition-colors"><i class="fa-brands fa-twitter text-xs"></i></a>
                </div>
            </div>

            <!-- Navigation -->
            <div>
                <h4 class="font-bold text-white mb-6 uppercase text-[11px] tracking-widest">Navigation</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="index.php" class="text-slate-400 hover:text-white transition-colors">Home</a></li>
                    <?php
                    $footer_cats = $database->get_all_categories();
                    $count = 0;
                    while($cat = mysqli_fetch_assoc($footer_cats)) {
                        if($count >= 4) break;
                        echo "<li><a href='category.php?id={$cat['id']}' class='text-slate-400 hover:text-white transition-colors'>{$cat['category']}</a></li>";
                        $count++;
                    }
                    ?>
                </ul>
            </div>

            <!-- Company -->
            <div>
                <h4 class="font-bold text-white mb-6 uppercase text-[11px] tracking-widest">Company</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="about.php" class="text-slate-400 hover:text-white transition-colors">About Us</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white transition-colors">Contact</a></li>
                    <li><a href="privacy-policy.php" class="text-slate-400 hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="termsofservices.php" class="text-slate-400 hover:text-white transition-colors">Terms of Service</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="lg:col-span-1">
                <h4 class="font-bold text-white mb-6 uppercase text-[11px] tracking-widest">Newsletter</h4>
                <p class="text-slate-400 text-xs mb-4">Get the morning edition directly to your inbox.</p>
                <form action="#" class="flex flex-col gap-3">
                    <input type="email" placeholder="Email address" required class="w-full bg-navy-800 border border-navy-800 text-white placeholder-slate-500 rounded py-2.5 px-3 text-sm focus:outline-none focus:border-slate-500 transition-colors">
                    <button type="submit" class="w-full bg-crimson-600 hover:bg-crimson-500 text-white font-bold py-2.5 px-4 rounded text-xs uppercase tracking-wider transition-colors">Subscribe</button>
                </form>
            </div>

        </div>

        <div class="border-t border-navy-800 pt-8 flex justify-center items-center">
            <p class="text-slate-500 text-xs">
                &copy; <?php echo date('Y'); ?> The Blog Editorial Group. All rights reserved.
            </p>
        </div>
    </div>
</footer>
