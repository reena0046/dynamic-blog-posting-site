@extends('layouts.admin')

@section('title')
    {{ Route::is('admin.blogs.create') ? 'Create Blog' : 'Edit Blog' }}
@endsection

@section('content')
    <div class="blog-page">
        <div class="cms-page-header blog-page-header">
            <div>
                <h1>{{ Route::is('admin.blogs.create') ? 'Create Blog' : 'Edit Blog' }}</h1>
                <p>{{ Route::is('admin.blogs.create') ? 'Create and publish a new blog post.' : 'Update your blog details.' }}
                </p>
            </div>
            <a href="{{ route('admin.blogs.index') }}" class="cms-back-btn">
                <i class="ti ti-arrow-left"></i>
                Back
            </a>
        </div>

        <form method="POST"
            action="{{ Route::is('admin.blogs.create')
                ? route('admin.blogs.store')
                : route('admin.blogs.update', ['blog' => $blog->id]) }}"
            enctype="multipart/form-data" autocomplete="off" id="blogs-form" class="blog-form-container">

            @csrf

            {{ Route::is('admin.blogs.create') ? '' : method_field('PUT') }}

            <div class="cms-card blog-form-card">
                <div class="cms-card-head">
                    <span class="cms-card-icon"><i class="ti ti-file-text"></i></span>
                    <div>
                        <h2>Basic Information</h2>
                        <p>Add the main details for your blog post.</p>
                    </div>
                </div>
                <div class="cms-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="cms-field">
                                <div class="cms-field-head">
                                    <label class="cms-label" for="title">Title <span class="cms-req">*</span></label>
                                </div>
                                <input type="text" class="form-control" placeholder="Enter title" id="title"
                                    name="title" value="{{ isset($blog) ? $blog->title : '' }}">
                                <div id="title-error" class="text-danger small mt-1"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cms-field">
                                <div class="cms-field-head cms-slug-head">
                                    <label class="cms-label" for="slug">URL Slug <span class="cms-req">*</span></label>
                                    <div class="form-check form-switch cms-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="custom-slug-toggle"
                                            {{ isset($blog) && $blog->slug !== \Illuminate\Support\Str::slug($blog->title) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="custom-slug-toggle">Custom URL</label>
                                    </div>
                                </div>
                                <input type="text" class="form-control" placeholder="Enter slug" id="slug"
                                    name="slug" value="{{ isset($blog) ? $blog->slug : '' }}"
                                    {{ isset($blog) && $blog->slug !== \Illuminate\Support\Str::slug($blog->title) ? '' : 'readonly' }}>
                                <div id="slug-error" class="text-danger small mt-1"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="cms-field">
                                <label class="cms-label" for="description">Description <span class="cms-req">*</span></label>
                                <textarea class="form-control" placeholder="Enter description" id="description" name="description"
                                    style="height: 88px;">{{ isset($blog) ? $blog->description : '' }}</textarea>
                                <div id="description-error" class="text-danger small mt-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cms-card">
                <div class="cms-card-head">
                    <span class="cms-card-icon"><i class="ti ti-article"></i></span>
                    <div>
                        <h2>Blog Content</h2>
                        <p>Write and format your complete blog content.</p>
                    </div>
                </div>
                <div class="cms-card-body">
                    <label class="cms-label" for="content">Content <span class="cms-req">*</span></label>
                    <div class="cms-editor-wrap">
                        <textarea id="content" name="content"></textarea>
                    </div>
                    <div id="content-error" class="text-danger small mt-2"></div>
                </div>
            </div>

            <div class="cms-card blog-form-card blog-media-card">
                <div class="cms-card-head">
                    <span class="cms-card-icon"><i class="ti ti-photo"></i></span>
                    <div>
                        <h2>Media</h2>
                        <p>Upload images for your blog.</p>
                    </div>
                </div>
                <div class="cms-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="cms-label">Thumbnail Image <span class="cms-req">*</span></label>
                            <label class="cms-upload-box" for="thumbnail_image">
                                <input type="file" name="thumbnail_image" id="thumbnail_image" accept="image/*">
                                <span class="cms-upload-icon"><i class="ti ti-upload"></i></span>
                                <span class="cms-upload-title">Click to upload</span>
                                <span class="cms-upload-help">Recommended size: 700 × 430 px</span>
                                <img src="{{ isset($blog) && $blog->thumbnail_image ? asset(Storage::url($blog->thumbnail_image)) : '' }}"
                                    alt=""
                                    class="cms-upload-preview {{ isset($blog) && $blog->thumbnail_image ? 'is-visible' : '' }}"
                                    id="thumbnail-preview">
                            </label>
                            <div id="thumbnail_image-error" class="text-danger small mt-1"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="cms-label">Banner Image <span class="cms-req">*</span></label>
                            <label class="cms-upload-box" for="banner_image">
                                <input type="file" name="banner_image" id="banner_image" accept="image/*">
                                <span class="cms-upload-icon"><i class="ti ti-upload"></i></span>
                                <span class="cms-upload-title">Click to upload</span>
                                <span class="cms-upload-help">Recommended size: 1140 × 420 px</span>
                                <img src="{{ isset($blog) && $blog->banner_image ? asset(Storage::url($blog->banner_image)) : '' }}"
                                    alt=""
                                    class="cms-upload-preview {{ isset($blog) && $blog->banner_image ? 'is-visible' : '' }}"
                                    id="banner-preview">
                            </label>
                            <div id="banner_image-error" class="text-danger small mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cms-card">
                <div class="cms-card-head">
                    <span class="cms-card-icon"><i class="ti ti-search"></i></span>
                    <div>
                        <h2>SEO Settings</h2>
                        <p>Optimize your blog for search engines.</p>
                    </div>
                </div>
                <div class="cms-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="cms-field">
                                <label class="cms-label" for="seo_title">SEO Title</label>
                                <input type="text" class="form-control" placeholder="Enter SEO title" id="seo_title"
                                    name="seo_title" value="{{ isset($blog) ? $blog->seo_title : '' }}">
                                <div id="seo_title-error" class="text-danger small mt-1"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cms-field">
                                <label class="cms-label" for="canonical_url">Canonical URL</label>
                                <input type="text" class="form-control" placeholder="Enter canonical URL"
                                    id="canonical_url" name="canonical_url"
                                    value="{{ isset($blog) ? $blog->canonical_url : '' }}">
                                <div id="canonical_url-error" class="text-danger small mt-1"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="cms-field">
                                <label class="cms-label" for="seo_description">SEO Description</label>
                                <textarea class="form-control" placeholder="Enter SEO description" id="seo_description" name="seo_description"
                                    style="height: 88px;">{{ isset($blog) ? $blog->seo_description : '' }}</textarea>
                                <div id="seo_description-error" class="text-danger small mt-1"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="cms-field">
                                <label class="cms-label" for="tags-entry">Tags</label>
                                <input type="hidden" id="tags" name="tags"
                                    value="{{ isset($blog) ? $blog->tags : '' }}">
                                <div class="cms-tag-box" id="tags-box">
                                    <input type="text" class="cms-tag-entry" id="tags-entry"
                                        placeholder="Enter tags">
                                </div>
                                <div id="tags-error" class="text-danger small mt-1"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="cms-field">
                                <label class="cms-label" for="schema_markup">Schema Markup</label>
                                <textarea class="form-control cms-schema" id="schema_markup" name="schema_markup" placeholder="Enter schema markup">{{ isset($blog) && $blog->schema_markup ? json_encode($blog->schema_markup, JSON_PRETTY_PRINT) : '' }}</textarea>
                                <div id="schema_markup-error" class="text-danger small mt-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cms-form-footer">
                <div class="cms-actions">
                    <a href="{{ route('admin.blogs.index') }}" class="cms-cancel-btn">Cancel</a>
                    <button type="submit" class="cms-save-btn" id="submit-btn">
                        <span class="spinner-span" role="status" aria-hidden="true"></span>
                        <span class="save-btn-text">
                            <i class="ti ti-device-floppy"></i>
                            {{ Route::is('admin.blogs.create') ? 'Save' : 'Update' }}
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
    <script>
        $(function() {
            var initialBlogContent = @json(isset($blog) ? $blog->content : '');

            if (typeof CKEDITOR === 'undefined') {
                $('#content').val(initialBlogContent);
                return;
            }

            function normalizePastedHtml(html) {
                if (!html) {
                    return html;
                }

                // Google Docs wraps normal text in <b style="font-weight:normal">.
                html = html.replace(/<b\b[^>]*style\s*=\s*["'][^"']*font-weight\s*:\s*normal[^"']*["'][^>]*>([\s\S]*?)<\/b>/gi, '$1');

                // Keep semantic headings used by Docs/Sheets paste.
                html = html.replace(/<\s*h1\b/gi, '<h2').replace(/<\/\s*h1\s*>/gi, '</h2>');

                // Preserve links but drop unsafe javascript: hrefs.
                html = html.replace(/\shref\s*=\s*(["'])\s*javascript:[^"']*\1/gi, ' href="#"');

                return html;
            }

            CKEDITOR.replace('content', {
                height: 220,
                resize_dir: 'vertical',
                resize_minHeight: 220,
                versionCheck: false,
                contentsCss: "{{ asset('admin/css/ckeditor-content.css') }}",
                extraPlugins: 'uploadimage,pastefromword',
                removePlugins: 'easyimage,exportpdf',
                forcePasteAsPlainText: false,
                clipboard_defaultContentType: 'html',
                pasteFilter: null,
                pasteFromWordRemoveFontStyles: false,
                pasteFromWordRemoveStyles: false,
                pasteFromWord_inlineImages: true,
                allowedContent: true,
                format_tags: 'p;h2;h3;h4',
                filebrowserImageUploadUrl: "{{ route('admin.blogs.upload-image') }}?_token={{ csrf_token() }}",
                fileTools_requestHeaders: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                toolbar: [{
                        name: 'document',
                        items: ['Source']
                    },
                    {
                        name: 'clipboard',
                        items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', 'Undo', 'Redo']
                    },
                    {
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Underline', 'Strike']
                    },
                    {
                        name: 'paragraph',
                        items: ['NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote']
                    },
                    {
                        name: 'links',
                        items: ['Link', 'Unlink']
                    },
                    {
                        name: 'insert',
                        items: ['Image', 'Table', 'HorizontalRule']
                    },
                    {
                        name: 'styles',
                        items: ['Format', 'FontSize']
                    }
                ]
            });

            CKEDITOR.on('instanceReady', function(evt) {
                if (evt.editor.name !== 'content') {
                    return;
                }

                var editor = evt.editor;

                if (CKEDITOR.lang && CKEDITOR.lang.en) {
                    CKEDITOR.lang.en.source = 'View Code';
                }

                if (initialBlogContent) {
                    editor.setData(initialBlogContent);
                }

                editor.dataProcessor.htmlFilter.addRules({
                    elements: {
                        h1: function(element) {
                            element.name = 'h2';
                        }
                    }
                });

                editor.on('paste', function(pasteEvt) {
                    if (!pasteEvt.data || typeof pasteEvt.data.dataValue !== 'string') {
                        return;
                    }

                    pasteEvt.data.dataValue = normalizePastedHtml(pasteEvt.data.dataValue);
                });
            });

            function generateSlug(text) {
                return text
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }

            $('#title').on('input', function() {
                if (!$('#custom-slug-toggle').is(':checked')) {
                    $('#slug').val(generateSlug($(this).val()));
                }
            });

            $('#custom-slug-toggle').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#slug').prop('readonly', false).focus();
                } else {
                    $('#slug').prop('readonly', true);
                    $('#slug').val(generateSlug($('#title').val()));
                }
            });

            // Tag chips
            var $tagsHidden = $('#tags');
            var $tagsBox = $('#tags-box');
            var $tagsEntry = $('#tags-entry');

            function currentTags() {
                return $tagsHidden.val()
                    .split(',')
                    .map(function(tag) {
                        return $.trim(tag);
                    })
                    .filter(Boolean);
            }

            function syncHidden(tags) {
                $tagsHidden.val(tags.join(', '));
            }

            function renderTags() {
                $tagsBox.find('.cms-tag-chip').remove();

                $.each(currentTags(), function(index, tag) {
                    var $chip = $('<span class="cms-tag-chip"></span>');

                    $('<span></span>').text(tag).appendTo($chip);

                    $('<button type="button" aria-label="Remove tag">&times;</button>')
                        .on('click', function(e) {
                            e.stopPropagation();
                            syncHidden($.grep(currentTags(), function(item) {
                                return item !== tag;
                            }));
                            renderTags();
                        })
                        .appendTo($chip);

                    $chip.insertBefore($tagsEntry);
                });
            }

            function addTagFromInput() {
                var value = $.trim($tagsEntry.val()).replace(/,$/, '');

                if (!value) {
                    $tagsEntry.val('');
                    return;
                }

                var tags = currentTags();
                var exists = tags.some(function(tag) {
                    return tag.toLowerCase() === value.toLowerCase();
                });

                if (!exists) {
                    tags.push(value);
                    syncHidden(tags);
                    renderTags();
                }

                $tagsEntry.val('');
            }

            $tagsEntry.on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    addTagFromInput();
                }

                if (e.key === 'Backspace' && !$tagsEntry.val()) {
                    var tags = currentTags();
                    tags.pop();
                    syncHidden(tags);
                    renderTags();
                }
            });

            $tagsEntry.on('blur', addTagFromInput);

            $tagsBox.on('click', function() {
                $tagsEntry.focus();
            });

            renderTags();

            // Image preview
            function previewUpload($input, previewId) {
                var file = $input.prop('files')[0];
                var $preview = $('#' + previewId);

                if (!file || !$preview.length) {
                    return;
                }

                var reader = new FileReader();

                reader.onload = function(e) {
                    $preview.attr('src', e.target.result).addClass('is-visible');
                };

                reader.readAsDataURL(file);
            }

            $('#thumbnail_image').on('change', function() {
                previewUpload($(this), 'thumbnail-preview');
            });

            $('#banner_image').on('change', function() {
                previewUpload($(this), 'banner-preview');
            });

            $('#blogs-form').submit(function(e) {
                e.preventDefault();

                $.each(CKEDITOR.instances, function(instance) {
                    CKEDITOR.instances[instance].updateElement();
                });

                $('div[id$="-error"]').empty();

                $('#submit-btn').attr('disabled', true)
                $('.spinner-span').addClass('spinner-border spinner-border-sm')

                var form = $(this);
                var url = form.attr('action');
                $.ajax({
                    type: "POST",
                    url: url,
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.status == 'success') {
                            toastr.success(data.message, '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                            setTimeout(function() {
                                window.location.href = "{!! route('admin.blogs.index') !!}";
                            }, 100);
                        } else {
                            $('#submit-btn').attr('disabled', false);
                            $('.spinner-span').removeClass('spinner-border spinner-border-sm')

                            toastr.error('There is some error!!', '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        $('#submit-btn').attr('disabled', false);
                        $('.spinner-span').removeClass('spinner-border spinner-border-sm')

                        toastr.error('There are some errors in Form. Please check your inputs',
                            '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('#' + key + '-error').html(value);
                        });
                        $('html, body').animate({
                            scrollTop: $('#' + Object.keys(xhr.responseJSON.errors)[0] +
                                    '-error')
                                .offset().top - 200
                        }, 500);
                    }
                });
            });
        });
    </script>
@endpush
