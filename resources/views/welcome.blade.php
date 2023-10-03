<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

</head>

<body>
    <div class="container">
        <div class="row">
            <form action="{{ route('video') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3  col-12">
                    <label for="" class="form-label">Choose file</label>
                    <input type="file" class="form-control" name="video" id="" placeholder=""
                        aria-describedby="fileHelpId">
                </div>
                <div class="mb-3 d-flex gap-5">
                    <div class="col-4">
                        <input type="number" name="start_time" class="form-control" placeholder="Enter Start Time">
                    </div>
                    <div class="col-4">
                        <input type="number" name="end_time" class="form-control" placeholder="Enter end Time">
                    </div>
                </div>
                <button class="btn btn-secondary" type="submit">Submit</button>
            </form>
        </div>
    </div>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
        integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
    </script>
</body>

</html>
