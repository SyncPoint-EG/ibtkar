<?php

namespace Database\Seeders;

use App\Models\Center;
use App\Models\District;
use App\Models\Division;
use App\Models\EducationType;
use App\Models\Governorate;
use App\Models\Grade;
use App\Models\Semister;
use App\Models\Stage;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AllDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $governorate = Governorate::create([
            'name' => 'القاهرة',
        ]);
        $district = District::create([
            'name' => 'حدائق القبة',
            'governorate_id' => $governorate->id,
        ]);
        $center = Center::create([
            'name' => 'Yassin Center'
        ]);
        $stage = Stage::create([
            'name' => 'المرحلة الثانوية'
        ]);
        $grade = Grade::create([
            'stage_id' => $stage->id,
            'name' => 'الصف الاول'
        ]);
        $division = Division::create([
            'stage_id' => $stage->id,
            'grade_id' => $grade->id,
            'name' => 'علمي'
        ]);
        $student = Student::create([
            'first_name' => 'ياسين',
            'last_name' => 'احمد',
            'phone' => '01091085581',
            'password' => bcrypt('12345678'),
            'governorate_id' => $governorate->id,
            'district_id' => $district->id,
            'center_id' => $center->id,
            'stage_id' => $stage->id,
            'grade_id' => $grade->id,
            'division_id' => $division->id,
            'gender' => 'male',
            'birth_date' => '1990-01-01',
            'status' => 1,
        ]);
        $subject = Subject::create([
            'name' => 'فيزياء'
        ]);
        $educationType = EducationType::create([
            'name'=>'عام'
        ]);
        $semister = Semister::create([
            'name' => 'الترم الاول'
        ]);
    }
}
