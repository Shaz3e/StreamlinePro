@extends('components.layouts.app')

@section('content')
    @include('partials.page-header', [
        'title' => 'Create New Download',
        'breadcrumbs' => [
            ['text' => 'Dashboard', 'link' => route('admin.dashboard')],
            ['text' => 'Downloads', 'link' => route('admin.downloads.index')],
            ['text' => 'Create', 'link' => null],
        ],
    ])

    {{-- Create Form --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form action="{{ route('admin.downloads.store') }}" method="POST" class="needs-validation" novalidate
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <div class="form-group">
                                    <label for="title">Download Title <strong class="text-danger">*</strong></label>
                                    <input type="text" name="title" id="title" class="form-control"
                                        value="{{ old('title') }}" required>
                                </div>
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- /.col --}}
                            <div class="col-md-2 mb-3">
                                <div class="form-group">
                                    <label for="version">Version <strong class="text-danger">*</strong></label>
                                    <input type="text" name="version" id="version" class="form-control"
                                        value="{{ old('version') }}" maxlength="50" required>
                                </div>
                                @error('version')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- /.col --}}
                            <div class="col-md-2 mb-3">
                                <div class="form-group">
                                    <label for="is_active">Status <strong class="text-danger">*</strong></label>
                                    <select class="form-control" name="is_active" required>
                                        <option value="1" {{ old('is_active') == 1 ? 'selected' : '' }}>Show</option>
                                        <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Hide</option>
                                    </select>
                                </div>
                                @error('is_active')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- /.col --}}
                        </div>
                        {{-- /.row --}}

                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="form-group">
                                    <label for="description">Download Details <strong class="text-danger">*</strong></label>
                                    <textarea id="description" name="description" class="form-control textEditor" rows="5" required>{{ old('description') }}</textarea>
                                </div>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- /.col --}}
                        </div>
                        {{-- /.row --}}

                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="form-group">
                                    <label for="download_link">Download Link (External)</label>
                                    <input type="text" name="download_link" id="download_link" class="form-control"
                                        value="{{ old('download_link') }}" maxlength="255">
                                </div>
                                @error('download_link')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- /.col --}}
                        </div>
                        {{-- /.row --}}

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="file_path">Attachments (optional)</label>
                                    <div class="input-group">
                                        <input type="file" name="file_path" id="file_path" class="form-control" multiple>
                                    </div>
                                    <small class="d-block text-muted">Allowed Formats: .ZIP, .EXE, .MSI with max 100MB file
                                        size allowed.
                                    </small>
                                </div>
                                @error('file_path')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 mt-2 mb-2">
                                <div id="upload-progress"></div>
                                <div class="progress">
                                    <div id="progress-bar" class="progress-bar" role="progressbar" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                        <span>0%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- /.row --}}

                        <div class="row">
                            <div class="col-md-12 mt-2 mb-3">
                                <div class="form-group">
                                    <label for="user_id">Assign Users</label>
                                    <select class="form-control select2" name="user_id[]" id="user_id"
                                        multiple="multiple">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('user_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- /.col --}}
                        </div>
                        {{-- /.row --}}
                    </div>
                    {{-- /.card-body --}}
                    <div class="card-footer">
                        <x-form.button class="submitButton" />
                        <x-form.button-save-view class="submitButton" />
                        <x-form.button-save-create-new class="submitButton" />
                    </div>
                    {{-- /.card-footer --}}
                </form>
            </div>
            {{-- /.card --}}
        </div>
        {{-- /.col --}}
    </div>
    {{-- /.row --}}
@endsection

@push('styles')
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css">
@endpush

@push('scripts')
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <!--tinymce js-->
    <script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script>

    <script>
        // File upload
        $('#file_path').change(function() {
            var file = this.files[0];
            if (!file) {
                $('#upload-progress').html('<div class="alert alert-danger">No file selected!</div>');
                return;
            }

            var formData = new FormData();
            formData.append('file_path', file); // Add file to formData

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            $.ajax({
                url: `{{ route('admin.downloads.upload.file') }}`,
                method: 'POST',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var progress = Math.round(evt.loaded / evt.total * 100);
                            console.log(progress);
                            $('#progress-bar').css('width', progress + '%');
                            $('#progress-bar span').text(progress + '%');
                            $('#upload-progress').html(''); // Clear previous message
                            $('#submitButton').prop('disabled',
                            true); // Disable button during upload
                        }
                    }, false);
                    return xhr;
                },
                success: function(response) {
                    console.log(response);
                    $('#progress-bar').css('width', '0%'); // Reset progress bar
                    $('#upload-progress').html(
                        '<div class="alert alert-success">Upload successful!</div>'
                        ); // Show success message
                    setTimeout(function() {
                        $('#upload-progress').html(''); // Hide success message after 1 second
                    }, 1000);
                    $('#submitButton').prop('disabled', false); // Enable submit button after upload
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    var errorMessage = 'Upload failed!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage += ' ' + xhr.responseJSON.message;
                    }
                    $('#upload-progress').html('<div class="alert alert-danger">' + errorMessage +
                        '</div>'); // Show error message
                }
            });
        });


        $(document).ready(function() {
            // Search Users
            $('#user_id').select2({
                ajax: {
                    url: '{{ route('admin.search.users') }}',
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function(params) {
                        return {
                            term: params.term
                        };
                    },
                    processResults: function(data) {
                        console.log(data);
                        return {
                            results: data.results
                        };
                    }
                },
                minimumInputLength: 3
            });
            // Description
            0 < $(".textEditor").length && tinymce.init({
                selector: "textarea.textEditor",
                height: 300,
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "save table directionality emoticons template paste"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
                /* enable title field in the Image dialog*/
                image_title: true,
                /* enable automatic uploads of images represented by blob or data URIs*/
                automatic_uploads: true,
                /*
                    URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
                    images_upload_url: 'postAcceptor.php',
                    here we add custom filepicker only to Image dialog
                */
                file_picker_types: 'image',
                /* and here's our custom image picker*/
                file_picker_callback: (cb, value, meta) => {
                    const input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');

                    input.addEventListener('change', (e) => {
                        const file = e.target.files[0];

                        const reader = new FileReader();
                        reader.addEventListener('load', () => {
                            /*
                            Note: Now we need to register the blob in TinyMCEs image blob
                            registry. In the next release this part hopefully won't be
                            necessary, as we are looking to handle it internally.
                            */
                            const id = 'blobid' + (new Date()).getTime();
                            const blobCache = tinymce.activeEditor.editorUpload
                                .blobCache;
                            const base64 = reader.result.split(',')[1];
                            const blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);

                            /* call the callback and populate the Title field with the file name */
                            cb(blobInfo.blobUri(), {
                                title: file.name
                            });
                        });
                        reader.readAsDataURL(file);
                    });

                    input.click();
                },
                // content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
                style_formats: [{
                        title: "Bold text",
                        inline: "b"
                    },
                    {
                        title: "Red text",
                        inline: "span",
                        styles: {
                            color: "#ff0000"
                        }
                    }, {
                        title: "Red header",
                        block: "h1",
                        styles: {
                            color: "#ff0000"
                        }
                    }, {
                        title: "Example 1",
                        inline: "span",
                        classes: "example1"
                    }, {
                        title: "Example 2",
                        inline: "span",
                        classes: "example2"
                    }, {
                        title: "Table styles"
                    }, {
                        title: "Table row 1",
                        selector: "tr",
                        classes: "tablerow1"
                    }
                ]
            });
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent form submission for testing
            let fileInput = document.querySelector('#file_path');
            console.log(fileInput.files); // Should contain the file array
        });
    </script>
@endpush
