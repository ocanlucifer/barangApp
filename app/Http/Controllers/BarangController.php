<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Illuminate\Support\Facades\Validator;
use Session;
use Yajra\Datatables\Datatables;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\LogBarang;

class BarangController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create', ['only' => ['create']]);
        $this->middleware('permission:edit', ['only' => ['update','update_stock']]);
        $this->middleware('permission:delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Barang::all();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('view', function($row){
                    $btn = '<a onclick="getBarangView('.$row->id.');" href="#modal_view" data-toggle="modal">'.$row->kode_barang.'</a>';
                    return $btn;
                })
                ->addColumn('action', function($row){
                    $btn='';
                    if (Auth::user()) {
                        if (Auth::user()->can('edit')) {
                            $btn .= '<center><a onclick="getBarangEdit('.$row->id.')" href="#modal_edit" data-toggle="modal" class="btn btn-warning btn-sm">Edit</a>';
                        }
                        if (Auth::user()->can('delete')) {
                            $btn .=' <a href="barang/delete/'.$row->id.')" onclick="return confirm(`Hapus Barang '.$row->kode_barang.' ('.$row->nama_barang.'), Lanjutkan?`);" class="btn btn-danger btn-sm">Delete</a></center>';
                        }
                    }
                    return $btn;
                })
                ->addColumn('masuk_keluar', function($row){
                    $btn='';
                    if (Auth::user()) {
                        if (Auth::user()->can('edit')) {
                            $btn = '<center><a onclick="setupUpdateStock('.$row->id.',`masuk`)" href="#modal_update_stock" data-toggle="modal" class="btn btn-success btn-sm">Masuk</a>
                                <a onclick="setupUpdateStock('.$row->id.',`keluar`)" href="#modal_update_stock" data-toggle="modal" class="btn btn-info btn-sm">Keluar</a></center>'
                                ;
                        }
                    }
                    return $btn;
                })
                ->rawColumns(['action','view','masuk_keluar'])
                ->make(true);
        }

        return view('barang.index');
    }

    public function barang_masuk(Request $request)
    {
        if ($request->ajax()) {
            $data = BarangMasuk::with('m_user')->with('m_barang')->get();
            return Datatables::of($data)->toJson();
        }

        return view('barang.barang_masuk');
    }

    public function barang_keluar(Request $request)
    {
        if ($request->ajax()) {
            $data = BarangKeluar::with('m_user')->with('m_barang')->get();
            return Datatables::of($data)->toJson();
        }

        return view('barang.barang_keluar');
    }

    public function mutasi(Request $request)
    {
        if ($request->ajax()) {
            $data = Barang::with('b_masuk')->with('b_keluar')->get();
            return Datatables::of($data)
                    ->addColumn('awal', function($row){
                        $qty = 0;
                        return $qty;
                    })
                    ->addColumn('masuk', function($row){
                        $qty = $row->b_masuk->sum('qty_masuk');
                        return $qty;
                    })
                    ->addColumn('keluar', function($row){
                        $qty = $row->b_keluar->sum('qty_keluar');
                        return $qty;
                    })
                    ->addColumn('akhir', function($row){
                        $qty = ($row->b_masuk->sum('qty_masuk') - $row->b_keluar->sum('qty_keluar'));
                        return $qty;
                    })
                    ->rawColumns(['awal','masuk','keluar','akhir'])
                    ->make(true);
        }

        return view('barang.mutasi');
    }

    public function log_barang(Request $request)
    {
        if ($request->ajax()) {
            $data = LogBarang::All();
            return Datatables::of($data)
                    ->addColumn('tgl', function($row){
                        $tgl = date("Y-m-d h:i:s", strtotime($row->created_at));
                        return $tgl;
                    })
                    ->rawColumns(['tgl'])
                    ->make(true);
        }

        return view('barang.log_barang');
    }

    public function get_barang(Request $request)
    {
        $id = $request->id;
        $result['brg'] = Barang::where('id', $id)->with('m_user_create')->with('m_user_update')->first();
        $result['satuan'] = ['PCS', 'KARTON','RENCENG'];

        return response()->json($result);
    }

    public function create(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'new_nama_barang'   => 'required',
            'new_stock'         => 'numeric',
            'new_satuan'        => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            Session::flash('gagal', 'Data Barang Gagal di Simpan, Pastikan Nama Barang, Stock dan Satuan Tidak Kosong');
            return redirect('barang');
        }

        if (!Auth::user()) {
            Session::flash('gagal', 'Anda harus login terlebuh dahulu');
            return redirect('barang');
        } else {
            $nama_barang= strtoupper($request->new_nama_barang);
            $satuan= strtoupper($request->new_satuan);
            $cekBR = Barang::where('nama_barang',$nama_barang)->where('satuan',$satuan)->count();
            if ($cekBR > 0) {
                Session::flash('gagal', 'Barang ini sudah ada');
                return redirect('barang');
            }
            $numbering_format = 'BR'. date('y');

            $latestid = Barang::orderBy('created_at','DESC')->first();;
            $kode = str_pad(($latestid) ? $latestid->id : 0 + 1, 5, "0", STR_PAD_LEFT);
            $create = new Barang;
            $create->kode_barang        = $numbering_format.''.$kode;
            $create->nama_barang        = $nama_barang;
            $create->stock              = $request->new_stock;
            $create->satuan             = $satuan;
            $create->status             = 1;
            $create->created_user_id    = Auth::user()->id;
            $create->save();

            BarangMasuk::create([
                'id_barang'     => $create->id,
                'qty_masuk'     => $request->new_stock,
                'tgl_masuk'     => date(NOW()),
                'user_id'       => Auth::user()->id
            ]);

            LogBarang::create([
                'id_barang' => $create->id,
                'kode_barang' => $numbering_format.''.$kode,
                'nama_barang' => $nama_barang,
                'qty_awal' => $request->new_stock,
                'qty_masuk' => 0,
                'qty_keluar' => 0,
                'qty_akhir' =>$request->new_stock,
                'aktifitas' => 'Berhasil menambah barang baru',
                'satuan' => $satuan,
                'user_id' =>Auth::user()->id,
                'username' =>Auth::user()->name,
            ]);
            Session::flash('sukses', 'Data Barang Berhasil Di Simpan');
            return redirect('barang');
        }
    }

    public function update(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'id'            => 'required',
            'kode_barang'   => 'required',
            'nama_barang'   => 'required',
            'satuan'        => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            Session::flash('gagal', 'Data Barang Gagal di Update, Pastikan Nama Barang dan Satuan Tidak Kosong');
            return redirect('barang');
        }

        if (!Auth::user()) {
            Session::flash('gagal', 'Anda harus login terlebuh dahulu');
            return redirect('barang');
        } else {
            $nama_barang= strtoupper($request->nama_barang);
            $satuan= strtoupper($request->satuan);
            $cekBR = Barang::where('nama_barang',$nama_barang)->where('satuan',$satuan)->count();
            if ($cekBR > 0) {
                Session::flash('gagal', 'Barang ini sudah ada');
                return redirect('barang');
            }

            $getBrg = Barang::where('id',$request->idd)->first();
            LogBarang::create([
                'id_barang' => $request->id,
                'kode_barang' => $request->kode_barang,
                'nama_barang' => $nama_barang,
                'qty_awal' => $getBrg->stock,
                'qty_masuk' => 0,
                'qty_keluar' => 0,
                'qty_akhir' => $getBrg->stock,
                'aktifitas' => 'Barang: '.$getBrg->kode_barang.' - '.$getBrg->nama_barang.' ('.$getBrg->satuan.') Terlah di ubah menjadi: '.$request->kode_barang.' - '.$nama_barang.' ('.$satuan.')',
                'satuan' => $satuan,
                'user_id' =>Auth::user()->id,
                'username' =>Auth::user()->name,
            ]);

            Barang::where('id',$request->id)->update([
                'nama_barang'        => $nama_barang,
                'satuan'            => $satuan,
                'updated_user_id'   => Auth::user()->id,
            ]);

            Session::flash('sukses', 'Data Barang Berhasil Di Update');
            return redirect('barang');
        }
    }

    public function delete($id)
    {
        $getBrg = Barang::where('id',$id)->first();
        LogBarang::create([
            'id_barang' => $getBrg->id,
            'kode_barang' => $getBrg->kode_barang,
            'nama_barang' => $getBrg->nama_barang,
            'qty_awal' => $getBrg->stock,
            'qty_masuk' => 0,
            'qty_keluar' => 0,
            'qty_akhir' => $getBrg->stock,
            'aktifitas' => 'Berhasil Menghapus Barang',
            'satuan' => $getBrg->satuan,
            'user_id' =>Auth::user()->id,
            'username' =>Auth::user()->name,
        ]);

        Barang::where('id',$id)->delete();
        BarangMasuk::where('id_barang',$id)->delete();
        BarangKeluar::where('id_barang',$id)->delete();

        Session::flash('sukses', 'Data Barang Berhasil Di Delete');
        return redirect('barang');
    }

    public function update_stock(Request $request)
    {
        $id_barang  = $request->id_barang;
        $jenis      = $request->jenis;
        $qty        = ($request->qty > 0) ? $request->qty : 0;
        $tgl        = date(NOW());
        $getBarang  = Barang::where('id',$id_barang)->first();
        $getStock   = ($getBarang) ? $getBarang->stock : 0;
        $user_id    = (Auth::user()) ? Auth::user()->id : "";
        if ($qty == 0) {
            Session::flash('gagal', 'Qty tidak boleh lebih kecil dari 0');
            return redirect('barang');
        }
        if ($jenis=='masuk') {
            $newStock = $getStock + $qty;
            BarangMasuk::create([
                'id_barang'     => $id_barang,
                'qty_masuk'     => $qty,
                'tgl_masuk'     => $tgl,
                'user_id'       => $user_id
            ]);
            Barang::where('id',$id_barang)->update([
                'stock'             => $newStock,
                'updated_user_id'   => $user_id
            ]);

            LogBarang::create([
                'id_barang' => $getBarang->id,
                'kode_barang' => $getBarang->kode_barang,
                'nama_barang' => $getBarang->nama_barang,
                'qty_awal' => $getStock,
                'qty_masuk' => $qty,
                'qty_keluar' => 0,
                'qty_akhir' => $newStock,
                'aktifitas' => 'Berhasil Menambah stock Barang',
                'satuan' => $getBarang->satuan,
                'user_id' =>Auth::user()->id,
                'username' =>Auth::user()->name,
            ]);

            Session::flash('sukses', 'Stock Barang '.$getBarang->kode_barang.' - '.$getBarang->nama_barnag.' berhasil di tambah: '.$qty.' '.$getBarang->satuan);
            return redirect('barang');
        } elseif ($jenis=='keluar') {
            $newStock = $getStock - $qty;
            if ($newStock < 0) {
                Session::flash('gagal', 'Stock tidak Cukup, anda memasukan qty keluar: '.$qty.' '.$getBarang->satuan.' sedangkan stock hanya ada: '.$getStock.' '.$getBarang->satuan);
                return redirect('barang');
            }
            BarangKeluar::create([
                'id_barang'     => $id_barang,
                'qty_keluar'     => $qty,
                'tgl_keluar'     => $tgl,
                'user_id'       => $user_id
            ]);
            Barang::where('id',$id_barang)->update([
                'stock'             => $newStock,
                'updated_user_id'   => $user_id
            ]);

            LogBarang::create([
                'id_barang' => $getBarang->id,
                'kode_barang' => $getBarang->kode_barang,
                'nama_barang' => $getBarang->nama_barang,
                'qty_awal' => $getStock,
                'qty_masuk' => 0,
                'qty_keluar' => $qty,
                'qty_akhir' => $newStock,
                'aktifitas' => 'Berhasil Mengurangi stock Barang',
                'satuan' => $getBarang->satuan,
                'user_id' =>Auth::user()->id,
                'username' =>Auth::user()->name,
            ]);
            Session::flash('sukses', 'Stock Barang '.$getBarang->kode_barang.' - '.$getBarang->nama_barnag.' berhasil di keluarkan: '.$qty.' '.$getBarang->satuan);
            return redirect('barang');
        }
    }
}
