@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row justify-content-center">
        <div class="card">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Data Barang</li>
            </ol>

            <div class="card-body table-responsive">
                @if (session('sukses'))
                    <div class="alert alert-success" role="alert">
                        {{ session('sukses') }}
                    </div>
                @elseif (session('gagal'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('gagal') }}
                    </div>
                @endif
                @can('create')
                    <a href="#modal_new" class="btn btn-primary" data-toggle="modal">Tambah Barang</a>
                @endcan
                <a href="barang/barang_masuk" class="btn btn-success">Data Barang Masuk</a>
                <a href="barang/barang_keluar" class="btn btn-info">Data Barang Keluar</a>
                <a href="barang/mutasi" class="btn btn-primary">Mutasi Barang</a>
                <a href="barang/log_barang" class="btn btn-info">Log Barang</a>
                <br><br>
                <table class="table table-sm yajra-datatable table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th><center>Stock</center></th>
                            <th>Satuan</th>
                            <th><center>Update Stock</center></th>
                            <th><center>Action</center></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="modal_new" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/barang/create" method="post" enctype='multipart/form-data'>
                {{ csrf_field() }}
                <div class=" modal-header">
                    <h6 class="modal-title"><strong>Barang Baru</strong></h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="new_nama_barang">Nama Barang</label>
                            <div class="input-group">
                                <input type="text"
                                    class="form-control"
                                    name="new_nama_barang"
                                    autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="new_stock">Stock</label>
                            <div class="input-group">
                                <input type="number"
                                    min="0"
                                    class="form-control"
                                    name="new_stock"
                                    autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="new_satuan">Satuan Barang</label>
                            <div class="input-group">
                                <select name="new_satuan" class="form-control" required>
                                    <option value "" selected>Pilih Satuan</option>
                                    <option value="PCS">PCS</option>
                                    <option value="KARTON">KARTON</option>
                                    <option value="RENCENG">RENCENG</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal_edit" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/barang/update" method="post" enctype='multipart/form-data'>
                {{ csrf_field() }}
                <div class=" modal-header">
                    <h6 class="modal-title"><strong>Edit Barang</strong></h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="kode_barang">Kode Barang</label>
                            <div class="input-group">
                                <input id="kode_barang" type="text"
                                    class="form-control"
                                    name="kode_barang"
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="nama_barang">Nama Barang</label>
                            <div class="input-group">
                                <input id="nama_barang" type="text"
                                    class="form-control"
                                    name="nama_barang"
                                    autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="satuan">Satuan Barang</label>
                            <div class="input-group">
                                <select id="edit_satuan" name="satuan" class="form-control" required></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal_view" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class=" modal-header">
                <h6 class="modal-title"><strong>Detail Barang</strong></h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label id="view_kode_barang">Kode Barang:</label>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label id="view_nama_barang">Nama Barang:</label>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label id="view_stock_barang">Stock Barang:</label>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label id="view_create_by">Created By:</label>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label id="view_update_by">Updated By:</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal_update_stock" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/barang/update_stock" method="post" enctype='multipart/form-data'>
                {{ csrf_field() }}
                <div class=" modal-header">
                    <h6 class="modal-title"><strong id="judul">Barang Update Stock</strong></h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" id="id_barang_stock" name="id_barang">
                        <input type="hidden" id="jenis" name="jenis">
                        <div class="form-group">
                            <label id="qty" for="new_stock">Stock</label>
                            <div class="input-group">
                                <input type="number"
                                    id="jumlah"
                                    class="form-control"
                                    name="qty"
                                    autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
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
            ajax: "{{ route('barang') }}",
            columns: [
                {
                    data: 'view',
                    name: 'view',
                    orderable: true,
                    searchable: true
                },
                {data: 'nama_barang', name: 'nama_barang'},
                {data: 'stock', name: 'stock', className: 'text-center'},
                {data: 'satuan', name: 'satuan', className: 'text-center'},
                {
                    data: 'masuk_keluar',
                    name: 'masuk_keluar',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    });;

    function getBarangEdit(id) {
        var id = id;
        var _token = $('input[name="_token"]').val();
        id_barang = document.getElementById('id');
        kode_barang = document.getElementById('kode_barang');
        nama_barang = document.getElementById('nama_barang');
        $.ajax({
            url: '/barang/get_barang',
            type: 'POST',
            data: {id: id, _token: _token},
            dataType: 'json',
            success: function(res) {
                // console.log(res);
                id_barang.value = res.brg.id;
                kode_barang.value = res.brg.kode_barang;
                nama_barang.value = res.brg.nama_barang;
                // id_barang.value = res.satuan;

                var opt_satuan = '';
                var selected = '';
                for (i = 0; i < res.satuan.length; i++) {
                    if (res.satuan[i] == res.brg.satuan) {
                        selected = 'selected';
                    } else {
                        selected = '';
                    }
                    opt_satuan += '<option value="' + res.satuan[i] + '"' + selected + '>' + res
                        .satuan[i] + '</option>'
                }
                $('#edit_satuan').html(opt_satuan);
            }
        });
    }

    function getBarangView(id) {
        var id = id;
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: '/barang/get_barang',
            type: 'POST',
            data: {id: id, _token: _token},
            dataType: 'json',
            success: function(res) {
                $('#view_kode_barang').html('KODE BARANG: '+res.brg.kode_barang);
                $('#view_nama_barang').html('NAMA BARANG: '+res.brg.nama_barang);
                $('#view_stock_barang').html('STOCK: '+res.brg.stock+' '+res.brg.satuan);
                create_date = new Date(res.brg.created_at);
                update_date = new Date(res.brg.updated_at);
                $('#view_create_by').html('Created By: '+res.brg.m_user_create.name+' ('+create_date.toUTCString()+')');

                    $('#view_update_by').html((res.brg.updated_user_id !== null) ? 'Updated By: '+res.brg.m_user_update.name+' ('+update_date.toUTCString()+')' : '') ;

            }
        });
    }

    function setupUpdateStock(id,jenis) {
        var id = id;
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: '/barang/get_barang',
            type: 'POST',
            data: {id: id, _token: _token},
            dataType: 'json',
            success: function(res) {
                $('#id_barang_stock').val(res.brg.id);
                $('#jenis').val(jenis);
                if (jenis=='masuk') {
                    $('#judul').html('Update Stok: '+res.brg.kode_barang+' - '+res.brg.nama_barang);
                    $('#qty').html('Qty Masuk ('+res.brg.satuan+')');
                    document.getElementById("jumlah").max='';
                    document.getElementById("jumlah").min = 0;
                } else {
                    $('#judul').html('Update Stok: '+res.brg.kode_barang+' - '+res.brg.nama_barang);
                    $('#qty').html('Qty Keluar ('+res.brg.satuan+')');
                    document.getElementById("jumlah").max =res.brg.stock;
                    document.getElementById("jumlah").min = 0;
                }

            }
        });
    }
</script>
