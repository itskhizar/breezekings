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
                        },
                        navy: {
                            50: '#eef0f7',
                            800: '#1e2336',
                            900: '#0B1F3A',
                            950: '#071324',
                        },
                        crimson: {
                            50: '#fff0f1',
                            100: '#ffe1e3',
                            200: '#ffc7ca',
                            500: '#e12b38',
                            600: '#C5202B',
                            700: '#a31a23',
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

        /* Editor content typography */
        .editor-content { min-height: 380px; font-family: 'Inter', sans-serif; color: #334155; }
        .editor-content p { margin-bottom: 1.1rem; line-height: 1.8; }
        .editor-content a { color: #C5202B; font-weight: 600; text-decoration: underline; text-decoration-thickness: 1.5px; text-underline-offset: 3px; cursor: pointer; }
        .editor-content a:hover { color: #0B1F3A; }
        .editor-content h2 { font-family: 'Playfair Display', serif; font-weight: 700; font-size: 1.75rem; color: #0f172a; margin-top: 2rem; margin-bottom: 1rem; border-left: 4px solid #C5202B; padding-left: 0.75rem; }
        .editor-content h3 { font-weight: 700; font-size: 1.3rem; color: #0f172a; margin-top: 1.5rem; margin-bottom: 0.75rem; }
        .editor-content h4 { font-weight: 700; font-size: 1.1rem; color: #1e293b; margin-top: 1.25rem; margin-bottom: 0.5rem; }
        .editor-content ul { list-style-type: disc; padding-left: 1.75rem; margin-bottom: 1.25rem; }
        .editor-content ol { list-style-type: decimal; padding-left: 1.75rem; margin-bottom: 1.25rem; }
        .editor-content li { margin-bottom: 0.35rem; line-height: 1.7; }
        .editor-content blockquote { font-family: 'Playfair Display', serif; font-style: italic; font-size: 1.15rem; color: #334155; background-color: #f8fafc; padding: 1rem 1.5rem; border-left: 4px solid #C5202B; margin: 1.5rem 0; border-radius: 0 0.5rem 0.5rem 0; }
        .editor-content table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; font-size: 0.9rem; }
        .editor-content th, .editor-content td { border: 1px solid #cbd5e1; padding: 0.6rem 0.9rem; text-align: left; }
        .editor-content th { background-color: #f1f5f9; font-weight: 700; color: #0f172a; }
        .editor-content tr:nth-child(even) td { background-color: #f8fafc; }
        .editor-content code { background-color: #f1f5f9; color: #c5202b; padding: 0.15rem 0.4rem; border-radius: 0.25rem; font-size: 0.9em; font-family: monospace; }
        .editor-content hr { border: 0; border-top: 1px solid #e2e8f0; margin: 2rem 0; }
        .editor-content img { max-width: 100%; height: auto; border-radius: 0.5rem; margin: 1.5rem auto; display: block; }

        /* WordPress-style editor placeholder */
        .editor-content:empty::before {
            content: attr(data-placeholder);
            color: #94a3b8;
            font-style: italic;
            pointer-events: none;
            display: block;
        }
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

                <!-- WordPress-style Draft Recovery Banner -->
                <div id="draftRestoreBanner" class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-amber-900 hidden shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <div class="w-6 h-6 rounded-full bg-amber-200 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-clock-rotate-left text-amber-700 text-[11px]"></i>
                        </div>
                        <span>An autosaved post draft is available (<span id="draftTime" class="font-semibold">recently</span>). Would you like to restore it?</span>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <button type="button" onclick="confirmRestoreDraft()" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-md transition-colors shadow-xs">Restore Draft</button>
                        <button type="button" onclick="discardDraft()" class="px-3 py-1.5 text-amber-700 hover:text-amber-900 hover:bg-amber-100 rounded-md transition-colors">Discard</button>
                    </div>
                </div>

                <!-- Content Grid -->
                <form action="process.php" method="POST" enctype="multipart/form-data" id="postForm">
                    <input type="hidden" name="add_post" value="1">
                    <input type="hidden" name="content" id="postContent">
                    <!-- Double-submit prevention token (unique per page load) -->
                    <input type="hidden" name="submit_token" id="submitToken" value="">
                    <input type="hidden" name="form_loaded_at" id="formLoadedAt" value="">
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <!-- Left Column (Editor) -->
                        <div class="lg:col-span-2 flex flex-col gap-6">
                            
                            <!-- Main Editor Card -->
                            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                                
                                <!-- Title Input (WordPress-style clean start) -->
                                <div class="p-6 border-b border-slate-100">
                                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Post Title <span class="text-red-500">*</span></label>
                                    <input type="text" name="title" id="postTitle" required class="w-full text-3xl font-bold text-slate-800 border-none outline-none placeholder-slate-300" placeholder="Add title" autocomplete="off">
                                    
                                    <!-- WordPress-style Clean Permalink Bar -->
                                    <div id="permalinkBox" class="mt-3 flex flex-wrap items-center gap-2 text-xs text-slate-500 bg-slate-50 p-2.5 rounded-lg border border-slate-200/80">
                                        <span class="font-semibold text-slate-700 flex items-center gap-1.5"><i class="fa-solid fa-link text-crimson-600 text-xs"></i> Permalink:</span>
                                        <span class="text-slate-400 font-mono">https://breezekings.com/post/</span>
                                        <span id="slugDisplayWrapper" class="flex items-center gap-1 font-mono">
                                            <span id="slugPreview" class="text-crimson-600 font-semibold">(auto-generated from title)</span>
                                            <input type="text" name="slug" id="postSlug" class="hidden font-mono text-xs text-crimson-600 font-semibold bg-white border border-slate-300 rounded px-2 py-0.5 outline-none focus:border-crimson-500 focus:ring-1 focus:ring-crimson-500 min-w-[200px]" placeholder="post-slug">
                                        </span>
                                        <div class="ml-auto flex items-center gap-1.5">
                                            <button type="button" id="editSlugBtn" onclick="toggleEditSlug()" class="text-slate-600 hover:text-navy-900 px-2.5 py-1 rounded bg-white border border-slate-200 hover:border-slate-300 text-[11px] font-medium transition-all shadow-xs flex items-center gap-1">
                                                <i class="fa-solid fa-pen text-[10px]"></i> Edit
                                            </button>
                                            <button type="button" id="saveSlugBtn" onclick="saveCustomSlug()" class="hidden text-white bg-crimson-600 hover:bg-crimson-700 px-2.5 py-1 rounded text-[11px] font-medium transition-all shadow-xs flex items-center gap-1">
                                                <i class="fa-solid fa-check text-[10px]"></i> OK
                                            </button>
                                            <button type="button" id="resetSlugBtn" onclick="resetSlugToTitle()" title="Reset to auto-generate from title" class="text-slate-400 hover:text-crimson-600 px-2 py-1 text-[11px] font-medium transition-colors hidden flex items-center gap-1">
                                                <i class="fa-solid fa-arrows-rotate text-[10px]"></i> Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Excerpt -->
                                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Short Excerpt (Summary)</label>
                                    <textarea name="excerpt" rows="2" class="w-full bg-transparent border-none outline-none text-sm text-slate-600 resize-none italic" placeholder="Provide a brief summary of this post..."></textarea>
                                </div>

                                <!-- Formatting Toolbar -->
                                <div class="px-4 py-2 border-b border-slate-100 flex flex-wrap gap-1.5 items-center bg-white sticky top-0 z-10" id="editorToolbar">
                                    <!-- Headings / Block Dropdown -->
                                    <div class="flex items-center gap-1 pr-2 border-r border-slate-200">
                                        <select id="headingSelect" onchange="formatHeading(this.value)" class="h-8 px-2 text-xs font-semibold text-slate-700 bg-slate-50 border border-slate-200 rounded hover:bg-slate-100 focus:outline-none focus:border-brand-blue cursor-pointer">
                                            <option value="p">Paragraph</option>
                                            <option value="h2">Heading 2 (H2)</option>
                                            <option value="h3">Heading 3 (H3)</option>
                                            <option value="h4">Heading 4 (H4)</option>
                                        </select>
                                    </div>

                                    <!-- Inline Formatting -->
                                    <div class="flex items-center gap-1 pr-2 border-r border-slate-200">
                                        <button type="button" onclick="formatDoc('bold')" title="Bold (Ctrl+B)" class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 flex items-center justify-center transition-colors"><i class="fa-solid fa-bold text-xs"></i></button>
                                        <button type="button" onclick="formatDoc('italic')" title="Italic (Ctrl+I)" class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 flex items-center justify-center transition-colors"><i class="fa-solid fa-italic text-xs"></i></button>
                                        <button type="button" onclick="formatDoc('underline')" title="Underline (Ctrl+U)" class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 flex items-center justify-center transition-colors"><i class="fa-solid fa-underline text-xs"></i></button>
                                        <button type="button" onclick="formatDoc('strikeThrough')" title="Strikethrough" class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 flex items-center justify-center transition-colors"><i class="fa-solid fa-strikethrough text-xs"></i></button>
                                    </div>

                                    <!-- Alignment -->
                                    <div class="flex items-center gap-1 pr-2 border-r border-slate-200">
                                        <button type="button" onclick="formatDoc('justifyLeft')" title="Align Left" class="w-8 h-8 rounded hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors"><i class="fa-solid fa-align-left text-xs"></i></button>
                                        <button type="button" onclick="formatDoc('justifyCenter')" title="Align Center" class="w-8 h-8 rounded hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors"><i class="fa-solid fa-align-center text-xs"></i></button>
                                        <button type="button" onclick="formatDoc('justifyRight')" title="Align Right" class="w-8 h-8 rounded hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors"><i class="fa-solid fa-align-right text-xs"></i></button>
                                    </div>

                                    <!-- Lists & Quotes -->
                                    <div class="flex items-center gap-1 pr-2 border-r border-slate-200">
                                        <button type="button" onclick="formatDoc('insertUnorderedList')" title="Bulleted List" class="w-8 h-8 rounded hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors"><i class="fa-solid fa-list-ul text-xs"></i></button>
                                        <button type="button" onclick="formatDoc('insertOrderedList')" title="Numbered List" class="w-8 h-8 rounded hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors"><i class="fa-solid fa-list-ol text-xs"></i></button>
                                        <button type="button" onclick="insertBlockquote()" title="Blockquote" class="w-8 h-8 rounded hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors"><i class="fa-solid fa-quote-left text-xs"></i></button>
                                    </div>

                                    <!-- Link & Guest Post Special Tools -->
                                    <div class="flex items-center gap-1 pr-2 border-r border-slate-200">
                                        <button type="button" onclick="openLinkModal()" id="linkBtn" title="Insert / Edit Link (Ctrl+K)" class="h-8 px-2.5 rounded bg-crimson-50 hover:bg-crimson-100 text-crimson-600 font-semibold text-xs flex items-center gap-1.5 transition-colors border border-crimson-200">
                                            <i class="fa-solid fa-link text-xs"></i> Link
                                        </button>
                                        <button type="button" onclick="removeActiveLink()" title="Remove Link" class="w-8 h-8 rounded hover:bg-slate-100 text-slate-500 hover:text-red-500 flex items-center justify-center transition-colors"><i class="fa-solid fa-link-slash text-xs"></i></button>
                                    </div>

                                    <!-- Elements: Table, Code, Divider -->
                                    <div class="flex items-center gap-1 pr-2 border-r border-slate-200">
                                        <button type="button" onclick="insertTable()" title="Insert Comparison Table" class="w-8 h-8 rounded hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors"><i class="fa-solid fa-table text-xs"></i></button>
                                        <button type="button" onclick="insertCodeBlock()" title="Inline Code / Snippet" class="w-8 h-8 rounded hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors"><i class="fa-solid fa-code text-xs"></i></button>
                                        <button type="button" onclick="formatDoc('insertHorizontalRule')" title="Horizontal Divider" class="w-8 h-8 rounded hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors"><i class="fa-solid fa-minus text-xs"></i></button>
                                    </div>

                                    <!-- Clean & HTML Toggle -->
                                    <div class="flex items-center gap-1 ml-auto">
                                        <button type="button" onclick="cleanFormatting()" title="Clear Dirty Formatting (from Word/Docs)" class="w-8 h-8 rounded hover:bg-slate-100 text-slate-500 flex items-center justify-center transition-colors"><i class="fa-solid fa-eraser text-xs"></i></button>
                                        <button type="button" onclick="toggleHtmlMode()" id="htmlModeBtn" title="Toggle Raw HTML / Visual Editor" class="h-8 px-2.5 rounded hover:bg-slate-100 text-slate-600 font-mono text-xs flex items-center gap-1 transition-colors border border-slate-200">
                                            <i class="fa-solid fa-file-code text-xs"></i> <span id="htmlModeText">HTML</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Content Area -->
                                <div class="p-6 flex-1 min-h-[420px] relative">
                                    <div id="editor" class="w-full min-h-[380px] text-slate-800 outline-none editor-content text-base leading-relaxed font-serif" contenteditable="true" data-placeholder="Start writing or paste your article content here..."></div>
                                    <textarea id="rawHtmlEditor" class="w-full min-h-[380px] p-4 font-mono text-xs text-slate-100 bg-slate-900 rounded-lg outline-none resize-y hidden leading-relaxed" placeholder="Paste or edit raw HTML article content here..."></textarea>
                                </div>

                                <!-- Bottom Status Bar -->
                                <div class="p-3 border-t border-slate-100 bg-slate-50 flex flex-wrap justify-between items-center text-[11px] text-slate-400 gap-2">
                                    <div class="flex items-center gap-4">
                                        <span>Words: <span id="wordCount" class="font-bold text-slate-600">0</span></span>
                                        <span>Links: <span id="linkCount" class="font-bold text-crimson-600">0</span></span>
                                        <span>Headings: <span id="headingCount" class="font-bold text-slate-600">0</span></span>
                                        <span id="seoWarning" class="transition-all opacity-0"></span>
                                    </div>
                                    <div class="flex gap-4">
                                        <span>Reading Time: <span id="readingTime" class="font-medium text-slate-600">0</span> min</span>
                                        <span>Status: <span id="editorStatus" class="font-medium text-slate-600">Ready</span></span>
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
                                        <div>
                                            <label for="isFeatured" class="text-sm text-slate-700 font-medium cursor-pointer">Featured Post</label>
                                            <p class="text-[10px] text-slate-400">Highlight this post in homepage hero section</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="is_featured" id="isFeatured" value="1" class="sr-only peer">
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
    <!-- Link Modal for Guest Posting / Articles -->
    <div id="linkModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full border border-slate-200 overflow-hidden max-h-[92vh] flex flex-col">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/70 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-red-50 text-[#C5202B] flex items-center justify-center">
                        <i class="fa-solid fa-link text-sm"></i>
                    </div>
                    <div>
                        <h3 id="linkModalTitle" class="font-bold text-slate-900 text-sm">Insert Link</h3>
                        <p class="text-[11px] text-slate-500">Configure anchor text &amp; guest post link attributes</p>
                    </div>
                </div>
                <button type="button" onclick="closeLinkModal()" class="w-7 h-7 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="linkModalForm" onsubmit="applyLinkModal(event)" class="p-5 space-y-4 overflow-y-auto flex-1">
                <!-- Target URL -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Destination URL <span class="text-red-500">*</span></label>
                    <input type="text" id="linkUrlInput" required placeholder="https://example.com/target-page" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2 text-xs text-slate-800 outline-none focus:border-red-500 focus:bg-white transition-all font-mono">
                    <p class="text-[10px] text-slate-400 mt-1">Accepts full URLs (https://...), relative links (/about), or anchor targets (#section)</p>
                </div>

                <!-- Anchor / Link Text -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Anchor Text (Display Text)</label>
                    <input type="text" id="linkTextInput" placeholder="Click here or target keyword" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2 text-xs text-slate-800 outline-none focus:border-red-500 focus:bg-white transition-all">
                </div>

                <!-- SEO / Rel Type (Essential for Guest Posting) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">
                        Link Type &amp; Rel Attribute 
                        <span class="text-[10px] font-normal text-slate-400">(Guest Posting / SEO)</span>
                    </label>
                    <select id="linkRelInput" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-700 outline-none focus:border-red-500 focus:bg-white font-medium">
                        <option value="dofollow">Standard / Dofollow (Editorial link)</option>
                        <option value="nofollow">rel="nofollow" (Standard Nofollow)</option>
                        <option value="sponsored">rel="sponsored" (Paid Guest Post / Sponsored)</option>
                        <option value="ugc">rel="ugc" (User-generated content)</option>
                    </select>
                    <p class="text-[10px] text-slate-400 mt-1">Choose <strong>sponsored</strong> for paid client guest posts per Google guidelines</p>
                </div>

                <!-- Open in new tab -->
                <div class="flex items-center justify-between py-1 bg-slate-50/70 px-3 rounded-lg border border-slate-100">
                    <span class="text-xs text-slate-700 font-medium">Open link in new tab</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="linkTargetBlank" checked class="sr-only peer">
                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C5202B]"></div>
                    </label>
                </div>

                <!-- Actions (Always visible at bottom) -->
                <div class="pt-3 flex items-center justify-between gap-2 border-t border-slate-100 mt-3">
                    <button type="button" id="linkModalUnlinkBtn" onclick="removeModalLink()" class="text-red-600 hover:text-red-800 text-xs font-semibold px-2.5 py-2 rounded-lg hover:bg-red-50 transition-colors hidden items-center gap-1 cursor-pointer">
                        <i class="fa-solid fa-link-slash"></i> Remove Link
                    </button>
                    <div class="flex items-center gap-2.5 ml-auto">
                        <button type="button" onclick="closeLinkModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors border border-slate-200 cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" id="linkModalSaveBtn" style="background-color: #C5202B !important; color: #ffffff !important; display: inline-flex !important; visibility: visible !important;" class="px-4 py-2 text-xs font-bold text-white bg-[#C5202B] hover:bg-[#a31a23] rounded-lg transition-colors shadow-sm flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-check"></i> Save Link
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ── Double-submission prevention ──────────────────────────────────────
        let _isSubmitting = false;
        const _TOKEN_KEY  = 'bk_submit_token_create';

        function _generateToken() {
            return Date.now().toString(36) + Math.random().toString(36).slice(2, 8);
        }

        // Stamp page-load time and a unique token when page loads
        (function initSubmitToken() {
            const tok = _generateToken();
            const loadedAt = Date.now();
            const tokenEl = document.getElementById('submitToken');
            const loadedEl = document.getElementById('formLoadedAt');
            if (tokenEl) tokenEl.value = tok;
            if (loadedEl) loadedEl.value = loadedAt;
            // Store the token so the server can check it was not reused
            try { sessionStorage.setItem(_TOKEN_KEY, tok); } catch(e) {}
        })();

        // ── Form submission helper (Syncs Visual & HTML modes) ──────────────────
        function submitPost() {
            // Prevent double-submission
            if (_isSubmitting) {
                return;
            }

            const editor      = document.getElementById('editor');
            const rawHtml     = document.getElementById('rawHtmlEditor');
            const contentInput = document.getElementById('postContent');

            // Always sync the active editor to the hidden input
            if (isHtmlMode) {
                editor.innerHTML = rawHtml.value;
            }

            const finalContent = editor.innerHTML.trim();
            if (!finalContent || finalContent === '<br>' || finalContent === '<p><br></p>') {
                alert('Please add content before saving the post.');
                return;
            }

            contentInput.value = editor.innerHTML;

            // Disable all submit buttons to prevent double-click
            _isSubmitting = true;
            document.querySelectorAll('[onclick="submitPost()"]').forEach(function(btn) {
                btn.disabled = true;
                const icon = btn.querySelector('i');
                if (icon) icon.className = 'fa-solid fa-spinner fa-spin';
                const textNodes = Array.from(btn.childNodes).filter(n => n.nodeType === 3);
                textNodes.forEach(n => { n.textContent = ' Saving...'; });
            });

            // Clear draft since we're officially submitting
            try { localStorage.removeItem(DRAFT_KEY); } catch(e) {}

            document.getElementById('postForm').submit();
        }

        // ── Keyboard shortcuts ────────────────────────────────────────────────
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                submitPost();
            }
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                openLinkModal();
            }
        });

        // ── Slugify helper ───────────────────────────────────────────────────
        function slugify(text) {
            return text.toString().toLowerCase().trim()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/[\s-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        const postTitle       = document.getElementById('postTitle');
        const postSlug        = document.getElementById('postSlug');
        const slugPreview     = document.getElementById('slugPreview');
        const editSlugBtn     = document.getElementById('editSlugBtn');
        const saveSlugBtn     = document.getElementById('saveSlugBtn');
        const resetSlugBtn    = document.getElementById('resetSlugBtn');
        let slugIsCustomized  = false;

        function updateSlugDisplay() {
            if (!postSlug || !slugPreview) return;
            const currentSlug = postSlug.value.trim();
            if (currentSlug) {
                slugPreview.textContent = currentSlug;
                slugPreview.classList.remove('text-slate-400', 'italic');
                slugPreview.classList.add('text-crimson-600', 'font-semibold');
            } else {
                slugPreview.textContent = '(auto-generated from title)';
                slugPreview.classList.add('text-slate-400', 'italic');
                slugPreview.classList.remove('text-crimson-600');
            }
        }

        if (postTitle) {
            postTitle.addEventListener('input', function() {
                if (!slugIsCustomized && postSlug) {
                    postSlug.value = slugify(postTitle.value);
                    updateSlugDisplay();
                }
            });
        }

        function toggleEditSlug() {
            if (!postSlug) return;
            slugPreview.classList.add('hidden');
            postSlug.classList.remove('hidden');
            editSlugBtn.classList.add('hidden');
            saveSlugBtn.classList.remove('hidden');
            resetSlugBtn.classList.remove('hidden');
            postSlug.focus();
            postSlug.select();
        }

        function saveCustomSlug() {
            if (!postSlug) return;
            let clean = slugify(postSlug.value);
            if (!clean && postTitle) {
                clean = slugify(postTitle.value);
            }
            postSlug.value = clean;
            slugIsCustomized = clean.length > 0;
            updateSlugDisplay();

            postSlug.classList.add('hidden');
            slugPreview.classList.remove('hidden');
            saveSlugBtn.classList.add('hidden');
            editSlugBtn.classList.remove('hidden');
            if (slugIsCustomized) {
                resetSlugBtn.classList.remove('hidden');
            } else {
                resetSlugBtn.classList.add('hidden');
            }
        }

        function resetSlugToTitle() {
            slugIsCustomized = false;
            if (postTitle && postSlug) {
                postSlug.value = slugify(postTitle.value);
                updateSlugDisplay();
            }
            postSlug.classList.add('hidden');
            slugPreview.classList.remove('hidden');
            saveSlugBtn.classList.add('hidden');
            editSlugBtn.classList.remove('hidden');
            resetSlugBtn.classList.add('hidden');
        }

        if (postSlug) {
            postSlug.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    saveCustomSlug();
                } else if (e.key === 'Escape') {
                    e.preventDefault();
                    postSlug.classList.add('hidden');
                    slugPreview.classList.remove('hidden');
                    saveSlugBtn.classList.add('hidden');
                    editSlugBtn.classList.remove('hidden');
                    if (!slugIsCustomized) resetSlugBtn.classList.add('hidden');
                }
            });
        }

        // ── Tag System ───────────────────────────────────────────────────────
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

        // ── Image Preview ────────────────────────────────────────────────────
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

        // ── Editor Formatting Helpers ─────────────────────────────────────────
        const editor = document.getElementById('editor');
        const rawHtmlEditor = document.getElementById('rawHtmlEditor');
        const editorToolbar = document.getElementById('editorToolbar');
        let isHtmlMode = false;
        let savedRange = null;
        let activeLinkNode = null;
        let selectedTextOnModalOpen = '';

        function saveSelection() {
            const sel = window.getSelection();
            if (sel && sel.rangeCount > 0) {
                const range = sel.getRangeAt(0);
                if (editor.contains(range.commonAncestorContainer) || editor === range.commonAncestorContainer) {
                    savedRange = range.cloneRange();
                }
            }
        }

        function restoreSelection() {
            if (savedRange) {
                const sel = window.getSelection();
                sel.removeAllRanges();
                sel.addRange(savedRange);
            }
        }

        editor.addEventListener('keyup', saveSelection);
        editor.addEventListener('mouseup', saveSelection);
        editor.addEventListener('touchend', saveSelection);
        document.addEventListener('selectionchange', function() {
            const sel = window.getSelection();
            if (sel && sel.rangeCount > 0 && (editor.contains(sel.anchorNode) || editor === sel.anchorNode)) {
                savedRange = sel.getRangeAt(0).cloneRange();
            }
        });

        // Prevent toolbar buttons from stealing focus & collapsing selection
        if (editorToolbar) {
            editorToolbar.addEventListener('mousedown', function(e) {
                if (e.target.closest('button, select')) {
                    saveSelection();
                    if (e.target.closest('button')) {
                        e.preventDefault();
                    }
                }
            });
        }

        // Clean & sanitize pasted content (e.g. from ChatGPT or Google Docs)
        // ALSO: detect if author pastes raw HTML source code into the visual editor
        editor.addEventListener('paste', function(e) {
            const plainText = e.clipboardData ? e.clipboardData.getData('text/plain') : '';
            const html      = e.clipboardData ? e.clipboardData.getData('text/html')  : '';

            // ── Smart HTML-source detection ────────────────────────────────────
            // If the plain text clipboard looks like raw HTML (starts with < and has tags)
            // but the author is in visual mode, auto-switch to HTML mode and paste there.
            const looksLikeHtmlSource = /^\s*<(!DOCTYPE|html|head|body|p|h[1-6]|div|ul|ol|li|table|section|article|blockquote|pre|code|strong|em|br|hr|a\s|img\s)/i.test(plainText.trim())
                && (plainText.match(/<\/?(p|h[1-6]|ul|ol|li|div|strong|em|blockquote|pre|code)>/gi) || []).length > 2;

            if (looksLikeHtmlSource && !isHtmlMode) {
                e.preventDefault();
                // Show an informative toast
                showEditorToast(
                    '🔍 Raw HTML detected — switched to HTML mode automatically. Review and click Visual to preview.',
                    'amber'
                );
                // Switch to HTML mode and paste there
                isHtmlMode = true;
                const rawHtmlEditor = document.getElementById('rawHtmlEditor');
                const htmlModeBtn   = document.getElementById('htmlModeBtn');
                const htmlModeText  = document.getElementById('htmlModeText');
                const toolbarButtons = document.querySelectorAll('#editorToolbar button:not(#htmlModeBtn), #headingSelect');

                rawHtmlEditor.value = (rawHtmlEditor.value || '') + plainText;
                editor.classList.add('hidden');
                rawHtmlEditor.classList.remove('hidden');
                if (htmlModeBtn) {
                    htmlModeBtn.classList.add('bg-brand-blue', 'text-white');
                    htmlModeBtn.classList.remove('text-slate-600', 'hover:bg-slate-100');
                }
                if (htmlModeText) htmlModeText.innerText = 'Visual';
                toolbarButtons.forEach(btn => { btn.disabled = true; btn.classList.add('opacity-40', 'pointer-events-none'); });
                rawHtmlEditor.focus();
                updateWordCount();
                saveDraft();
                return;
            }

            // ── Standard rich-text paste (from Google Docs, Word, etc.) ──────────
            if (html) {
                e.preventDefault();
                try {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Strip disruptive styles from ChatGPT (dark backgrounds, fixed fonts, etc.)
                    const all = doc.body.querySelectorAll('*');
                    all.forEach(el => {
                        el.style.backgroundColor = '';
                        el.style.color = '';
                        el.style.fontFamily = '';
                        el.removeAttribute('face');
                        el.removeAttribute('size');
                        if (el.tagName === 'BUTTON' || el.tagName === 'SVG') {
                            el.remove();
                        }
                    });

                    document.execCommand('insertHTML', false, doc.body.innerHTML);
                } catch(err) {
                    // Fallback to standard paste
                    const text = e.clipboardData.getData('text/plain');
                    document.execCommand('insertText', false, text);
                }
                updateWordCount();
                saveDraft();
            }
        });

        // ── Editor Toast Notification ────────────────────────────────────────
        function showEditorToast(message, type) {
            const existing = document.getElementById('editorToast');
            if (existing) existing.remove();

            const colors = {
                amber: 'bg-amber-50 border-amber-400 text-amber-900',
                green: 'bg-emerald-50 border-emerald-400 text-emerald-900',
                red:   'bg-red-50 border-red-400 text-red-900'
            };
            const colorClass = colors[type] || colors.amber;

            const toast = document.createElement('div');
            toast.id = 'editorToast';
            toast.className = `fixed bottom-6 left-1/2 -translate-x-1/2 z-[1000] flex items-start gap-3 px-5 py-3.5 rounded-xl shadow-xl border text-sm font-medium max-w-lg ${colorClass}`;
            toast.innerHTML = `<span>${message}</span><button onclick="this.parentElement.remove()" class="shrink-0 ml-2 opacity-60 hover:opacity-100 text-lg leading-none">&times;</button>`;
            document.body.appendChild(toast);

            setTimeout(function() { if (toast.parentElement) toast.remove(); }, 7000);
        }

        function formatDoc(cmd, value = null) {
            if (isHtmlMode) return;
            editor.focus();
            document.execCommand(cmd, false, value);
            updateWordCount();
            saveDraft();
        }

        function formatHeading(tag) {
            if (isHtmlMode) return;
            editor.focus();
            document.execCommand('formatBlock', false, '<' + tag + '>');
            updateWordCount();
            saveDraft();
        }

        function insertBlockquote() {
            if (isHtmlMode) return;
            editor.focus();
            document.execCommand('formatBlock', false, '<blockquote>');
            updateWordCount();
            saveDraft();
        }

        function insertTable() {
            if (isHtmlMode) return;
            editor.focus();
            const tableHtml = `
                <table class="w-full border-collapse my-4">
                    <thead>
                        <tr>
                            <th>Item / Feature</th>
                            <th>Description</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Specification 1</td>
                            <td>Feature detail description</td>
                            <td>Verified</td>
                        </tr>
                        <tr>
                            <td>Specification 2</td>
                            <td>Feature detail description</td>
                            <td>Verified</td>
                        </tr>
                    </tbody>
                </table>
                <p><br></p>
            `;
            document.execCommand('insertHTML', false, tableHtml);
            updateWordCount();
            saveDraft();
        }

        function insertCodeBlock() {
            if (isHtmlMode) return;
            editor.focus();
            const sel = window.getSelection();
            const selectedText = sel ? sel.toString() : '';
            if (selectedText) {
                document.execCommand('insertHTML', false, `<code>${selectedText}</code>`);
            } else {
                document.execCommand('insertHTML', false, `<code>code_sample</code>&nbsp;`);
            }
            updateWordCount();
            saveDraft();
        }

        function cleanFormatting() {
            if (isHtmlMode) return;
            editor.focus();
            document.execCommand('removeFormat', false, null);
            const els = editor.querySelectorAll('*');
            els.forEach(el => {
                el.removeAttribute('style');
                el.removeAttribute('face');
                el.removeAttribute('size');
            });
            updateWordCount();
            saveDraft();
        }

        // ── Link Insertion / Editing Logic (Guest Posting Core) ───────────────
        const linkModal          = document.getElementById('linkModal');
        const linkModalTitle     = document.getElementById('linkModalTitle');
        const linkUrlInput       = document.getElementById('linkUrlInput');
        const linkTextInput      = document.getElementById('linkTextInput');
        const linkRelInput       = document.getElementById('linkRelInput');
        const linkTargetBlank    = document.getElementById('linkTargetBlank');
        const linkModalUnlinkBtn = document.getElementById('linkModalUnlinkBtn');

        function findEnclosingLink() {
            let node = null;
            if (savedRange) {
                node = savedRange.commonAncestorContainer;
            }
            if (!node || node === editor) {
                const sel = window.getSelection();
                if (sel && sel.rangeCount > 0) {
                    node = sel.anchorNode;
                }
            }
            while (node && node !== editor) {
                if (node.nodeType === 1 && node.tagName.toLowerCase() === 'a') {
                    return node;
                }
                node = node.parentNode;
            }
            return null;
        }

        function openLinkModal() {
            if (isHtmlMode) return;

            // Prioritize active selection in editor
            const sel = window.getSelection();
            if (sel && sel.rangeCount > 0) {
                const r = sel.getRangeAt(0);
                if (editor.contains(r.commonAncestorContainer) || editor === r.commonAncestorContainer) {
                    savedRange = r.cloneRange();
                }
            }

            if (!activeLinkNode) {
                activeLinkNode = findEnclosingLink();
            }

            if (activeLinkNode) {
                linkModalTitle.innerText = 'Edit Link';
                linkUrlInput.value = activeLinkNode.getAttribute('href') || '';
                linkTextInput.value = activeLinkNode.textContent || '';
                selectedTextOnModalOpen = linkTextInput.value;
                linkTargetBlank.checked = activeLinkNode.target === '_blank';

                const rel = (activeLinkNode.getAttribute('rel') || '').toLowerCase();
                if (rel.includes('sponsored')) {
                    linkRelInput.value = 'sponsored';
                } else if (rel.includes('nofollow')) {
                    linkRelInput.value = 'nofollow';
                } else if (rel.includes('ugc')) {
                    linkRelInput.value = 'ugc';
                } else {
                    linkRelInput.value = 'dofollow';
                }
                linkModalUnlinkBtn.classList.remove('hidden');
                linkModalUnlinkBtn.classList.add('flex');
            } else {
                linkModalTitle.innerText = 'Insert Link';
                linkUrlInput.value = '';
                
                let selectedText = '';
                if (savedRange && !savedRange.collapsed) {
                    selectedText = savedRange.toString().trim();
                }
                selectedTextOnModalOpen = selectedText;
                linkTextInput.value = selectedText;
                linkTargetBlank.checked = true;
                linkRelInput.value = 'dofollow';
                linkModalUnlinkBtn.classList.add('hidden');
                linkModalUnlinkBtn.classList.remove('flex');
            }

            linkModal.classList.remove('hidden');
            linkModal.style.display = 'flex';
            setTimeout(() => {
                linkUrlInput.focus();
                linkUrlInput.select();
            }, 60);
        }

        function closeLinkModal() {
            linkModal.classList.add('hidden');
            linkModal.style.display = 'none';
            activeLinkNode = null;
        }

        function applyLinkModal(e) {
            e.preventDefault();
            let url = linkUrlInput.value.trim();
            if (!url) return;

            // Auto-prepend https:// if protocol is missing and not relative/anchor
            if (!url.match(/^https?:\/\//i) && !url.match(/^mailto:/i) && !url.match(/^tel:/i) && !url.startsWith('/') && !url.startsWith('#')) {
                url = 'https://' + url;
            }

            const text = linkTextInput.value.trim();
            const isBlank = linkTargetBlank.checked;
            const relType = linkRelInput.value;

            let relAttr = '';
            if (relType === 'sponsored') {
                relAttr = isBlank ? 'sponsored nofollow noopener' : 'sponsored nofollow';
            } else if (relType === 'nofollow') {
                relAttr = isBlank ? 'nofollow noopener' : 'nofollow';
            } else if (relType === 'ugc') {
                relAttr = isBlank ? 'ugc noopener' : 'ugc';
            } else {
                relAttr = isBlank ? 'noopener' : '';
            }

            if (activeLinkNode) {
                // Editing existing link
                activeLinkNode.setAttribute('href', url);
                if (text) activeLinkNode.textContent = text;
                if (isBlank) {
                    activeLinkNode.setAttribute('target', '_blank');
                } else {
                    activeLinkNode.removeAttribute('target');
                }
                if (relAttr) {
                    activeLinkNode.setAttribute('rel', relAttr);
                } else {
                    activeLinkNode.removeAttribute('rel');
                }
            } else {
                // Inserting new link on selected word/sentence or at caret
                if (savedRange && !savedRange.collapsed) {
                    const a = document.createElement('a');
                    a.href = url;
                    if (isBlank) a.setAttribute('target', '_blank');
                    if (relAttr) a.setAttribute('rel', relAttr);

                    if (text && text !== selectedTextOnModalOpen) {
                        a.textContent = text;
                        savedRange.deleteContents();
                        savedRange.insertNode(a);
                    } else {
                        try {
                            savedRange.surroundContents(a);
                        } catch (err) {
                            const fragment = savedRange.extractContents();
                            a.appendChild(fragment);
                            savedRange.insertNode(a);
                        }
                    }

                    // Move caret right after inserted link
                    const sel = window.getSelection();
                    if (sel) {
                        const newRange = document.createRange();
                        newRange.setStartAfter(a);
                        newRange.collapse(true);
                        sel.removeAllRanges();
                        sel.addRange(newRange);
                        savedRange = newRange.cloneRange();
                    }
                } else {
                    // Caret is collapsed (no text selected) -> insert new link
                    const a = document.createElement('a');
                    a.href = url;
                    a.textContent = text || url;
                    if (isBlank) a.setAttribute('target', '_blank');
                    if (relAttr) a.setAttribute('rel', relAttr);

                    if (savedRange && editor.contains(savedRange.commonAncestorContainer)) {
                        savedRange.insertNode(a);
                    } else {
                        editor.appendChild(a);
                    }

                    // Move caret after inserted link
                    const sel = window.getSelection();
                    if (sel) {
                        const newRange = document.createRange();
                        newRange.setStartAfter(a);
                        newRange.collapse(true);
                        sel.removeAllRanges();
                        sel.addRange(newRange);
                        savedRange = newRange.cloneRange();
                    }
                }
            }

            closeLinkModal();
            updateWordCount();
            saveDraft();
            const postContentInput = document.getElementById('postContent');
            if (postContentInput) {
                postContentInput.value = editor.innerHTML;
            }
        }

        function removeModalLink() {
            if (activeLinkNode) {
                const parent = activeLinkNode.parentNode;
                while (activeLinkNode.firstChild) {
                    parent.insertBefore(activeLinkNode.firstChild, activeLinkNode);
                }
                parent.removeChild(activeLinkNode);
            }
            closeLinkModal();
            updateWordCount();
            saveDraft();
            const postContentInput = document.getElementById('postContent');
            if (postContentInput) {
                postContentInput.value = editor.innerHTML;
            }
        }

        function removeActiveLink() {
            if (isHtmlMode) return;
            const link = findEnclosingLink();
            if (link) {
                const parent = link.parentNode;
                while (link.firstChild) {
                    parent.insertBefore(link.firstChild, link);
                }
                parent.removeChild(link);
            } else {
                document.execCommand('unlink', false, null);
            }
            updateWordCount();
            saveDraft();
            const postContentInput = document.getElementById('postContent');
            if (postContentInput) {
                postContentInput.value = editor.innerHTML;
            }
        }

        // Prevent accidental link navigation inside editor & open editor on click
        editor.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link) {
                e.preventDefault();
                activeLinkNode = link;
                openLinkModal();
            }
        });

        // ── Toggle Raw HTML Mode ─────────────────────────────────────────────
        function toggleHtmlMode() {
            isHtmlMode = !isHtmlMode;
            const htmlModeBtn = document.getElementById('htmlModeBtn');
            const htmlModeText = document.getElementById('htmlModeText');
            const toolbarButtons = document.querySelectorAll('#editorToolbar button:not(#htmlModeBtn), #headingSelect');

            if (isHtmlMode) {
                rawHtmlEditor.value = editor.innerHTML;
                editor.classList.add('hidden');
                rawHtmlEditor.classList.remove('hidden');
                htmlModeBtn.classList.add('bg-brand-blue', 'text-white');
                htmlModeBtn.classList.remove('text-slate-600', 'hover:bg-slate-100');
                htmlModeText.innerText = 'Visual';
                toolbarButtons.forEach(btn => { btn.disabled = true; btn.classList.add('opacity-40', 'pointer-events-none'); });
                rawHtmlEditor.focus();
            } else {
                editor.innerHTML = rawHtmlEditor.value;
                rawHtmlEditor.classList.add('hidden');
                editor.classList.remove('hidden');
                htmlModeBtn.classList.remove('bg-brand-blue', 'text-white');
                htmlModeBtn.classList.add('text-slate-600', 'hover:bg-slate-100');
                htmlModeText.innerText = 'HTML';
                toolbarButtons.forEach(btn => { btn.disabled = false; btn.classList.remove('opacity-40', 'pointer-events-none'); });
                editor.focus();
                updateWordCount();
            }
        }

        rawHtmlEditor.addEventListener('input', function() {
            updateWordCount();
            saveDraft();
        });

        // ── Word Count, Links, Headings & Real-time Guidance ───────────────────
        const wordCountDisplay   = document.getElementById('wordCount');
        const linkCountDisplay   = document.getElementById('linkCount');
        const headingCountDisplay= document.getElementById('headingCount');
        const readingTimeDisplay = document.getElementById('readingTime');
        const seoWarning         = document.getElementById('seoWarning');

        function updateWordCount() {
            const text  = isHtmlMode ? rawHtmlEditor.value.replace(/<[^>]*>/g, ' ') : (editor.innerText || editor.textContent || '');
            const words = text.trim() === '' ? 0 : text.trim().split(/\s+/).length;
            if (wordCountDisplay) wordCountDisplay.innerText = words;
            if (readingTimeDisplay) readingTimeDisplay.innerText = Math.max(1, Math.ceil(words / 200));

            // Count Links & Headings inside content
            const currentHtml = isHtmlMode ? rawHtmlEditor.value : editor.innerHTML;
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = currentHtml;
            const links = tempDiv.querySelectorAll('a').length;
            const headings = tempDiv.querySelectorAll('h1, h2, h3, h4, h5, h6').length;

            if (linkCountDisplay) linkCountDisplay.innerText = links;
            if (headingCountDisplay) headingCountDisplay.innerText = headings;

            if (words === 0) {
                seoWarning.style.opacity = '0';
            } else if (words < 300) {
                seoWarning.innerText   = '⚠ Too short for SEO (min 300 words)';
                seoWarning.className   = 'text-amber-500 font-medium transition-all';
                seoWarning.style.opacity = '1';
            } else if (words < 800) {
                seoWarning.innerText   = '✓ Good — 800+ words recommended for guest posts';
                seoWarning.className   = 'text-blue-500 font-medium transition-all';
                seoWarning.style.opacity = '1';
            } else {
                seoWarning.innerText   = '✓ Excellent in-depth article length!';
                seoWarning.className   = 'text-emerald-500 font-medium transition-all';
                seoWarning.style.opacity = '1';
            }
        }

        editor.addEventListener('input', updateWordCount);
        updateWordCount();

        // ── localStorage Auto-save & WordPress-style Draft Recovery ──────────
        const DRAFT_KEY = 'bk_draft_create';
        let activeDraftData = null;

        function saveDraft() {
            const titleVal = postTitle ? postTitle.value.trim() : '';
            const contentVal = isHtmlMode ? rawHtmlEditor.value.trim() : (editor.innerText ? editor.innerText.trim() : '');
            if (!titleVal && !contentVal) {
                return;
            }

            const content = isHtmlMode ? rawHtmlEditor.value : editor.innerHTML;
            const data = {
                title:      postTitle ? postTitle.value : '',
                slug:       postSlug  ? postSlug.value  : '',
                slugCustom: slugIsCustomized,
                content:    content,
                tags:       tags,
                ts:         Date.now()
            };
            try { localStorage.setItem(DRAFT_KEY, JSON.stringify(data)); } catch(e) {}
        }

        function checkForDraft() {
            try {
                const saved = localStorage.getItem(DRAFT_KEY);
                if (!saved) return;
                const data = JSON.parse(saved);
                if ((Date.now() - data.ts) > 86400000) {
                    localStorage.removeItem(DRAFT_KEY);
                    return;
                }
                const hasTitle = data.title && data.title.trim().length > 0;
                const hasContent = data.content && data.content.trim().length > 15;
                if (!hasTitle && !hasContent) return;

                activeDraftData = data;
                const banner = document.getElementById('draftRestoreBanner');
                const timeEl = document.getElementById('draftTime');
                if (banner && timeEl) {
                    const minutesAgo = Math.round((Date.now() - data.ts) / 60000);
                    let timeStr = 'just now';
                    if (minutesAgo >= 60) {
                        timeStr = Math.round(minutesAgo / 60) + ' hour(s) ago';
                    } else if (minutesAgo > 1) {
                        timeStr = minutesAgo + ' minutes ago';
                    }
                    timeEl.textContent = timeStr;
                    banner.classList.remove('hidden');
                }
            } catch(e) {}
        }

        function confirmRestoreDraft() {
            if (!activeDraftData) return;
            if (postTitle && activeDraftData.title) {
                postTitle.value = activeDraftData.title;
            }
            if (postSlug && activeDraftData.slug) {
                postSlug.value = activeDraftData.slug;
                slugIsCustomized = !!activeDraftData.slugCustom;
                updateSlugDisplay();
                if (slugIsCustomized && resetSlugBtn) {
                    resetSlugBtn.classList.remove('hidden');
                }
            }
            if (activeDraftData.content) {
                if (editor) editor.innerHTML = activeDraftData.content;
                if (rawHtmlEditor) rawHtmlEditor.value = activeDraftData.content;
                updateWordCount();
            }
            if (activeDraftData.tags && Array.isArray(activeDraftData.tags)) {
                tags = activeDraftData.tags;
                updateTags();
            }
            const banner = document.getElementById('draftRestoreBanner');
            if (banner) banner.classList.add('hidden');
        }

        function discardDraft() {
            try { localStorage.removeItem(DRAFT_KEY); } catch(e) {}
            activeDraftData = null;
            const banner = document.getElementById('draftRestoreBanner');
            if (banner) banner.classList.add('hidden');
        }

        // Check if an autosaved draft exists (DO NOT auto-fill inputs!)
        checkForDraft();
        setInterval(saveDraft, 15000);
        editor.addEventListener('input', saveDraft);

        document.getElementById('postForm').addEventListener('submit', function(e) {
            // Final safety: sync content and prevent double-submit via form submit event
            if (_isSubmitting) {
                e.preventDefault();
                return;
            }
            if (isHtmlMode) editor.innerHTML = rawHtmlEditor.value;
            document.getElementById('postContent').value = editor.innerHTML;
            try { localStorage.removeItem(DRAFT_KEY); } catch(e) {}
        });

        // ── Validation / Draft Fallback ──────────────────────────────────────
        const postForm       = document.getElementById('postForm');
        const postStatus     = document.getElementById('postStatus');
        const statusNote     = document.getElementById('statusNote');
        const thumbInput     = document.getElementById('thumbInput');
        const categorySelect = document.querySelector('select[name="category_id"]');

        function checkMandatoryFields() {
            const hasTitle    = postTitle && postTitle.value.trim() !== '';
            const hasCategory = categorySelect && categorySelect.value !== '';
            const hasContent  = (isHtmlMode ? rawHtmlEditor.value.trim() : editor.innerText.trim()) !== '';
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
