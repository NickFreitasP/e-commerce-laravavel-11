@extends("back.layouts.dashboardAdmin")
@section("content")
 <div class="main-content">

                        <!-- main-content-wrap -->
                        <div class="main-content-inner">
                            <!-- main-content-wrap -->
                            <div class="main-content-wrap">
                                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3>Gallery images</h3>
                                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                                        <li>
                                            <a href="{{route("admin.index")}}">
                                                <div class="text-tiny">Dashboard</div>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="icon-chevron-right"></i>
                                        </li>
                                        <li>
                                            <a href="{{route("admin.products")}}">
                                                <div class="text-tiny">Products</div>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="icon-chevron-right"></i>
                                        </li>
                                        <li>
                                            <div class="text-tiny">Add product</div>
                                        </li>
                                    </ul>
                                </div>
                                <!-- form-add-product -->

                                    <div class="wg-box">
                                     @if(Session::has("status"))

                                     <div class="alert alert-success">{{Session('status')}}</div>



                                     @endif

                                    <div class="wg-box">
                                       <form action="{{route("admin.add-product-principal-image",["id" => $product->id])}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <fieldset>
                                            <div class="body-title">Imagem principal
                                            </div>
                                            <div class="upload-image flex-grow">
                                                <div class="item" id="imgpreview" >
                                                    <img src="{{asset("uploads/products/".$product->image)}}"
                                                        class="effect8" alt="">
                                                </div>
                                                <div id="upload-file" class="item up-load">
                                                    <label class="uploadfile" for="myFile">
                                                        <span class="icon">
                                                            <i class="icon-upload-cloud"></i>
                                                        </span>
                                                        <span class="body-text">Drop your images here or select <span
                                                                class="tf-color">click to browse</span></span>
                                                        <input type="file" id="myFile" name="image" accept="image/*">
                                                         @error("image")
                                                          <span class="alert alert-danger mt-4 "> {{$message}}</span>
                                                         @enderror
                                                    </label>
                                                </div>
                                            </div>
                                                <div class="cols gap10 mt-5">
                                                  <button class="tf-button " type="submit">Adicionar nova imagem principal</button>
                                                </div>



                                            </fieldset>
                                    </form>
                                        <div class="body-title">Galeria de imagens
                                            </div>

                                           <div style="display: flex;gap:20px;">
                                               @foreach ($images as $image)

                                                    <div class="item image-item mb-3" style="position: relative;">
                                                        <img style="max-height: 250px" src="{{ asset('uploads/products/'.$image->image) }}"
                                                            alt="{{ $product->name }}">

                                                        <form action="{{ route('admin.product.image.destroy',["id" => $image->id]) }}"
                                                            method="POST"
                                                            style="position:absolute; top:6px; right:6px;">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit"
                                                                    class="delete-image-btn "
                                                                    onclick="return confirm('Deseja excluir esta imagem?')"
                                                                     aria-label="Excluir imagem "
                                                                    >
                                                                    <i class="icon-trash " style="color:#f2695c;"></i>
                                                            </button>

                                                            <input type="hidden" id="product_id" name="product_id" value="{{$product->id}}">
                                                        </form>
                                                    </div>
                                                @endforeach
                                           </div>

                                        </div>


                                         <fieldset>
                                          <form method="post" action="{{route("admin.add-product-image",["id_product"=>$product->id])}} " enctype="multipart/form-data"  >
                                            @csrf
                                            <div class="body-title mb-10">Upload Gallery Images</div>
                                            <div class="upload-image mb-16">
                                                <!-- <div class="item">
                                <img src="images/upload/upload-1.png" alt="">
                            </div>                                                 -->
                                                   @csrf
                                                    <div id="galUpload" class="item up-load">
                                                    <label class="uploadfile" for="gFile">
                                                        <span class="icon">
                                                            <i class="icon-upload-cloud"></i>
                                                        </span>
                                                        <span class="text-tiny">Drop your images here or select <span
                                                                class="tf-color">click to browse</span></span>
                                                        <input type="file" id="gFile" name="images[]" accept="image/*"
                                                            multiple="">
                                                        @if(Session::has("error-image"))
                                                           <div class="alert alert-danger">{{Session("error-image")}}</div>
                                                        @endif
                                                     </label>
                                                </div>
                                            </div>
                                                <div class="cols gap10">
                                                  <button class="tf-button w-full" type="submit">Adicionar nova imagem</button>
                                                </div>
                                            </form>
                                        </fieldset>




                                    </div>


                            </div>
                            <!-- /main-content-wrap -->
                        </div>
                        <!-- /main-content-wrap -->

                        <div class="bottom-page">
                            <div class="body-text">Copyright © 2024 SurfsideMedia</div>
                        </div>
                    </div>
@endsection
@push("scripts")
<script>
$(function () {
    $("#myFile").on("change", function () {
        const [file] = this.files;
        if (file) {
            $('#imgpreview img').attr("src", URL.createObjectURL(file));
            $('#imgpreview').show();
        }
    });

    $("#gFile").on("change", function () {
        const gphotos = this.files;

         $('#galUpload .gitems').remove();
        $.each(gphotos, function (key, val) {
            $('#galUpload').prepend(`
                <div class="item gitems">
                    <img src="${URL.createObjectURL(val)}" />
                </div>
            `);
        });
    });

    $("input[name='name']").on("input", function () {
        $("input[name='slug']").val(stringToSlug($(this).val()));
    });
});

function stringToSlug(text) {
    return text
        .toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, "")
        .replace(/\s+/g, "-");
}

</script>
@endpush
