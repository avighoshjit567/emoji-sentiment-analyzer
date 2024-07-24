<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Emoji Sentiment Analyzer</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href=" https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
</head>

<body>
    <div class="wrapper fadeInDown">
        <h2>Emoji Sentiment Analyzer</h2>
        <div id="formContent">
            <!-- Emoji Sentiment Analyze Form -->
            <form id="add_sentiment_score" enctype="multipart/form-data">
                <input type="hidden" name="hidden_id" id="hidden-id">
                <input type="text" id="text_input" class="fadeIn second" name="text_input"
                    placeholder="Input Here..">
                <input type="submit" class="fadeIn fourth" value="Analyze & Save">
            </form>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-12" style="margin-top:12px;">
                    <div class="">
                        <table class="table table-bordered table-striped mb-0" id="sentiment_table">
                            <thead class="table-dark">
                                <tr>
                                    <th>#SL</th>
                                    <th>Input Text</th>
                                    <th>Sentiment Score</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#hidden-id').attr("disabled", "true");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $('#add_sentiment_score').on('submit', function(e) {
                e.preventDefault();
                // alert(11);
                var formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: "{{ route('emoji.analyze') }}",
                    data: formData,
                    success: function(response) {
                        Swal.fire("Congratulations!", response.message, "success");
                        document.getElementById("add_sentiment_score").reset();
                        $('#sentiment_table').DataTable().draw(true);
                        $('#hidden-id').attr("disabled", "true");
                    },
                    error: function(response) {
                        // $('#message').html('<p>' + response.responseJSON.message + '</p>');
                        Swal.fire("Errors!", response.responseJSON.message, "error");
                    }
                });
            });

            $('#sentiment_table').DataTable({
                processing: true,
                responsive: false,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ route('emoji.analyze.data') }}",
                    type: 'GET',
                    cache: false,
                    data: function(d) {

                    }
                },
                columns: [{
                        // title: 'SL',
                        data: 'id',
                        name: 'id'
                    },
                    {
                        // title: 'degree_title',
                        data: 'input_text',
                        name: 'input_text'
                    },
                    {
                        // title: 'degree_title',
                        data: 'sentiment_score',
                        name: 'sentiment_score'
                    },
                    {
                        // title: 'Action',
                        data: 'action',
                        name: 'action'
                    }
                ]
            });

            $(document).on('click', '.tableEdit', function() {
                let Id = $(this).data('id');
                $('#hidden-id').removeAttr("disabled");
                $('#hidden-id').val(Id);
                $.ajax({
                    data: {
                        "id": Id
                    },
                    dataType: 'json',
                    method: 'GET',
                    url: "{{ route('emoji.analyze.edit') }}",
                    success: function(responseText) {
                        $('input[name^="text_input"]').val(responseText.data.input_text);
                        $('input[name^="hidden_id"]').val(responseText.data.id);
                    }
                });
            });

            $(document).on('click', '.tableDelete', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        let Id = $(this).data('id');
                        $.ajax({
                            data: {
                                "delete": Id
                            },
                            method: 'POST',
                            dataType: 'json',
                            url: "{{ route('emoji.analyze') }}",
                            success: function(responseText) {
                                // formSuccess(responseText, statusText, xhr, $form);
                                swal.fire("Congratulations!", responseText.message,
                                    "success");
                                $('#sentiment_table').DataTable().draw(true);
                            }
                        });
                        // swal("Congratulations!", responseText.message, "success");
                    } else {
                        swal.fire("Congratulations!", "Your imaginary file is safe!", "success");
                    }
                });

            });

        });
    </script>
</body>

</html>
