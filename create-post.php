<?php
include("include/classes/session.php");

if (!$session->logged_in) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Post | BlogAdmin</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['ui-serif', 'Georgia', 'Cambria', 'Times New Roman', 'Times', 'serif'],
                    },
                    colors: {
                        brand: {
                            sidebar: '#151928',
                            blue: '#2563eb',
                            hover: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .sidebar-link { transition: all 0.2s ease; }
        .sidebar-link:hover { background-color: rgba(255, 255, 255, 0.05); }
        .sidebar-link.active { background-color: rgba(255, 255, 255, 0.08); border-left: 3px solid #2563eb; }
        
        /* Custom scrollbar for editor */
        .editor-content::-webkit-scrollbar { width: 6px; }
        .editor-content::-webkit-scrollbar-track { background: transparent; }
        .editor-content::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-slate-800 antialiased">

    <?php include 'include/admin_sidebar.php'; ?>
    
    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col h-full overflow-hidden">
        
        <?php include 'include/admin_header.php'; ?>

        <!-- Main Content Scrollable -->
        <main class="flex-1 overflow-y-auto p-6 lg:p-8">
            <div class="max-w-[1400px] mx-auto">
                
                <!-- Page Header & Actions -->
                <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-6">
                    <div>
                        <div class="text-[11px] font-medium text-slate-400 mb-2 flex items-center gap-2">
                            <a href="dashboard.php" class="hover:text-brand-blue transition-colors">Dashboard</a> 
                            <i class="fa-solid fa-chevron-right text-[8px]"></i> 
                            <a href="posts.php" class="hover:text-brand-blue transition-colors">Posts</a>
                            <i class="fa-solid fa-chevron-right text-[8px]"></i> 
                            <span class="text-slate-700 font-semibold">Create New Post</span>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900">Create New Post</h2>
                    </div>
                    <div class="mt-4 md:mt-0 flex gap-3">
                        <button type="button" onclick="submitPost()" class="bg-brand-blue hover:bg-brand-hover text-white font-semibold py-2 px-5 rounded shadow-sm transition-colors text-sm flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Publish Post
                        </button>
                    </div>
                </div>

                <!-- Alert Messages -->
                <?php if (isset($_GET['msg'])): ?>
                    <div id="statusAlert" class="relative">
                    <?php if ($_GET['msg'] == 'error' || $_GET['msg'] == 'db_error'): ?>
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r flex items-center justify-between gap-3 shadow-sm">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                                <p class="text-sm text-red-700 font-medium">There was an error saving your post. Please check your inputs.</p>
                            </div>
                            <button onclick="document.getElementById('statusAlert').style.display='none'" class="text-red-400 hover:text-red-600 transition-colors">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Content Grid -->
                <form action="process.php" method="POST" enctype="multipart/form-data" id="postForm">
                    <input type="hidden" name="add_post" value="1">
                    <input type="hidden" name="content" id="postContent">
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <!-- Left Column (Editor) -->
                        <div class="lg:col-span-2 flex flex-col gap-6">
                            
                            <!-- Main Editor Card -->
                            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                                
                                <!-- Title Input -->
                                <div class="p-6 border-b border-slate-100">
                                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Post Title <span class="text-red-500">*</span></label>
                                    <input type="text" name="title" id="postTitle" required class="w-full text-3xl font-bold text-slate-800 border-none outline-none placeholder-slate-300" placeholder="Enter post title here...">
                                    
                                    <!-- Slug Input -->
                                    <div class="mt-4 flex items-center gap-2 text-xs text-slate-500 bg-slate-50 p-2 rounded border border-slate-100">
                                        <span class="font-semibold text-slate-700"><i class="fa-solid fa-link text-[10px] text-crimson-600 mr-1"></i> Clean Permalink:</span>
                                        <span class="text-slate-400 font-mono">/post/bk.../</span>
                                        <input type="text" name="slug" id="postSlug" class="border-none p-0 focus:ring-0 text-crimson-600 font-semibold font-mono bg-transparent min-w-[220px]" placeholder="post-slug-auto-generated">
                                        <button type="button" onclick="generateSlug()" title="Regenerate slug from title" class="text-slate-400 hover:text-crimson-600 ml-auto flex items-center gap-1 text-[11px] font-medium"><i class="fa-solid fa-arrows-rotate"></i> Auto-Generate</button>
                                    </div>
                                </div>

                                <!-- Excerpt -->
                                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Short Excerpt (Summary)</label>
                                    <textarea name="excerpt" rows="2" class="w-full bg-transparent border-none outline-none text-sm text-slate-600 resize-none italic" placeholder="Provide a brief summary of this post..."></textarea>
                                </div>

                                <!-- Formatting Toolbar -->
                                <div class="px-4 py-2 border-b border-slate-100 flex flex-wrap gap-1 items-center bg-white">
                                    <div class="flex items-center gap-1 pr-3 border-r border-slate-200">
                                        <button type="button" onclick="document.execCommand('bold')" class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 flex items-center justify-center"><i class="fa-solid fa-bold text-sm"></i></button>
                                        <button type="button" onclick="document.execCommand('italic')" class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 flex items-center justify-center"><i class="fa-solid fa-italic text-sm"></i></button>
                                        <button type="button" onclick="document.execCommand('underline')" class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 flex items-center justify-center"><i class="fa-solid fa-underline text-sm"></i></button>
                                    </div>
                                    <div class="flex items-center gap-1 px-3 border-r border-slate-200">
                                        <button type="button" onclick="document.execCommand('insertUnorderedList')" class="w-8 h-8 rounded hover:bg-slate-100 text-slate-600 flex items-center justify-center"><i class="fa-solid fa-list-ul"></i></button>
                                        <button type="button" onclick="document.execCommand('insertOrderedList')" class="w-8 h-8 rounded hover:bg-slate-100 text-slate-600 flex items-center justify-center"><i class="fa-solid fa-list-ol"></i></button>
                                    </div>
                                </div>

                                <!-- Content Area -->
                                <div class="p-6 flex-1 min-h-[400px]">
                                    <div id="editor" class="w-full h-full text-slate-800 outline-none editor-content text-base leading-relaxed font-serif" contenteditable="true">
                                        <p>Start writing your amazing post content here...</p>
                                    </div>
                                </div>

                                <!-- Bottom Status Bar -->
                                <div class="p-3 border-t border-slate-100 bg-slate-50 flex justify-between items-center text-[11px] text-slate-400">
                                    <div class="flex items-center gap-4">
                                        <span>Words: <span id="wordCount" class="font-bold text-slate-600">0</span></span>
                                        <span id="seoWarning" class="transition-all opacity-0"></span>
                                    </div>
                                    <div class="flex gap-4">
                                        <span>Reading Time: <span id="readingTime" class="font-medium text-slate-600">0</span> min</span>
                                        <span>Status: <span class="font-medium text-slate-600">Ready</span></span>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Right Column (Widgets) -->
                        <div class="flex flex-col gap-6">
                            
                            <!-- Publish Settings -->
                            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                                <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-white">
                                    <h3 class="font-semibold text-slate-800 text-sm">Publish Settings</h3>
                                </div>
                                <div class="p-5 space-y-4">
                                    
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 mb-2">Post Status</label>
                                        <select name="status" id="postStatus" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-sm text-slate-700 outline-none focus:border-brand-blue">
                                            <option value="Draft">Draft</option>
                                            <option value="Published">Published</option>
                                        </select>
                                        <p id="statusNote" class="text-[10px] text-amber-600 mt-1 hidden italic">Note: Missing mandatory fields (*). Post will be saved as Draft.</p>
                                    </div>

                                    <div class="flex items-center justify-between py-2">
                                        <label class="text-sm text-slate-600 font-medium">Featured Post</label>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="is_featured" class="sr-only peer">
                                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-blue"></div>
                                        </label>
                                    </div>

                                    <div class="pt-4">
                                        <button type="button" onclick="submitPost()" class="w-full bg-brand-blue hover:bg-brand-hover text-white font-semibold py-2.5 rounded shadow-sm transition-colors text-sm flex items-center justify-center gap-2">
                                            <i class="fa-solid fa-paper-plane"></i> Save Post
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Featured Image -->
                            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                                <div class="p-4 border-b border-slate-100 flex items-center gap-2">
                                    <i class="fa-regular fa-image text-slate-400"></i>
                                    <h3 class="font-semibold text-slate-800 text-sm">Featured Image <span class="text-red-500">*</span></h3>
                                </div>
                                <div class="p-5">
                                    <div class="border-2 border-dashed border-slate-200 rounded-lg p-4 text-center hover:border-brand-blue transition-colors cursor-pointer" onclick="document.getElementById('thumbInput').click()">
                                        <i class="fa-solid fa-cloud-arrow-up text-slate-300 text-2xl mb-2"></i>
                                        <p class="text-[10px] text-slate-500 font-medium">Click to upload featured image</p>
                                        <input type="file" id="thumbInput" name="thumbnail" class="hidden" onchange="previewImage(this)">
                                    </div>
                                    <div id="imagePreview" class="mt-3 hidden">
                                        <img src="" class="w-full h-32 object-cover rounded border border-slate-100">
                                    </div>
                                    <p class="text-[10px] text-slate-400 text-center mt-3 uppercase tracking-wider">Recommended size: 1200 Ã— 630 px</p>
                                </div>
                            </div>

                            <!-- Categories -->
                            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                                <div class="p-4 border-b border-slate-100 flex items-center gap-2">
                                    <i class="fa-regular fa-folder text-slate-400"></i>
                                    <h3 class="font-semibold text-slate-800 text-sm">Category <span class="text-red-500">*</span></h3>
                                </div>
                                <div class="p-5">
                                    <select name="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-sm text-slate-700 outline-none focus:border-brand-blue">
                                        <option value="">Select Category</option>
                                        <?php
                                        $cats = $database->get_all_categories_admin();
                                        while ($cat = mysqli_fetch_assoc($cats)) {
                                            echo "<option value='{$cat['id']}'>{$cat['category']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <!-- SEO Settings -->
                            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                                <div class="p-4 border-b border-slate-100 flex items-center gap-2">
                                    <i class="fa-solid fa-magnifying-glass-chart text-slate-400"></i>
                                    <h3 class="font-semibold text-slate-800 text-sm">SEO Meta Tags</h3>
                                </div>
                                <div class="p-5 space-y-4">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Meta Title</label>
                                        <input type="text" name="meta_title" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-sm text-slate-700 outline-none focus:border-brand-blue" placeholder="Google Search Title">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Meta Description</label>
                                        <textarea name="meta_description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-sm text-slate-700 outline-none focus:border-brand-blue resize-none" placeholder="Search result snippet..."></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Tags (Press Enter/Space to add)</label>
                                        <div id="tagContainer" class="flex flex-wrap gap-2 p-2 bg-slate-50 border border-slate-200 rounded min-h-[40px]">
                                            <input type="text" id="tagInput" class="bg-transparent border-none outline-none text-sm text-slate-700 min-w-[100px] flex-1" placeholder="Add tags...">
                                        </div>
                                        <input type="hidden" name="tags" id="hiddenTags">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>

            </div>
            
            <!-- Footer -->
            <footer class="bg-[#f4f7f6] border-t border-slate-200 py-4 px-6 md:px-8 text-xs font-medium flex justify-between mt-auto">
                <p class="text-slate-400">&copy; <?php echo date('Y'); ?> BlogAdmin Management Suite. All rights reserved.</p>
                <div class="flex gap-4">
                    <a href="#" class="text-slate-400 hover:text-brand-blue">Privacy Policy</a>
                    <a href="#" class="text-slate-400 hover:text-brand-blue">Terms of Service</a>
                </div>
            </footer>
        </main>
    </div>

    <script>
        // â”€â”€ Helper: capture editor â†’ submit form â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        function submitPost() {
            document.getElementById('postContent').value = document.getElementById('editor').innerHTML;
            document.getElementById('postForm').submit();
        }

        // â”€â”€ Keyboard shortcut: Ctrl+S to save â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                submitPost();
            }
        });

        // â”€â”€ Slugify helper â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        function slugify(text) {
            return text.toString().toLowerCase().trim()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')    // remove accents
                .replace(/[^a-z0-9\s-]/g, '')        // strip special chars
                .replace(/[\s-]+/g, '-')             // collapse spaces/hyphens
                .replace(/^-+|-+$/g, '');            // trim hyphens
        }

        // â”€â”€ Slug auto-generation â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        const postTitle  = document.getElementById('postTitle');
        const postSlug   = document.getElementById('postSlug');
        let slugManuallyChanged = false;

        if (postSlug) {
            postSlug.addEventListener('input', function() {
                slugManuallyChanged = postSlug.value.trim().length > 0;
            });
        }

        if (postTitle) {
            postTitle.addEventListener('input', function() {
                if (!slugManuallyChanged && postSlug) {
                    postSlug.value = slugify(postTitle.value);
                }
            });
        }

        function generateSlug() {
            if (postTitle && postSlug) {
                const s = slugify(postTitle.value);
                postSlug.value = s || 'article';
                slugManuallyChanged = false;
            }
        }

        // â”€â”€ Tag System â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        const tagContainer = document.getElementById('tagContainer');
        const tagInput     = document.getElementById('tagInput');
        const hiddenTags   = document.getElementById('hiddenTags');
        let tags = [];

        function updateTags() {
            const tagElements = tags.map((tag, index) => `
                <span class="bg-brand-blue/10 text-brand-blue text-[11px] font-bold px-2 py-1 rounded flex items-center gap-1">
                    ${tag}
                    <button type="button" onclick="removeTag(${index})" class="hover:text-brand-hover"><i class="fa-solid fa-xmark"></i></button>
                </span>
            `).join('');
            tagContainer.innerHTML = tagElements;
            tagContainer.appendChild(tagInput);
            tagInput.focus();
            hiddenTags.value = tags.join(',');
        }

        function removeTag(index) {
            tags.splice(index, 1);
            updateTags();
        }

        if (tagInput) {
            tagInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    const tag = this.value.trim().replace(/,/g, '');
                    if (tag && !tags.includes(tag)) {
                        tags.push(tag);
                        this.value = '';
                        updateTags();
                    }
                } else if (e.key === 'Backspace' && this.value === '' && tags.length > 0) {
                    tags.pop();
                    updateTags();
                }
            });
        }

        // â”€â”€ Image Preview â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    preview.querySelector('img').src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // â”€â”€ Word Count & SEO Warning â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        const editor            = document.getElementById('editor');
        const wordCountDisplay  = document.getElementById('wordCount');
        const readingTimeDisplay= document.getElementById('readingTime');
        const seoWarning        = document.getElementById('seoWarning');

        function updateWordCount() {
            const text  = editor.innerText || editor.textContent || '';
            const words = text.trim() === '' ? 0 : text.trim().split(/\s+/).length;
            wordCountDisplay.innerText   = words;
            readingTimeDisplay.innerText = Math.max(1, Math.ceil(words / 200));

            if (words === 0) {
                seoWarning.style.opacity = '0';
            } else if (words < 300) {
                seoWarning.innerText   = 'âš  Too short for SEO (min 300 words)';
                seoWarning.className   = 'text-amber-500 font-medium transition-all';
                seoWarning.style.opacity = '1';
            } else if (words < 800) {
                seoWarning.innerText   = 'âœ“ Good â€” 800+ words is even better';
                seoWarning.className   = 'text-blue-500 font-medium transition-all';
                seoWarning.style.opacity = '1';
            } else {
                seoWarning.innerText   = 'âœ“ Excellent length for SEO!';
                seoWarning.className   = 'text-emerald-500 font-medium transition-all';
                seoWarning.style.opacity = '1';
            }
        }

        editor.addEventListener('input', updateWordCount);
        updateWordCount();

        // â”€â”€ localStorage Auto-save â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        const DRAFT_KEY = 'bk_draft_create';

        function saveDraft() {
            const data = {
                title:   postTitle ? postTitle.value : '',
                slug:    postSlug  ? postSlug.value  : '',
                content: editor.innerHTML,
                ts:      Date.now()
            };
            try { localStorage.setItem(DRAFT_KEY, JSON.stringify(data)); } catch(e) {}
        }

        function restoreDraft() {
            try {
                const saved = localStorage.getItem(DRAFT_KEY);
                if (!saved) return;
                const data = JSON.parse(saved);
                // Only restore if saved within last 12 hours
                if ((Date.now() - data.ts) > 43200000) { localStorage.removeItem(DRAFT_KEY); return; }
                if (postTitle && !postTitle.value && data.title) postTitle.value = data.title;
                if (postSlug  && !postSlug.value  && data.slug)  postSlug.value  = data.slug;
                if (editor && data.content && editor.innerHTML.trim().length < 50) {
                    editor.innerHTML = data.content;
                    updateWordCount();
                }
            } catch(e) {}
        }

        restoreDraft();
        setInterval(saveDraft, 10000); // auto-save every 10 seconds
        editor.addEventListener('input', saveDraft);

        // Clear draft on successful submit
        document.getElementById('postForm').addEventListener('submit', function() {
            document.getElementById('postContent').value = editor.innerHTML;
            try { localStorage.removeItem(DRAFT_KEY); } catch(e) {}
        });

        // â”€â”€ Validation / Draft Fallback â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        const postForm       = document.getElementById('postForm');
        const postStatus     = document.getElementById('postStatus');
        const statusNote     = document.getElementById('statusNote');
        const thumbInput     = document.getElementById('thumbInput');
        const categorySelect = document.querySelector('select[name="category_id"]');

        function checkMandatoryFields() {
            const hasTitle    = postTitle && postTitle.value.trim() !== '';
            const hasCategory = categorySelect && categorySelect.value !== '';
            const hasContent  = editor.innerText.trim() !== '';
            const hasImage    = thumbInput && thumbInput.files.length > 0;

            if ((!hasTitle || !hasCategory || !hasContent || !hasImage) && postStatus && postStatus.value === 'Published') {
                if (statusNote) statusNote.classList.remove('hidden');
            } else {
                if (statusNote) statusNote.classList.add('hidden');
            }
        }

        [postTitle, categorySelect, thumbInput].forEach(el => {
            if (!el) return;
            el.addEventListener('change', checkMandatoryFields);
            el.addEventListener('input',  checkMandatoryFields);
        });
        editor.addEventListener('input', checkMandatoryFields);
        if (postStatus) postStatus.addEventListener('change', checkMandatoryFields);

        checkMandatoryFields();
    </script>

</body>
</html>
