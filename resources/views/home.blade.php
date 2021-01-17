@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
       
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white border">
                <li class="breadcrumb-item"><a class="text-dark" href="{{ route('home') }}">Home</a></li>
                @isset($folder)
                    @php
                    $parentFolders = [];
                    $parent = $folder->parent;
                    while($parent){
                    array_unshift($parentFolders, $parent);

                    $parent = $parent->parent;
                    }
                    @endphp
                    @foreach ($parentFolders as $item)

                        <li class="breadcrumb-item"><a class="text-dark" href="{{ route('home') }}?folder={{ $item->name }}">{{ $item->name }}</a>
                        </li>
                    @endforeach
                    <li class="breadcrumb-item active" aria-current="page">{{ $folder->name }}</li>
                @endisset
            </ol>
        </nav>
        <div class="card">
            <div class="card-header bg-dark">
                <div class="d-flex justify-content-between">

                    <form class="form-inline my-2 my-lg-0">
                        <input class="form-control rounded-0 mr-sm-2" name="search" value="{{request()->query('search')}}" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-secoundary rounded-0 my-2 my-sm-0" type="submit">Search</button>
                    </form>
                    <div class="d-flex justify-content-end">
                        <button class="btn bg-transparent rounded-0" data-toggle="modal" data-target="#addFolderModal" data-toggle="tooltip" data-placement="top" title="Create a folder"><i
                                class="fas fa-folder-plus fa-2x  text-light"></i></button>
                        <button class="btn bg-transparent rounded-0 ml-2" data-toggle="modal"
                            data-target="#addImageModal" data-toggle="tooltip" data-placement="top" title="Upload single image"><i class="fas fa-plus-square fa-2x text-light"></i></button>
                        <button class="btn bg-transparent rounded-0 ml-2" data-toggle="modal" data-target="#addMultipleImagesModal" data-toggle="tooltip" data-placement="top" title="Upload multiple images">
                            <i class="fas fa-layer-group fa-2x  text-light"></i></button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row no-gutters">
                    @foreach ($files as $file)
                        @if ($file->caption)
                            <div class="col-md-2 p-1">
                                <div class="card text-center text-left border-0 img-card">
                                    <div style="height: 100px">

                                        <img class="card-img-to h-100" src="{{ $file->url }}" alt="">
                                    </div>
                                    <div class="card-foote mt-2">
                                        <small class="card-title">{{ $file->caption }}</small>
                                    </div>
                                    <a href="#" data-toggle="modal" data-target="#previewImageModal-{{ $file->id }}"></a>
                                </div>

                                <!-- Modal -->
                                <div class="modal  fade" id="previewImageModal-{{ $file->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="previewImageModal-{{ $file->id }}Label" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark rounded-0 text-light">
                                                <h5 class="modal-title" id="previewImageModal-{{ $file->id }}Label">
                                                    {{ $file->caption }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body bg-dark">
                                                <div class="form-control mb-2">
                                                    Public Link
                                                    <a class="card-link" target="_blank"
                                                        href="{{ $file->url }}">{{ $file->url }}</a>
                                                </div>
                                                <div class="d-flex justify-content-end mb-2">
                                                    <form action="{{route('images.update', $file->id)}}" class="update-image-form" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="text" name="caption" value="{{ $file->caption }}"
                                                            class="form-control">
                                                    </form>
                                                    <div class="btn-group ml-2">
                                                        <button type="button" class="btn btn-primary btn-sm" onclick="$(this).parent().parent().find('.update-image-form').submit()">Rename</button>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="$(this).parent().find('.delete-image-form').submit()" >Delete</button>
                                                        <form action="{{route('images.destroy', $file->id)}}" class="delete-image-form" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="text-center" style="height: 500px">

                                                    <img class="img-fluid" src="{{ $file->url }}" alt="">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-2 m-1">
                                <div class="card text-center border-0 text-left img-card folder">
                                    {{-- <div class="card-header"><button
                                            class="btn close">&times;</button></div> --}}
                                    <div style="height: 100px">
                                        <button class="btn close mr-2"
                                            style="position-absolute; z-index: 1000">&times;</button>

                                        <i class="fas fa-folder fa-10x"></i>

                                    </div>
                                    <div class="card-body">
                                        <div class="title d-none">{{ $file->name }}</div>
                                        <form action="{{ route('folders.destroy', $file->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="">

                                        </form>
                                    </div>
                                    <div class="card-foote p-1 bg-white text-center">
                                        <small class="card-title">{{ $file->name }} <span class="text-secondary">(
                                                {{ $file->total_files ? $file->total_files . ' files' : 'empty' }}
                                                )</span></small>
                                    </div>
                                    {{-- <a
                                        href="{{ route('home') }}?folder={{ $file->name }}"></a>
                                    --}}

                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <!-- create folder Modal -->
        <div class="modal fade" id="addFolderModal" tabindex="-1" role="dialog" aria-labelledby="addFolderModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-dark rounded-0  text-light">
                        <h5 class="modal-title" id="addFolderModalLabel">New Folder</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('folders.store') }}" method="post" id="add-folder-form">
                            @csrf
                            <div class="form-group">
                                <input name="name" type="text" placeholder="Folder name" class="form-control" required>
                            </div>
                            @isset($folder)
                                <input type="hidden" name="parent_id" value="{{ $folder->id }}">
                            @endisset
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary"
                            onclick="document.querySelector('#add-folder-form').submit()">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addImageModal" tabindex="-1" role="dialog" aria-labelledby="addImageModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-dark rounded-0  text-light">
                        <h5 class="modal-title" id="addImageModalLabel">New Image</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('images.store') }}" id="add-image-form" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card mb-3 p-2" style="height: 300px">
                                <img src="" alt="" id="preview" class="h-100 w-100 d-none">
                            </div>
                            <div class="form-group">
                                <input name="image" type="file" placeholder="Image" id="upload" class="form-control-file">
                            </div>
                            <div class="form-group">
                                <input name="caption" type="text" placeholder="Caption" class="form-control">
                            </div>

                            @isset($folder)
                                <input type="hidden" name="folder_id" value="{{ $folder->id }}">
                            @endisset
                        </form>
                        {{-- <div class="form-group">
                            <label for=""> <input name="is_public" type="checkbox" class="form-control-checkbox">
                                Public</label>
                        </div> --}}
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary"
                            onclick="document.querySelector('#add-image-form').submit()">Upload</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addMultipleImagesModal" tabindex="-1" role="dialog" aria-labelledby="addMultipleImagesModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-dark rounded-0  text-light">
                        <h5 class="modal-title" id="addMultipleImagesModalLabel">Add Multiple Images</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('images.store') }}" id="add-multiple-images-form" method="post"
                            enctype="multipart/form-data">
                            @csrf
                           
                            <div class="form-group">
                                <input name="images[]" multiple type="file" placeholder="Image" id="upload" class="form-control-file">
                            </div>
                            <div class="form-group">
                                <input name="caption" type="text" placeholder="Caption" class="form-control">
                            </div>

                            @isset($folder)
                                <input type="hidden" name="folder_id" value="{{ $folder->id }}">
                            @endisset
                        </form>
                        {{-- <div class="form-group">
                            <label for=""> <input name="is_public" type="checkbox" class="form-control-checkbox">
                                Public</label>
                        </div> --}}
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary"
                            onclick="document.querySelector('#add-multiple-images-form').submit()">Upload</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function() {
            $('#upload').change(function() {
                var input = this;
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" ||
                        ext == "jpg")) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#preview').attr('src', e.target.result);
                        $('#preview').removeClass('d-none');
                    }
                    reader.readAsDataURL(input.files[0]);
                } else {
                    $('#preview').attr('src', '/assets/no_preview.png');
                }
            });

            $(".img-card.folder").dblclick(function() {
                window.location = "{{ route('home') }}?folder=" + $(this).find('.title').html()
            });

            $(".img-card.folder .close").click(function() {
                let confirm = window.confirm(
                    'Deleting folder will remove all files!\nDo you want to delete this folder?')
                if (confirm) {
                    $(this).parent().parent().find('form').submit();
                }
            })

        });

    </script>
@endsection
