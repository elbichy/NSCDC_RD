<?php

namespace Database\Seeders;

use App\Models\FormationUser;
use App\Models\Nok;
use App\Models\Progression;
use App\Models\Qualification;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $formation_user1 = array(
            array('id' => '1','formation_id' => '1','user_id' => '1','command' => 'National Headquarters','department' => 'DCG Admin office','designation' => 'Secretary','from' => '2019-08-21','to' => '2021-03-14','created_at' => '2021-03-13 19:45:21','updated_at' => '2021-03-14 07:21:32'),
            array('id' => '2','formation_id' => '1','user_id' => '1','command' => 'National Headquarters','department' => 'Public Relations','designation' => 'Secretary II','from' => '2016-02-08','to' => '2017-05-15','created_at' => '2021-03-14 07:32:03','updated_at' => '2021-03-14 07:32:03'),
            array('id' => '3','formation_id' => '1','user_id' => '1','command' => 'National Headquarters','department' => 'ICT Unit','designation' => 'Web Developer','from' => '2017-05-15','to' => '2019-08-21','created_at' => '2021-03-14 07:33:40','updated_at' => '2021-03-14 07:33:40')
        );
        $formation_user2 = array(
            array('id' => '4','formation_id' => '29','user_id' => '2','command' => 'National Headquarters','department' => 'ICT','designation' => 'HOU','from' => '2015-08-21','to' => '2021-03-21','created_at' => '2021-03-13 19:45:21','updated_at' => '2021-03-14 07:21:32')
        );

        $qualifications = array(
            array('id' => '2','user_id' => '1','qualification' => 'WAEC','course' => 'WAEC','institution' => 'G.S.S Jibi, FCT Abuja','grade' => 'WAEC','year_obtained' => '2009','created_at' => '2021-03-14 09:24:57','updated_at' => '2021-03-14 09:24:57'),
            array('id' => '3','user_id' => '1','qualification' => 'FSLC','course' => 'FSLC','institution' => 'Model Pri & Sec School, Suleja Niger state','grade' => 'FSLC','year_obtained' => '1997','created_at' => '2021-03-14 09:26:00','updated_at' => '2021-03-14 09:26:00'),
            array('id' => '4','user_id' => '1','qualification' => 'B.Sc','course' => 'Geography','institution' => 'Bayero University, Kano','grade' => 'Second Class Lower','year_obtained' => '2014','created_at' => '2021-03-14 09:29:32','updated_at' => '2021-03-14 09:29:32')
        );

        $progressions = array(
            array('id' => '1','user_id' => '1','type' => 'Entry','cadre' => 'superintendent','gl' => '8','rank_full' => 'Assistant Superintendent of Corps II','rank_short' => 'ASC II','effective_date' => '2015-12-22 00:00:00','created_at' => '2021-03-14 08:53:36','updated_at' => '2021-03-14 08:53:36'),
            array('id' => '2','user_id' => '1','type' => 'advancement','cadre' => 'superintendent','gl' => '9','rank_full' => 'Assistant Superintendent of Corps I','rank_short' => 'ASC I','effective_date' => '2018-01-01 00:00:00','created_at' => '2021-03-14 08:54:21','updated_at' => '2021-03-14 08:54:21')
        );

        $noks = array(
            array('id' => '1','user_id' => '1','name' => 'Abdulwahab Abdulrazaq','relationship' => 'brother','phone' => '08116431743','created_at' => '2021-03-14 08:52:49','updated_at' => '2021-03-14 08:52:49')
        );
          

        User::insert([
            'name' => 'Suleiman Abdulrazaq',
            'username' => '66818',
            'email' => 'suleiman.bichi@gmail.com',
            'email_verified_at' => NULL,
            'password' => Hash::make('@Suleimanu1'),
            'remember_token' => NULL,
            'blood_group' => 'a+',
            'marital_status' => 'single',
            'dob' => '1992-01-27',
            'sex' => 'male',
            'soo' => '20',
            'lgoo' => '374',
            'phone_number' => '08050811702',
            'residential_address' => '08 KB Aliyu street, PDP Quarters, Suleja Niger state',
            'service_number' => 66818,
            'cadre' => 'superintendent',
            'gl' => '9',
            'step' => '4',
            'rank_full' => 'Assistant Superintendent of Corps I',
            'rank_short' => 'ASC I',
            'dofa' => '2015-12-22',
            'doc' => '2015-01-01',
            'dopa' => '2018-01-01',
            'paypoint' => 'National Headquarters',
            'salary_structure' => 'conpass',
            'bank' => 'Guaranty Trust Bank',
            'account_number' => '0259039724',
            'bvn' => '22257949312',
            'ippis_number' => '7012812',
            'nin_number' => NULL,
            'nhis_number' => '03185385',
            'nhf' => NULL,
            'pfa' => 'Premium Pension Limited',
            'pen_number' => '100781252318',
            'current_formation' => 'National Headquarters',
            'current_department' => 'N/A',
            'specialization' => 'GIS'
        ]);

        User::insert([
            'name' => 'Kabiru Ali',
            'username' => '61251',
            'email' => 'kabiruly@gmail.com',
            'email_verified_at' => NULL,
            'password' => Hash::make('@Kabiru1'),
            'remember_token' => NULL,
            'blood_group' => 'o+',
            'marital_status' => 'married',
            'dob' => '1982-05-05',
            'sex' => 'male',
            'soo' => '20',
            'lgoo' => '407',
            'phone_number' => '08060808701',
            'residential_address' => 'Tarauni, Kano',
            'service_number' => 61251,
            'cadre' => 'superintendent',
            'gl' => '9',
            'step' => '8',
            'rank_full' => 'Assistant Superintendent of Corps I',
            'rank_short' => 'ASC I',
            'dofa' => '2012-11-05',
            'doc' => '2015-01-01',
            'dopa' => '2015-01-01',
            'paypoint' => 'Kano',
            'salary_structure' => 'conpass',
            'bank' => 'First Bank',
            'account_number' => '2008771313',
            'bvn' => '22213346311',
            'ippis_number' => '7010272',
            'nin_number' => '36912935772',
            'nhis_number' => '711765',
            'nhf' => '00114820',
            'pfa' => 'FIRST GRANTEE',
            'pen_number' => '10062795920',
            'current_formation' => 'Kano',
            'current_department' => 'ICT',
            'specialization' => 'N/A'
        ]);

        $user1 = User::where('service_number', 66818)->first();
        $user2 = User::where('service_number', 61251)->first();

        foreach ($formation_user1 as $key => $deployment) {
            FormationUser::insert($deployment);
        }
        foreach ($formation_user2 as $key => $deployment) {
            FormationUser::insert($deployment);
        }

        foreach ($qualifications as $key => $qualification) {
            Qualification::insert($qualification);
        }
        
        foreach ($progressions as $key => $progression) {
            Progression::insert($progression);
        }

        foreach ($noks as $key => $nok) {
            Nok::insert($nok);
        }

        // $role = Role::create(['name' => 'super admin']);
        // $permission = Permission::create(['name' => 'all privilages']);
        // $role->givePermissionTo($permission);

        // $role2 = Role::create(['name' => 'state admin']);
        // $permission2 = Permission::create(['name' => 'create', 'name'=>'update']);
        // $role2->givePermissionTo($permission2);

        // $user1->assignRole('super admin');
        // $user2->assignRole('state admin');
    }
}
