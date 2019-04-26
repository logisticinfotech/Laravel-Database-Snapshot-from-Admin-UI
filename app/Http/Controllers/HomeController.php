<?php

namespace App\Http\Controllers;

use Artisan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Yajra\DataTables\DataTables;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function CreateDBSnapshot(Request $request)
    {
        $request->validate([
            'snapshot_name' => 'required|alpha_dash'
        ]);
        $search = ["_"];
        $replace = ["-"];

        $file_name = str_replace($search, $replace, $request->snapshot_name).'_'.Carbon::now()->format('Y-m-d.H:i:s');
        Artisan::call('snapshot:create '.$file_name);

        return redirect()->back()->with('db-snapshot-done', 'Database Snapshot Create Successful.');
    }

    public function DBSnapshotList()
    {
        return view('db-snapshot-list');
    }

    public function DBSnapshotDatatable()
    {    
        Artisan::call('snapshot:list');
        $snapshot_data = Artisan::output();
        $snapshot_data = array_filter(explode("\n", $snapshot_data));
        $snapshot_list = new Collection;
        if(count($snapshot_data) != 1) {
            foreach ($snapshot_data as $index =>$snapshot_list_row) {
                if( $snapshot_list_row[0] != "+" ) {               
                    if( $index != 1 ) {                    
                        $snapshot_list_column = array_map('trim', array_filter(explode("|", $snapshot_list_row)));
                        $file_name = $snapshot_list_column[1];
                        $name = explode("_", $snapshot_list_column[1]);
                        $date = $snapshot_list_column[2];
                        $size = $snapshot_list_column[3];
                        $snapshot_list->push([                       
                            'name'      => $name[0],
                            'date'      => $date,
                            'size'      => $size,
                            'action'    => "<a href='".route('DBSnapshotDownload', $file_name)."' class='text-info'><i class='fa fa-download'></i></a>&nbsp;&nbsp;<a href='".route('DBSnapshotDelete', $file_name)."' onclick=\"return confirm('Are you sure you want to delete this item?');\" class='text-danger'><i class='fa fa-trash'></i></a>"
                        ]);                                
                    } 
                }
            } 
        }

        return DataTables::of($snapshot_list)

        ->rawColumns(['action'])
        ->make(true);

    }

    public function DBSnapshotDownload($name)
    {
        return response()->download(storage_path("snapshots/".$name.'.sql'));
    }

    public function DBSnapshotDelete($name)
    {  
        Artisan::call('snapshot:delete '.$name);
        return redirect()->back()->with('db-snapshot-delete', 'Database Snapshot Delete Successful.');
    }
}
