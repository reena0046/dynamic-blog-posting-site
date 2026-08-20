@extends('layouts.admin')

@section('title')
    Blogs
@endsection

@section('content')
    <div class="row blog-list-page">
        <div class="col-lg-12">
            <div class="card w-100 blog-list-card">
                <div class="card-header blog-list-header">
                    <h5 class="mb-0">Blogs</h5>
                    <div class="blog-header-actions">
                        <select id="blog-sort" class="form-select blog-sort-select">
                            <option value="newest">Newest to oldest</option>
                            <option value="oldest">Oldest to newest</option>
                            <option value="az">A-Z</option>
                        </select>
                        <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary blog-create-btn">
                            Create +
                        </a>
                    </div>
                </div>
                <div class="card-body blog-list-body">
                    <div class="table-responsive">
                        <table class="table border table-sm table-bordered text-nowrap mb-0 align-middle" id="datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th width="3%">Action</th>
                                    <th width="3%">Status</th>
                                    <th>Title</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            // Blogs DataTable
            var dataTable = $('#datatable').DataTable({
                processing: false,
                serverSide: true,
                autoWidth: false,
                scrollCollapse: false,
                pageLength: 10,
                lengthMenu: [10, 20, 30, 40, 50],
                searching: true,
                ajax: {
                    url: '{!! route('admin.blogs.data') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                        d.sort = $('#blog-sort').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title',
                        orderable: false
                    },
                ],
                ordering: false,
                order: [],
                columnDefs: [{
                    targets: [0, 1, 2],
                    className: 'text-center'
                }],
                initComplete: function() {
                    var api = this.api();
                    var $input = $('#datatable_filter input');

                    $input.off();
                    $input.on('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            api.search(this.value).draw();
                        }
                    });
                }
            });

            $('#blog-sort').on('change', function() {
                dataTable.ajax.reload();
            });

            $(document).on('change', '.blog-status-switch', function(e) {
                e.preventDefault();

                var id = $(this).data('id');
                var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';

                $.ajax({
                    url: "{{ route('admin.blogs.update-status') }}",
                    type: 'POST',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: id,
                        status: status
                    },
                    success: function(data) {
                        if (data.status === 'success') {
                            toastr.success(data.message);
                            dataTable.ajax.reload(null, false);
                        } else {
                            toastr.error(data.message);
                        }
                    },
                    error: function() {
                        toastr.error('Something went wrong!');
                        dataTable.ajax.reload(null, false);
                    }
                });
            });
        });
    </script>
@endpush
