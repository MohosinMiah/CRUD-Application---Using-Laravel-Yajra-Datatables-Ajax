
            <html>
                <head>
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title>Ajax CRUD Application Using Yajra Datatables and Laravel 7
                </title>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
                <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
                <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
                <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
                </head>
                <body>
                <div class="container">
                    <br />
                    <h1 align="center">Ajax CRUD Application Using <b>Yajra Datatables</b> and </b>Laravel 7 </b></h1>
                    <br />
                    <div align="right">
                    <button type="button" name="create_record" id="create_record" class="btn btn-success btn-sm">Create Record</button>
                    </div>
                    <br />
                <div class="table-responsive">
                    {{-- List Of Data  --}}
                <table id="user_table" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th width="5%"> ID</th>
                        <th width="20%"> Name</th>
                        <th width="20%"> URL</th>
                        <th width="5%">Rating Name</th>
                        <th width="30%">Descriptions</th>
                    <th width="20%">Action</th>
                    </tr>
                    </thead>
                </table>
                </div>
                <br />
                <br />
                </div>
                </body>
            </html>
  {{-- Modal Form  --}}
            <div id="formModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add New Record</h4>
                    </div>
                    <div class="modal-body">
                        <span id="form_result"></span>
                        <form method="post" id="sample_form" class="form-horizontal">
                        @csrf
                        <div class="form-group">
                        <label class="control-label col-md-4" >Channel Name : </label>
                        <div class="col-md-8">
                            <input type="text" name="name" id="name" class="form-control" />
                        </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">URL : </label>
                            <div class="col-md-8">
                            <input type="text" name="url" id="url" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Rating : </label>
                            <div class="col-md-8">
                            <input type="number" name="rating" id="rating" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Descriptrion : </label>
                            <div class="col-md-8">
                            <input type="text" name="description" id="description" class="form-control" />
                            </div>
                        </div>
                                <br />
                            <div class="form-group" align="center">
                                <input type="hidden" name="action" id="action" value="Add" />
                                <input type="hidden" name="hidden_id" id="hidden_id" />
                                <input type="submit" name="action_button" id="action_button" class="btn btn-warning" value="Add" />
                            </div>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
      {{-- Delete Confirm Form  --}}
            <div id="confirmModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h2 class="modal-title">Confirmation</h2>
                        </div>
                        <div class="modal-body">
                            <h4 align="center" style="margin:0;">Are you sure you want to remove this data?</h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">OK</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function(){

          //   *************************          Data Table Initial Setup   Start  ***********************************
                $('#user_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                url: "{{ route('channel.index') }}",
                },
                columns: [
                    {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'url',
                    name: 'url'
                },
                {
                    data: 'rating',
                    name: 'rating'
                },
                    {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
                ]
                });
// ---------------------------- *********   DataTable Initial Setup End *******************-------------------------------------*********************************

//  **** Open Form For Add New ******************************************
$('#create_record').click(function(){
            $('.modal-title').text('Add New Record');
            $('#action_button').val('Add');
            $('#action').val('Add');
            $('#form_result').html('');

            $('#formModal').modal('show');
            });


 //  ***********************  Create or Update Data   *********

            $('#sample_form').on('submit', function(event){
            event.preventDefault();
            var action_url = '';
//  ****  Checking Action Type for Add New  ******
            if($('#action').val() == 'Add')
            {
            action_url = "{{ route('channel.store') }}";
            }
//  ****  Checking Action Type for Update/Edit   **********

            if($('#action').val() == 'Edit')
            {
            action_url = "{{ route('channel.update') }}";
            }

            $.ajax({
            url: action_url,
            method:"POST",
            data:$(this).serialize(),
            dataType:"json",
            success:function(data)
            {
                var html = '';
                if(data.errors)
                {
                html = '<div class="alert alert-danger">';
                for(var count = 0; count < data.errors.length; count++)
                {
                html += '<p>' + data.errors[count] + '</p>';
                }
                html += '</div>';
                }
                if(data.success)
                {
                html = '<div class="alert alert-success">' + data.success + '</div>';
                $('#sample_form')[0].reset();
                $('#user_table').DataTable().ajax.reload();
                }
                $('#form_result').html(html);
            }
            });
            });
    // ********************   Edit Form Display Data and Update  baed on Id  **********************************
                        $(document).on('click', '.edit', function(){
                var id = $(this).attr('id');
                $('#form_result').html('');
                $.ajax({
                url :"/channel/"+id+"/edit",
                dataType:"json",
                success:function(data)
                {
                    $('#name').val(data.result.name);
                    $('#url').val(data.result.url);
                    $('#rating').val(data.result.rating);
                    $('#description').val(data.result.description);

                    $('#hidden_id').val(id);
                    $('.modal-title').text('Edit Record');
                    $('#action_button').val('Edit');
                    $('#action').val('Edit');
                    $('#formModal').modal('show');
                }
                })
                });

            var user_id;
            // *********************   Delete Data  based on Id **************************

            $(document).on('click', '.delete', function(){
            user_id = $(this).attr('id');
            $('#confirmModal').modal('show');
            $('.modal-title').text('Delete Record');

            });

            $('#ok_button').click(function(){
            $.ajax({
            url:"/channel/destroy/"+user_id,
            beforeSend:function(){
                $('#ok_button').text('Deleting .... ');
            },
            success:function(data)
            {
                setTimeout(function(){
                $('#confirmModal').modal('hide');
                $('#user_table').DataTable().ajax.reload();
                alert('Data Deleted');
                }, 2000);
            }
            });

            });

            });

                </script>


