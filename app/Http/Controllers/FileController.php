<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\File;
use Illuminate\Http\Request;
// use Alert;
use RealRashid\SweetAlert\Facades\Alert;

use App\Models\Document;
use App\Models\Rank;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class FileController extends Controller
{

    // CONSTRUCTOR
    public function __construct()
    {
        $this->middleware('auth');
    }


    // SHOW ALL FILES
    public function index()
    {
        return view('dashboard.file.personnel');
    }

    public function get_personnel(){
        $file = File::get();
        return DataTables::of($file)
            ->editColumn('name', function ($file) {
                return "<b><a href=\"/dashboard/files/personnel/$file->id\">$file->name</a></b>";
            })
            ->editColumn('passport', function ($file) {
                if($file->passport == NULL){

                    return "<a href=\"/dashboard/files/personnel/$file->id\">
                        <img src=".asset('storage/avaterMale.jpg')." alt=\"Profile Pic\" width=\"20%\">
                    </a>";

                }else{
                    return "<a href=\"/dashboard/files/personnel/$file->id\">
                        <img src=".asset('storage/files/'.$file->file_number.'/passport/'.$file->passport)." alt=\"Profile Pic\" width=\"20%\">
                    </a>";
                }
                
            })
            ->addColumn('checkbox', function($redeployment) {
                return '<input type="checkbox" name="fileCheckbox[]" class="fileCheckbox browser-default" value="'.$redeployment->id.'" />';
            })
            ->rawColumns(['passport', 'name', 'checkbox'])
            ->make();
    }
    
    // CREATE NEW FILE
    public function create()
    {
        return view('dashboard.file.new');
    }

    // STORE NEW FILE
    public function store(Request $request)
    {

        $validation = $request->validate([
            'name' => 'required|string',
            'file_number' => 'required|numeric',
            'type' => 'required|string',
            
        ]);

        $image_name = NULL;
        if($request->has('passport')){

            $val = $request->validate([
                'passport' => 'required|image|mimes:jpeg,png,jpg,|max:800',
            ]);

            $file = $request->file('passport');
            $image = $file->getClientOriginalName();
            $ext = pathinfo($image, PATHINFO_EXTENSION);
            $image_name = $request->file_number.'.'.$ext;
            $file->storeAs('public/files/'.$request->file_number.'/passport/', $image_name);
            // $image->storeAs('public/documents/'.$request->service_number.'/passport/', $image->getClientOriginalName());
        }
        
        $files = File::create([
            'name' => $request->name,
            'file_number' => $request->file_number,
            'type' => $request->type,
            'passport' => $image_name
        ]);

        

        if($files){
            if($request->has('file')){
                $images = $request->file('file');
                foreach($images as $image)
                {
                    $file_name = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    $image->storeAs('public/files/'.$files->file_number.'/', $image->getClientOriginalName());

                    $upload = File::find($files->id)->documents()->create([
                        'title' => $file_name,
                        'file_name' => $image->getClientOriginalName()
                    ]);
                }
            }
    
            Alert::success('File record added successfully!', 'Success!')->autoclose(2500);
            return redirect()->route('file_create');
        }

    }


    // SHOW SPECIFIC FILE
    public function show(File $file)
    {   
        $file = File::with('documents')->first();
        return view('dashboard.file.show', compact(['file']));
    }

    // UPLOAD A FILE(S)
    public function upload_file(Request $request, File $file)
    {

        if($request->has('file')){
            $images = $request->file('file');
            foreach($images as $image)
            {
                $file_name = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $image->storeAs('public/files/'.$file->file_number.'/', $image->getClientOriginalName());

                $upload = $file->documents()->create([
                    'title' => $file_name,
                    'file_name' => $image->getClientOriginalName()
                ]);
            }
        }

        Alert::success('Document(s) uploaded successfully!', 'Success!')->autoclose(2500);
        return redirect()->route('file_show', $file->id);
    }

    // EDIT A FILE
    public function edit($file)
    {
        $file = File::find($file);
        return view('dashboard.file.edit',  compact(['file']));
    }

    
    // UPDATE A FILE RECORD
    public function update(Request $request, File $file)
    {

        $validation = $request->validate([
            'name' => 'required|string',
            'file_number' => 'required|numeric',
            'type' => 'required|string',
            
        ]);
        
        $image_name = $file->passport;
        if($request->has('passport')){

            $val = $request->validate([
                'passport' => 'required|image|mimes:jpeg,png,jpg,|max:800',
            ]);

            $passport = $request->file('passport');
            $image = $passport->getClientOriginalName();
            $ext = pathinfo($image, PATHINFO_EXTENSION);
            $image_name = $request->file_number.'.'.$ext;
            $passport->storeAs('public/files/'.$request->file_number.'/passport/', $image_name);
            // $image->storeAs('public/documents/'.$request->service_number.'/passport/', $image->getClientOriginalName());
        }

        $update_file = $file->update([
            'name' => $request->name,
            'file_number' => $request->file_number,
            'type' => $request->type,
            'passport' =>  $image_name
        ]);
        
        Alert::success('File record updated successfully!', 'Success!')->autoclose(2500);
        return redirect()->route('file_show', $file->id);
    }

    // DELETE FILE RECORD
    public function destroy(Request $request, File $file)
    {
        Storage::deleteDirectory('public/files/'.$file->file_number);
        $file->delete();
        Alert::success('File record deleted successfully!', 'Success!')->autoclose(2500);
        return redirect()->route('file_personnel');
    }

    // DELETE FILE DOCUMENT
    public function destroyDocument($id)
    {
        $document = Document::where('id', $id)->with('file')->first();
        $arr = explode('/', $document->file_name);
        $file = end($arr);
        Storage::delete('public/files/'.$document->file->file_number.'/'.$file);
        $document->delete();
        Alert::success('Document deleted successfully!', 'Success!')->autoclose(2500);
        return redirect()->back();
    }

}
