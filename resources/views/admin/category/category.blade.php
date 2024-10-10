@extends('admin')
@section('content')
    <div class="row">
        @can('category')
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="text-white">Category List</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('check.delete') }}" method="POST">
                            @csrf
                            <table class="table table-bordered" id="myTable">
                                <thead>
                                    <tr>
                                        <th width="50">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" id="chkSelectAll"
                                                        {{ $categories->count() == 0 ? 'disabled' : '' }}
                                                        class="form-check-input">
                                                    Check All
                                                    <i class="input-frame"></i></label>
                                            </div>
                                        </th>
                                        <th>SL</th>
                                        <th>Category</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($categories as $sl=>$category)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" name="category_id[]"
                                                            class="form-check-input chkDel" value="{{ $category->id }}">
                                                        <i class="input-frame"></i></label>
                                                </div>
                                            </td>
                                            <td>{{ $sl + 1 }}</td>
                                            <td>{{ $category->category_name }}</td>
                                            <td><img src="{{ asset('uploads/category') }}/{{ $category->category_image }}"
                                                    width="100" alt=""></td>
                                            <td>
                                                <a href="{{ route('category.delete', $category->id) }}"
                                                    class="btn btn-danger">Delete</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <h3>No Data Found</h3>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="my-2">
                                <button type="submit" id="del_all" class="btn btn-danger d-none">Delete checked</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan
        @can('category_add')
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="text-white">Add New Category</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Category Name</label>
                                <input type="text" name="category_name" class="form-control">
                                @error('category_name')
                                    <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Category Image</label>
                                <input type="file" name="category_image" class="form-control"
                                    onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                @error('category_image')
                                    <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                                <div class="my-2">
                                    <img src="" id="blah" width="200" alt="">
                                </div>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Add Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan
    </div>
@endsection

@section('footer_script')
    @if (session('category'))
        <script>
            Swal.fire({
                position: "center",
                icon: "success",
                title: "{{ session('category') }}",
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    @endif
    @if (session('soft_del'))
        <script>
            Swal.fire({
                position: "center",
                icon: "success",
                title: "{{ session('soft_del') }}",
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    @endif
    <script>
        let del = document.querySelectorAll('.chkDel');
        $('#chkSelectAll').click(function() {
            $('.chkDel').prop('checked', this.checked);
            $('#del_all').toggleClass('d-none', !this.checked);
        });
        $('.chkDel').click(function() {
            const anyChecked = Array.from(del).some(element => element.checked);
            if (anyChecked) {
                $('#del_all').removeClass('d-none');
            } else {
                $('#del_all').addClass('d-none');
            }
        });
    </script>
    <script>
        let table = new DataTable('#myTable');
    </script>
@endsection
