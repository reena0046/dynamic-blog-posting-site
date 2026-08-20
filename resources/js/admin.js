import tinymce from 'tinymce/tinymce';
import 'tinymce/icons/default';
import 'tinymce/themes/silver';
import 'tinymce/models/dom';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/link';
import 'tinymce/plugins/image';
import 'tinymce/plugins/table';
import 'tinymce/plugins/code';
import 'tinymce/plugins/wordcount';
import 'tinymce/plugins/fullscreen';
import 'tinymce/skins/ui/oxide/skin.js';

window.initAdminEditor = function () {
    const editor = document.querySelector('#blogContent');
    if (!editor) {
        return;
    }

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const uploadUrl = editor.dataset.uploadUrl;

    tinymce.init({
        selector: '#blogContent',
        license_key: 'gpl',
        promotion: false,
        height: 500,
        menubar: true,
        plugins: 'lists link image table code wordcount fullscreen',
        toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image table | code fullscreen',
        block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4',
        skin: false,
        content_css: false,
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,
        images_upload_handler: (blobInfo) => new Promise((resolve, reject) => {
            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            fetch(uploadUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
                body: formData,
            })
                .then((response) => response.json())
                .then((json) => {
                    if (!json.location) {
                        reject('Upload failed');
                        return;
                    }
                    resolve(json.location);
                })
                .catch(() => reject('Upload failed'));
        }),
        setup: function (instance) {
            instance.on('change', function () {
                tinymce.triggerSave();
            });
        },
    });
};

document.addEventListener('DOMContentLoaded', () => {
    window.initAdminEditor();
});
