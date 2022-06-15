<?php

namespace App\Http\Controllers;

use App\Candidate;
use App\Charts\FormationChart;
use App\Charts\GenderChart;
use App\Charts\MaritalStatusChart;
use App\Charts\RankChart;
use App\Conversion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use Alert;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Deployment;
use App\Models\Nok;
use App\Models\Document;
use App\Exports\UsersExport;
use App\Models\Formation;
use App\Models\Lga;
use App\Models\Promotion;
use App\Models\Qualification;
use App\Models\Rank;
use App\Models\Redeployment;
use App\Models\Region;
use App\Models\State;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\QueryBuilder;
use Spatie\QueryBuilder\QueryBuilder as QueryBuilderQueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use PDF;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PersonnelController extends Controller
{

    // CONSTRUCTOR
    public function __construct()
    {
        $this->middleware('auth');
    }


    // SHOW ALL ACTIVE PERSONNEL
    public function index()
    {
        // if(!auth()->user()->hasAnyRole(['super admin'])){
        //     abort(401, 'You can only manage staff withing your formation');
        // }
        // return $personnel = User::with('deployments', 'progressions')->get();
        return view('dashboard.personnel.all');
    }
    public function get_all(){
        $personnel = User::orderByRaw("FIELD(rank_full, 'Commandant General of Corps', 'Deputy Commandant General of Corps', 'Assistant Commandant General of Corps', 'Commandant of Corps', 'Deputy Commandant of Corps', 'Assistant Commandant of Corps', 'Chief Superintendent of Corps', 'Superintendent of Corps', 'Deputy Superintendent of Corps', 'Assistant Superintendent of Corps I', 'Assistant Superintendent of Corps II', 'Chief Inspector of Corps', 'Deputy Chief Inspector of Corps', 'Assistant Chief Inspector of Corps', 'Principal Inspector of Corps I', 'Principal Inspector of Corps II', 'Senior Inspector of Corps', 'Inspector of Corps', 'Assistant Inspector of Corps', 'Chief Corps Assistant', 'Senior Corps Assistant', 'Corps Assistant I', 'Corps Assistant II', 'Corps Assistant III')")->orderBy('service_number', 'ASC');
        return DataTables::of($personnel)
            ->editColumn('name', function ($personnel) {
                return "<b><a href=\"/dashboard/personnel/$personnel->id\">$personnel->name</a></b>";
            })
            ->addColumn('checkbox', function($redeployment) {
                return '<input type="checkbox" name="personnelCheckbox[]" class="personnelCheckbox browser-default" value="'.$redeployment->id.'" />';
            })
            ->rawColumns(['name', 'checkbox'])
            ->make();
    }


    // SHOW ALL PERSONNEL OUT OF SERVICE
    public function outofservice()
    {
        // if (!Gate::allows('isGlobalAdmin')) {
        //     abort(401);
        // }
        // return $personnel = User::with('deployments', 'progressions')->get();
        return view('administration.dashboard.personnel.outofservice');
    }
    public function get_outofservice(){
        $personnel = User::onlyTrashed("FIELD(rank_full, 'Commandant General of Corps', 'Deputy Commandant General of Corps', 'Assistant Commandant General of Corps', 'Commandant of Corps', 'Deputy Commandant of Corps', 'Assistant Commandant of Corps', 'Chief Superintendent of Corps', 'Superintendent of Corps', 'Deputy Superintendent of Corps', 'Assistant Superintendent of Corps I', 'Assistant Superintendent of Corps II', 'Chief Inspector of Corps', 'Deputy Chief Inspector of Corps', 'Assistant Chief Inspector of Corps', 'Principal Inspector of Corps I', 'Principal Inspector of Corps II', 'Senior Inspector of Corps', 'Inspector of Corps', 'Assistant Inspector of Corps', 'Chief Corps Assistant', 'Senior Corps Assistant', 'Corps Assistant I', 'Corps Assistant II', 'Corps Assistant III')")->orderBy('service_number', 'ASC');
        return DataTables::of($personnel)
            ->editColumn('name', function ($personnel) {
                return "<b><a href=\"/dashboard/personnel/$personnel->id\">$personnel->name</a></b>";
            })
            ->addColumn('checkbox', function($redeployment) {
                return '<input type="checkbox" name="personnelCheckbox[]" class="personnelCheckbox browser-default" value="'.$redeployment->id.'" />';
            })
            ->rawColumns(['name', 'checkbox'])
            ->make();
    }
    


    // CREATE NEW PERSONNEL
    public function create()
    {
        $formations = Formation::all();
        return view('dashboard.personnel.new', compact(['formations']));
    }

    // STORE NEW PERSONNEL
    public function store(Request $request)
    {
        dd($request);
        // return $request;
        $validation = $request->validate([
            'name' => 'required|string',
            'file_number' => 'required|numeric',
            'type' => 'required|string',
            
        ]);
        
        $files = User::create([
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

                    $upload = User::find($files->id)->documents()->create([
                        'title' => $file_name,
                        'file' => $image->getClientOriginalName()
                    ]);
                }
            }
    
            Alert::success('File record added successfully!', 'Success!')->autoclose(2500);
            return redirect()->route('file_create');
        }

    }

    // IMPORT NEW DATA
    public function import_data(Request $request){
        // if (!Gate::allows('isGlobalAdmin')) {
        //     abort(401);
        // }
        return view('dashboard/personnel/import');
    }

    // SHOW SPECIFIC PERSONNEL
    public function show(User $user)
    {

        $personnel = $user;
        $all_formations = Formation::all();
        $state = State::where('id', $personnel->soo)->first();
        $lga = Lga::where('id', $personnel->lgoo)->first();

        // GET TIME TILL RETIREMENT
        $max_svc_yr = 35;
        $max_age = 60;
        $dofa = Carbon::create($personnel->dofa);
        $dob = Carbon::create($personnel->dob);
        if($dofa >= Carbon::create('2004/1/1')){
            $prop_rt_yr_by_svc = $dofa->addYears($max_svc_yr);
            $prop_rt_yr_by_age = $dob->addYears($max_age);
            if($prop_rt_yr_by_age < $prop_rt_yr_by_svc){
                $ttr = $prop_rt_yr_by_age;
            }else{
                $ttr = $prop_rt_yr_by_svc;
            }
        }else{
            $ttr = 'DOFA/DOB not valid';
        }
        
        return view('dashboard/personnel/show', compact(['personnel', 'state', 'lga', 'all_formations', 'ttr']));
    }

    // UPLOAD A FILE(S)
    public function upload_file(Request $request, User $user)
    {
        
        // LOCAL FILE STORAGE GOES HERE //
        $image_name = $user->passport;
        if($request->has('passport')){
            $val = $request->validate([
                'passport' => 'required|image|mimes:jpeg,png,jpg,|max:800',
            ]);
            $file = $request->file('passport');
            $image = $file->getClientOriginalName();
            $ext = pathinfo($image, PATHINFO_EXTENSION);
            $image_name = $user->service_number.'.'.$ext;
            $file->storeAs('public/documents/'.$user->service_number.'/passport/', $image_name);

            $personnel = $user->update([
                'passport' => $image_name,
            ]);
        }

        if($request->has('file')){
            $images = $request->file('file');
            foreach($images as $image)
            {
                $file_name = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $image->storeAs('public/documents/'.$user->service_number.'/', $image->getClientOriginalName());

                $upload = $user->documents()->create([
                    'title' => $file_name,
                    'file' => $image->getClientOriginalName()
                ]);
            }
        }

        Alert::success('File(s) uploaded successfully!', 'Success!')->autoclose(2500);
        return redirect()->route('personnel_show', $user->id);
    }

    // EDIT A PERSONNEL
    public function edit($user)
    {
        $personnel = User::where('id', $user)->with(['formations' => function($query){
            $query->latest()->first();
        }])->first();

        return view('dashboard/personnel/edit', compact(['personnel']));
    }

    // EDIT PERSONNEL PASSWORD
    public function change_password(Request $request, User $user)
    {
        // if(auth()->user()->current_formation != $user->current_formation && auth()->user()->role != 'global_admin'){
        //     abort(401);
        // }
        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
        ]);
        
        if(auth()->user()->role === 'global_admin'){
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            Alert::success('Personnel password updated successfully!', 'Success!')->autoclose(2500);
        }
        else if($user->password != null){
            if(Hash::check($request->old_pass, $user->password)){
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
                Alert::success('Personnel password updated successfully!', 'Success!')->autoclose(2500);
            }else{
                Alert::error('Sorry your old password does not match with password on the database', 'Error!')->autoclose(2500);
            }
        }else{
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            Alert::success('Personnel password updated successfully!', 'Success!')->autoclose(2500);
        }
        return back();
    }
    
    // UPDATE A PERSONNEL RECORD
    public function update(Request $request, User $user)
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

        $image_name = $user->passport;
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

        $personnel = $user->update([
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
        
        Alert::success('Personnel record updated successfully!', 'Success!')->autoclose(2500);
        return redirect()->route('personnel_show', $user->id);
    }

    // DELETE PERSONNEL RECORD
    public function destroy(Request $request)
    {
        // if(auth()->user()->role != 'global_admin'){
        //     abort(401);
        // }
        $user = User::find($request->user);
        // Storage::deleteDirectory('public/documents/'.$user->service_number);
        $user->status = $request->reason;
        $user->save();
        $user->delete();
        Alert::success('Personnel record deleted successfully!', 'Success!')->autoclose(2500);
        return redirect()->route('personnel_all');
    }

    // DELETE PERSONNEL DOCUMENT
    public function destroyDocument($id)
    {
        $document = Document::where('id', $id)->with('user')->first();
        $arr = explode('/', $document->file);
        $file = end($arr);
        Storage::delete('public/documents/'.$document->user->service_number.'/'.$file);
        $document->delete();
        Alert::success('Document deleted successfully!', 'Success!')->autoclose(2500);
        return redirect()->back();
    }

}
