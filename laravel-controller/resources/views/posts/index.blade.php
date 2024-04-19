@extends('layouts.admin.master')
@section('page-title', 'Posts')
@push('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush
@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-5 text-gray-900 dark:text-gray-100">
                    <a class="btn btn-primary rounded"
                       href="#"
                       data-toggle="modal" data-target="#createModal"
                    >
                        Add new post
                    </a>
                    <div class="relative overflow-x-auto mb-5">
                        <table id="example2" class="table table-bordered table-hover dataTable dtr-inline">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    #
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Title
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Author
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($posts as $post)
                                <tr class="bg-white dark:bg-gray-800">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $post->id }}
                                    </th>
                                    <td class="px-6 py-4">
                                        <a
                                            href="#"
                                            onclick="detailModal(this)"
                                            data-id="{{ $post->id }}"
                                        >
                                            {{ $post->title }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $post->user->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @can('update', $post)
                                            <button class="btn btn-success rounded mr-3 edit-btn"
                                                    data-id="{{ $post->id }}" onclick="editModal(this)">Edit
                                            </button>
                                        @endcan
                                        @can('delete', $post)
                                            <button class="btn btn-danger rounded delete-btn" data-id="{{ $post->id }}"
                                                    onclick="deleteModal(this)">
                                                Delete
                                            </button>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create post</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" placeholder="Title">
                                <span class="text-danger" id="title-error"></span>
                            </div>
                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea name="" id="content" cols="30" rows="5" class="form-control"
                                          placeholder="Content"></textarea>
                                <span class="text-danger" id="content-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btn-create-post">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit post</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" name="post_id" id="post_id">

                        <div class="card-body">
                            <div class="form-group">
                                <label for="title-edit">Title</label>
                                <input type="text" class="form-control" id="title-edit" placeholder="Title">
                                <span class="text-danger" id="title-edit-error"></span>
                            </div>
                            <div class="form-group">
                                <label for="content-edit">Content</label>
                                <textarea name="" id="content-edit" cols="30" rows="5" class="form-control"
                                          placeholder="Content"></textarea>
                                <span class="text-danger" id="content-edit-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btn-edit-post">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1"
         role="dialog" aria-labelledby="detailModal"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div>
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title text-white"
                                id="detail-title"></h5>

                            <p class="fs-6 text-primary p-0 m-0">
                                <span id="author"></span>
                                -
                                <span id="created-at"></span>
                            </p>
                        </div>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-white" id="detail-content">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>

    <script>

        let posts = @json($posts);

        const detailTitle = $('#detail-title');
        const detailContent = $('#detail-content');
        const author = $('#author');
        const createdAt = $('#created-at');

        const detailModal = function (event) {
            const postId = event.dataset.id;

            const post = posts.find(post => post.id == postId);

            detailTitle.text(post.title);
            detailContent.text(post.content);
            author.text(post.user.name);
            createdAt.text(post.created_at);

            $('#detailModal').modal('show');
        }

        const editModal = function (event) {
            const postId = event.dataset.id;

            let post = posts.find(post => post.id == postId);

            if (!post) {
                return;
            }

            $('#editModal').modal('show');

            $('#title-edit').val(post.title);

            $('#content-edit').val(post.content);

            $('#post_id').val(post.id);
        }

        const deleteModal = function (event) {
            if (!confirm('Are you sure you want to delete this post?')) {
                return;
            }

            const postId = event.dataset.id;

            const tr = $(event).closest('tr');

            $.ajax({
                url: '{{ route('posts.destroy', ':id') }}'.replace(':id', postId),
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    posts = posts.filter(post => post.id != postId);
                    tr.remove();
                }
            });
        }

        $(function () {

            const csrfToken = '{{ csrf_token() }}';

            const datatable = $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "processing": true,
                "order": [[0, "desc"]],
            });

            $('#btn-create-post').on('click', function () {
                const title = $('#title').val();
                const content = $('#content').val();

                let title_error = '';
                let content_error = '';

                $.ajax({
                    url: '{{ route('posts.store') }}',
                    type: 'POST',
                    data: {
                        _token: csrfToken,
                        title: title,
                        content: content
                    },
                    success: function (response) {

                        $('#createModal').modal('hide');
                        $('#title').val('');
                        $('#content').val('');

                        posts.push(response.post);

                        datatable.row.add([
                            response.post.id,
                            `<a
                                href="#"
                                onclick="detailModal(this)"
                                data-id="${response.post.id}"
                            >
                                ${response.post.title}
                            </a>`,
                            response.post.user.name,
                            `<button class="btn btn-success rounded mr-3 edit-btn" data-id="${response.post.id}" onclick="editModal(this)">Edit</button>
                            <button class="btn btn-danger rounded delete-btn" data-id="${response.post.id}"  onclick="deleteModal(this)">Delete</button>`
                        ]).draw(false);
                    },
                    error: function (error) {
                        const errors = error?.responseJSON?.errors;

                        if (errors) {
                            title_error = errors.title ? errors.title[0] : '';

                            content_error = errors.content ? errors.content[0] : '';
                        }

                        $('#title-error').text(title_error);

                        $('#content-error').text(content_error);
                    }
                })
            });

            $('#btn-edit-post').on('click', function () {
                const title = $('#title-edit').val();
                const content = $('#content-edit').val();
                const postId = $('#post_id').val();

                let title_error = '';
                let content_error = '';

                $.ajax({
                    url: '{{ route('posts.update', ':id') }}'.replace(':id', postId),
                    type: 'PUT',
                    data: {
                        _token: csrfToken,
                        title: title,
                        content: content
                    },
                    success: function (response) {
                        $('#editModal').modal('hide');
                        $('#title-edit').val('');
                        $('#content-edit').val('');

                        const tr = $('.edit-btn[data-id="' + postId + '"]').closest('tr');

                        let post = posts.find(post => post.id == postId);

                        post.title = response.post.title;

                        post.content = response.post.content;

                        datatable.row(tr).data([
                            response.post.id,
                            `<a
                                href="#"
                                onclick="detailModal(this)"
                                data-id="${response.post.id}"
                            >
                                ${response.post.title}
                            </a>`,
                            response.post.user.name,
                            `<button class="btn btn-success rounded mr-3 edit-btn" data-id="${response.post.id}" onclick="editModal(this)">Edit</button>
                            <button class="btn btn-danger rounded delete-btn" data-id="${response.post.id}"  onclick="deleteModal(this)">Delete</button>`
                        ]).draw(false);

                    },
                    error: function (error) {
                        const errors = error?.responseJSON?.errors;

                        if (errors) {
                            title_error = errors.title ? errors.title[0] : '';

                            content_error = errors.content ? errors.content[0] : '';
                        }

                        $('#title-edit-error').text(title_error);

                        $('#content-edit-error').text(content_error);
                    }
                });
            });
        });

    </script>
@endpush
