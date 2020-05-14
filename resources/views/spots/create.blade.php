<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Nuevo anuncio</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/croppie.css') }}"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="{{ asset('js/croppie.js') }}"></script>


</head>
<body id="app">

    <div id="uploadimageModal" class="modal" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload & Crop Image</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
            <div class="row">
        <div class=" col-md-12 text-center">
            <div id="image_demo" style="margin-top:30px"></div>
        </div>
        <div class="col-md-12" style="padding-top:30px;">
            <br />
            <br />
            <br/>
            <button class="btn btn-success crop_image">Crop & Upload Image</button>
        </div>
        </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>

    <div class="container mt-5">
    <h1>Nuevo anuncio</h1>
        <form action="{{ route('spots.create')}}" method="post" class="mt-3" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="form-group">
                <label for="inputImage">Imagen</label>
                <div id="uploaded_image"></div>
                <div class="input-group">
                    <div>
                        <input type="hidden" id="image" name="image">
                    </div>
                    <div class="custom-file">
                        <input type="file" name="upload_image" accept="image/*" class="form-control-file" id="inputImage" lang="es">
                        <label for="inputImage" class="custom-file-label">Seleciona una imagen...</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="inputText">Texto:</label>
                <input type="text" class="form-control" name="text" id="inputText">
            </div>

            <div class="form-group">
                <label for="inputSize">Tamaño letra:</label>
                <input type="number" class="form-control" name="size" value="24" id="inputSize">
            </div>

            <div class="form-group">
                <label for="inputColor">Color:</label>
                <input type="color" class="form-control colorpicker" name="color" id="inputColor">
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="obscure" id="obscure">
                <label class="form-check-label" for="obscure">¿Oscurecer?</label>
            </div>

            <div class="form-group mt-5">
                <input type="submit" class="btn btn-success" value="Crear anuncio">
            </div>
        </form>
    </div>
</body>

<script type="application/javascript">

    $(document).ready(function(){


        $image_crop = $('#image_demo').croppie({
            enableExif: true,
            viewport: {
                width:400,
                height:200,
                type:'square' //circle
            },
            boundary:{
                width:410,
                height:210
            }
        });

        $('#inputImage').on('change', function(){
            var reader = new FileReader();
            reader.onload = function (event) {
                $image_crop.croppie('bind', {
                url: event.target.result
                }).then(function(){
                    console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
            $('#uploadimageModal').modal('show');
        });

        $('.crop_image').click(function(event){
            $image_crop.croppie('result', {
                type: 'canvas',
                size: 'viewport',
                backgroundColor:'white'
            }).then(function(response){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:"{{ route('spots.upload')}}",
                    type: "POST",
                    data:{
                        "upload_image": response
                    },
                    dataType: 'json',
                    cache: false,
                    success:function(data)
                    {
                        $('#uploadimageModal').modal('hide');
                        console.log(data);
                        $('#uploaded_image').html('<img src='+ data[0] +' class="img-thumbnail" />');
                        $('#image').val(data[1]);
                    }
                });
            })
        });
    });  

    $('input[type="file"]').change(function(e){
        var fileName = e.target.files[0].name;
        $('.custom-file-label').html(fileName);
    });


</script>

</html>