@extends('admin')
@section('content')
@can('trash_category')

<div class="row">
    <div class="col-lg-8 m-auto">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="text-white">Trash Category</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('check.restore') }}" method="POST">
                    @csrf
                <table class="table table-striped">
                    <tr>
                        <th width="50">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" id="chkSelectAll" class="form-check-input">
                                    Check All
                                <i class="input-frame"></i></label>
                            </div>
                        </th>
                        <th>SL</th>
                        <th>Category</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                    @forelse ($trash_categories as $index=>$trash)
                    <tr>
                        <td>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" name="category_id[]" class="form-check-input chkDel" value="{{ $trash->id }}">
                                <i class="input-frame"></i></label>
                            </div>
                        </td>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $trash->category_name }}</td>
                        <td><img src="{{ asset('uploads/category') }}/{{ $trash->category_image }}" alt=""></td>
                        <td>
                            <a href="{{ route('category.restore', $trash->id) }}" class="btn btn-success">Restore</a>
                            <a data-link="{{ route('category.hard.delete', $trash->id) }}" class="btn btn-danger del">Delete</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center"><h3>No Data Found</h3></td>
                    </tr>
                    @endforelse
                </table>
                <div class="my-2">
                    <button type="submit" value="1" name="action_btn" class="btn btn-success d-none del_all">Restore checked</button>
                    <button type="submit" value="2" name="action_btn" class="btn btn-danger d-none del_all">Delete checked</button>
                </div>
              </form>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection

@section('footer_script')
<script>
    $("#chkSelectAll").on('click', function(){
        this.checked ? $(".chkDel").prop("checked",true) : $(".chkDel").prop("checked",false);  
        $('.del_all').toggleClass('d-none');  
    })
    $('.chkDel').click(function(){
        $('.del_all').removeClass('d-none');  
    });
</script>
@if (session('restore'))
    <script>
            Swal.fire({
            position: "center",
            icon: "success",
            title: "{{ session('restore') }}",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
@endif

<script>
    $('.del').click(function(){
        Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
        }).then((result) => {
        if (result.isConfirmed) {
            var link = $(this).attr('data-link');
            window.location.href = link
        }
        });
    });
</script>
@if (session('del'))
    <script>
        Swal.fire({
      title: "Deleted!",
      text: "{{ session('del') }}",
      icon: "success"
    });
    </script>
@endif
@endsection