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
                        <button type="button" onclick="document.getElementById('postForm').submit();" class="bg-brand-blue hover:bg-brand-hover text-white font-semibold py-2 px-5 rounded shadow-sm transition-colors text-sm flex items-center gap-2">
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
                                    <div class="mt-4 flex items-center gap-2 text-xs text-slate-400">
                                        <span class="font-medium">Permalink:</span>
                                        <span class="text-slate-300">/posts/</span>
                                        <input type="text" name="slug" id="postSlug" class="border-none p-0 focus:ring-0 text-brand-blue font-medium bg-transparent min-w-[200px]" placeholder="post-slug-here">
                                        <button type="button" onclick="generateSlug()" class="text-slate-400 hover:text-brand-blue"><i class="fa-solid fa-arrows-rotate"></i></button>
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
                                        <button type="submit" onclick="document.getElementById('postContent').value = document.getElementById('editor').innerHTML;" class="w-full bg-brand-blue hover:bg-brand-hover text-white font-semibold py-2.5 rounded shadow-sm transition-colors text-sm flex items-center justify-center gap-2">
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
                                    <p class="text-[10px] text-slate-400 text-center mt-3 uppercase tracking-wider">Recommended size: 1200 × 630 px</p>
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
                                        $cats = $database->get_all_categories();
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
        function generateSlug(text) {
            return text.toString().toLowerCase()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                .replace(/^-+/, '')             // Trim - from start of text
                .replace(/-+$/, '');            // Trim - from end of text
        }

        document.getElementById('postTitle').addEventListener('input', function() {
            const slugInput = document.getElementById('postSlug');
            // Only auto-generate if the slug is empty or was auto-generated from previous title
            if (!slugInput.dataset.manual) {
                slugInput.value = generateSlug(this.value);
            }
        });

        document.getElementById('postSlug').addEventListener('input', function() {
            this.dataset.manual = true;
        });

        // Tag System
        const tagContainer = document.getElementById('tagContainer');
        const tagInput = document.getElementById('tagInput');
        const hiddenTags = document.getElementById('hiddenTags');
        let tags = [];

        function updateTags() {
            const tagElements = tags.map((tag, index) => `
                <span class="bg-brand-blue/10 text-brand-blue text-[11px] font-bold px-2 py-1 rounded flex items-center gap-1">
                    ${tag}
                    <button type="button" onclick="removeTag(${index})" class="hover:text-brand-hover"><i class="fa-solid fa-xmark"></i></button>
                </span>
            `).join('');
            
            // Re-insert input after tags
            tagContainer.innerHTML = tagElements;
            tagContainer.appendChild(tagInput);
            tagInput.focus();
            
            hiddenTags.value = tags.join(',');
        }

        function removeTag(index) {
            tags.splice(index, 1);
            updateTags();
        }

        tagInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
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

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    preview.querySelector('img').src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Word Count and SEO Warning
        const editor = document.getElementById('editor');
        const wordCountDisplay = document.getElementById('wordCount');
        const readingTimeDisplay = document.getElementById('readingTime');
        const seoWarning = document.getElementById('seoWarning');

        function updateWordCount() {
            const text = editor.innerText || editor.textContent;
            const words = text.trim() === "" ? 0 : text.trim().split(/\s+/).length;
            wordCountDisplay.innerText = words;
            
            // Reading Time logic: 200 words per minute
            readingTimeDisplay.innerText = Math.ceil(words / 200);

            if (words === 0) {
                seoWarning.style.opacity = '0';
            } else if (words < 300) {
                seoWarning.innerText = "Content is too short for SEO (Min: 300 words)";
                seoWarning.className = "text-amber-500 font-medium ml-4 transition-all opacity-100";
                seoWarning.style.opacity = '1';
            } else if (words >= 300 && words < 800) {
                seoWarning.innerText = "Good start, but 800+ is better for SEO";
                seoWarning.className = "text-blue-500 font-medium ml-4 transition-all opacity-100";
                seoWarning.style.opacity = '1';
            } else {
                seoWarning.innerText = "Excellent length for SEO!";
                seoWarning.className = "text-emerald-500 font-medium ml-4 transition-all opacity-100";
                seoWarning.style.opacity = '1';
            }
        }

        editor.addEventListener('input', updateWordCount);
        // Initialize on load
        updateWordCount();

        // Validation for Draft fallback
        const postForm = document.getElementById('postForm');
        const postStatus = document.getElementById('postStatus');
        const statusNote = document.getElementById('statusNote');
        const thumbInput = document.getElementById('thumbInput');
        const postTitle = document.getElementById('postTitle');
        const categorySelect = document.querySelector('select[name="category_id"]');

        function checkMandatoryFields() {
            const hasTitle = postTitle.value.trim() !== "";
            const hasCategory = categorySelect.value !== "";
            const hasContent = editor.innerText.trim() !== "";
            const hasImage = thumbInput.files.length > 0;

            if (!hasTitle || !hasCategory || !hasContent || !hasImage) {
                if (postStatus.value === 'Published') {
                    statusNote.classList.remove('hidden');
                } else {
                    statusNote.classList.add('hidden');
                }
                return false;
            } else {
                statusNote.classList.add('hidden');
                return true;
            }
        }

        [postTitle, categorySelect, thumbInput].forEach(el => {
            el.addEventListener('change', checkMandatoryFields);
            el.addEventListener('input', checkMandatoryFields);
        });
        editor.addEventListener('input', checkMandatoryFields);
        postStatus.addEventListener('change', checkMandatoryFields);

        postForm.addEventListener('submit', function(e) {
            document.getElementById('postContent').value = editor.innerHTML;
            if (!checkMandatoryFields() && postStatus.value === 'Published') {
                // We'll let it submit, but the server side will force Draft
                // Or we can force it here:
                // postStatus.value = 'Draft';
            }
        });
    </script>

</body>
</html>
