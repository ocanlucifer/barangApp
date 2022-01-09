@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="card">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/barang') }}">Data Barang</a></li>
                <li class="breadcrumb-item active">Masuk</li>
            </ol>

            <div class="card-body table-responsive">
                <table class="table table-sm yajra-datatable table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th><center>Qty Masuk</center></th>
                            <th>Satuan</th>
                            <th>Tanggal Masuk</th>
                            <th>User Input</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/3.6.0/jquery.validate.js"></script> --}}
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>

    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script> --}}
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
    $(function () {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('barang_masuk') }}",
            columns: [
                {data: 'm_barang.kode_barang', name: 'kode_barang'},
                {data: 'm_barang.nama_barang', name: 'nama_barang'},
                {data: 'qty_masuk', name: 'qty_masuk', className: 'text-center'},
                {data: 'm_barang.satuan', name: 'satuan'},
                {data: 'tgl_masuk', name: 'tgl_masuk'},
                {data: 'm_user.name', name: 'user_input'},
            ]
        });
    });;

</script>
