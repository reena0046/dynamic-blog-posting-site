<div id="modalOne" class="modal fade" tabindex="-1" aria-labelledby="modalOneTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-md">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center p-4">
                <h4 class="modal-title" id="modalOneTitle"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 border-top border-bottom" id="modalOneBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.modal-one-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var $btn = $(this);
        var title = $btn.data('title') || 'Details';
        var url = $btn.attr('href');

        if (!url) {
            url = '/admin/' + $btn.data('entity') + '/' + $btn.data('routeKey');
        }

        $.ajax({
            type: 'GET',
            url: url,
            cache: false,
            headers: {
                'Accept': 'text/html',
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(data) {
                var modalSize = $btn.attr('data-modal-size') || 'md';
                var $dialog = $('#modalOne .modal-dialog');

                $dialog.removeClass('modal-sm modal-md modal-lg modal-xl');
                $dialog.addClass('modal-' + modalSize);

                if ($btn.attr('data-modal-scrollable') === 'false') {
                    $dialog.removeClass('modal-dialog-scrollable');
                } else {
                    $dialog.addClass('modal-dialog-scrollable');
                }

                $('#modalOneTitle').text(title);
                $('#modalOneBody').html(data);

                bootstrap.Modal.getOrCreateInstance(document.getElementById('modalOne')).show();
            },
            error: function() {
                toastr.error('There is some problem. Please try again', '', {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    timeOut: 1500,
                    closeButton: true
                });
            }
        });

        return false;
    });
</script>
