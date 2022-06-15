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
        return view('dashboard.file.all');
    }

    public function get_all(){
        $file = File::orderByRaw("FIELD(rank_full, 'Commandant General of Corps', 'Deputy Commandant General of Corps', 'Assistant Commandant General of Corps', 'Commandant of Corps', 'Deputy Commandant of Corps', 'Assistant Commandant of Corps', 'Chief Superintendent of Corps', 'Superintendent of Corps', 'Deputy Superintendent of Corps', 'Assistant Superintendent of Corps I', 'Assistant Superintendent of Corps II', 'Chief Inspector of Corps', 'Deputy Chief Inspector of Corps', 'Assistant Chief Inspector of Corps', 'Principal Inspector of Corps I', 'Principal Inspector of Corps II', 'Senior Inspector of Corps', 'Inspector of Corps', 'Assistant Inspector of Corps', 'Chief Corps Assistant', 'Senior Corps Assistant', 'Corps Assistant I', 'Corps Assistant II', 'Corps Assistant III')")->orderBy('service_number', 'ASC');
        return DataTables::of($file)
            ->editColumn('name', function ($file) {
                return "<b><a href=\"/dashboard/files/$file->id\">$file->name</a></b>";
            })
            ->addColumn('checkbox', function($redeployment) {
                return '<input type="checkbox" name="fileCheckbox[]" class="fileCheckbox browser-default" value="'.$redeployment->id.'" />';
            })
            ->rawColumns(['name', 'checkbox'])
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
        dd($request);
        // return $request;
        $validation = $request->validate([
            'name' => 'required|string',
            'file_number' => 'required|numeric',
            'type' => 'required|string',
            
        ]);
        
        $files = File::create([
            'name' => $request->name,
            'file_number' => $request->dob,
            'type' => $request->sex
        ]);

        if($files){
            if($request->has('file')){
                $images = $request->file('file');
                foreach($images as $image)
                {
                    $file_name = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    $image->storeAs('public/files/'.$files->service_number.'/', $image->getClientOriginalName());

                    $upload = File::find($files->id)->documents()->create([
                        'title' => $file_name,
                        'file' => $image->getClientOriginalName()
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
        return view('dashboard/file/show');
    }

    // UPLOAD A FILE(S)
    public function upload_file(Request $request, File $file)
    {
        
        $image_name = $file->passport;
        if($request->has('passport')){
            $val = $request->validate([
                'passport' => 'required|image|mimes:jpeg,png,jpg,|max:800',
            ]);
            $file = $request->file('passport');
            $image = $file->getClientOriginalName();
            $ext = pathinfo($image, PATHINFO_EXTENSION);
            $image_name = $file->service_number.'.'.$ext;
            $file->storeAs('public/files/'.$file->service_number.'/passport/', $image_name);

            $file = $file->update([
                'passport' => $image_name,
            ]);
        }

        if($request->has('file')){
            $images = $request->file('file');
            foreach($images as $image)
            {
                $file_name = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $image->storeAs('public/documents/'.$file->service_number.'/', $image->getClientOriginalName());

                $upload = $file->documents()->create([
                    'title' => $file_name,
                    'file' => $image->getClientOriginalName()
                ]);
            }
        }

        Alert::success('File(s) uploaded successfully!', 'Success!')->autoclose(2500);
        return redirect()->route('file_show', $file->id);
    }

    // EDIT A FILE
    public function edit($file)
    {
        return view('dashboard/file/edit');
    }

    
    // UPDATE A FILE RECORD
    public function update(Request $request, File $file)
    {

        $validation = $request->validate([
            'name' => 'required|string',
            'dob' => 'required|date',
            'soo' => 'required',
            'lgoo' => 'string',
            'cadre' => 'required|string',
            'gl' => 'required|numeric',
            'step' => 'required|numeric',
            'dofa' => 'required|date',
            'doc' => 'required|date',
            'dopa' => 'required|date'
        ]);

        $image_name = $file->passport;
        if($request->has('passport')){

            $val = $request->validate([
                'passport' => 'required|image|mimes:jpeg,png,jpg,|max:200',
            ]);

            $file = $request->file('passport');
            $image = $file->getClientOriginalName();
            $ext = pathinfo($image, PATHINFO_EXTENSION);
            $image_name = $request->service_number.'.'.$ext;
            $file->storeAs('public/documents/'.$request->service_number.'/passport/', $image_name);
        }

        $rank = Rank::where('cadre', $request->cadre)->where('gl', $request->gl)->first();
        
        // return $request;

        $file = $file->update([
            'name' => $request->name,
            'dob' => $request->dob,
            'sex' => $request->sex,
            'blood_group' => $request->blood_group,
            'marital_status' => $request->marital_status,
            'soo' => $request->soo,
            'lgoo' => $request->lgoo,
            'residential_address' => $request->residential_address,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'cadre' => $request->cadre,
            'gl' => $request->gl,
            'step' => $request->step,
            'rank_full' => $rank->full_title,
            'rank_short' => $rank->short_title,
            'dofa' => $request->dofa,
            'doc' => $request->doc,
            'dopa' => $request->dopa,
            'bank' => $request->bank,
            'account_number' => $request->account_number,
            'bvn' => $request->bvn,
            'paypoint' => $request->paypoint,
            'salary_structure' => $request->salary_structure,
            'nin_number' => $request->nin_number,
            'nhis_number' => $request->nhis_number,
            'ippis_number' => $request->ippis_number,
            'nhf' => $request->nhf,
            'pfa' => $request->pfa,
            'pen_number' => $request->pen_number,
            'specialization' => $request->specialization,
            'passport' => $image_name,
        ]);
        
        Alert::success('File record updated successfully!', 'Success!')->autoclose(2500);
        // return redirect()->route('file_show', $file->id);
    }

    // DELETE FILE RECORD
    public function destroy(Request $request)
    {
        $file = File::find($request->file);
        $file->status = $request->reason;
        $file->save();
        $file->delete();
        Alert::success('File record deleted successfully!', 'Success!')->autoclose(2500);
        return redirect()->route('file_all');
    }

    // DELETE FILE DOCUMENT
    public function destroyDocument($id)
    {
        $document = Document::where('id', $id)->with('file')->first();
        $arr = explode('/', $document->file);
        $file = end($arr);
        Storage::delete('public/files/'.$document->file->service_number.'/'.$file);
        $document->delete();
        Alert::success('Document deleted successfully!', 'Success!')->autoclose(2500);
        return redirect()->back();
    }

}
