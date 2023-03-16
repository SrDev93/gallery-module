@extends('layouts.admin')

@push('stylesheets')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
@endpush

@section('content')
    <!-- CONTAINER -->
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
    @include('gallery::partial.header')
    <!-- PAGE-HEADER END -->

        <!-- ROW -->
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h3 class="card-title">ویرایش گالری</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('gallery.update', $gallery->id) }}" method="post" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                            @include('admin.partial.lang')
                            @include('admin.partial.brand')
                            <div class="col-md-6">
                                <label for="title" class="form-label">عنوان</label>
                                <input type="text" name="title" class="form-control" id="title" required value="{{ $gallery->title }}">
                                <div class="invalid-feedback">لطفا عنوان را وارد کنید</div>
                            </div>
                            <div class="col-md-5">
                                <label for="image" class="form-label">تصویر شاخص</label>
                                <input type="file" name="image" class="form-control" aria-label="تصویر شاخص" id="image" accept="image/*" @if(!$gallery->image) required @endif>
                                <div class="invalid-feedback">لطفا یک تصویر انتخاب کنید</div>
                            </div>
                            <div class="col-md-1">
                                @if($gallery->image)
                                    <label for="image" class="form-label">تصویر فعلی</label>
                                    <img src="{{ url($gallery->image) }}" style="max-width: 50%;">
                                @endif
                            </div>
                            <div class="col-md-12">
                                <label for="photos[]" class="form-label">تصاویر گالری</label>
                                <input type="file" name="photos[]" class="form-control" aria-label="تصاویر گالری" accept="image/*" @if(!count($gallery->photo)) required @endif multiple>
                                <div class="invalid-feedback">لطفا یک یا چند تصویر انتخاب کنید</div>
                            </div>
                            @if(count($gallery->photo))
                                <div class="col-md-12">
                                    <ul>
                                        @foreach($gallery->photo as $photo)
                                            <li class="mb-2">
                                                <a href="{{ url($photo->path) }}" target="_blank"><img src="{{ url($photo->path) }}" width="100"></a>
                                                <a href="{{ route('gallery-photo-destroy', $photo->id) }}" class="btn btn-danger" onclick="return confirm('برای حذف اطمینان دارید؟');"><i class="fa fa-trash"></i> حذف</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">بروزرسانی</button>
                                @csrf
                                @method('PATCH')
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- ROW CLOSED -->

    </div>

    @push('scripts')

    @endpush
@endsection
