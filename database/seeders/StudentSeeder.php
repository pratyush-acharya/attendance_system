<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = [
            ['roll_no'=>'801','name'=>'Aabishkar Pandey','status'=>'active','batch_id'=>'1'],
            ['roll_no'=>'802','name'=>'Aakash Bhandari','status'=>'active','batch_id'=>'1'],
            ['roll_no'=>'803','name'=>'Aashish Tamang','status'=>'active','batch_id'=>'1'],
            ['roll_no'=>'804','name'=>'Aahishma Khanal','status'=>'active','batch_id'=>'1'],
            ['roll_no'=>'805','name'=>'Aishworya Sapkota','status'=>'active','batch_id'=>'1'],
            ['roll_no'=>'806','name'=>'Ayushree Sapkota','status'=>'active','batch_id'=>'1'],
            ['roll_no'=>'807','name'=>'Simran Parajuli','status'=>'active','batch_id'=>'1'],
            ['roll_no'=>'808','name'=>'Dashyta Paudel','status'=>'active','batch_id'=>'1'],
            ['roll_no'=>'809','name'=>'David Paudel','status'=>'active','batch_id'=>'1'],
            ['roll_no'=>'810','name'=>'Vikrant Thapa','status'=>'active','batch_id'=>'1'],
            
            ['roll_no'=>'811','name'=>'Rahul','status'=>'active','batch_id'=>'2'],
            ['roll_no'=>'812','name'=>'Rajesh','status'=>'active','batch_id'=>'2'],
            ['roll_no'=>'813','name'=>'Ramesh','status'=>'active','batch_id'=>'2'],
            ['roll_no'=>'814','name'=>'Rita','status'=>'active','batch_id'=>'2'],
            ['roll_no'=>'815','name'=>'Sita','status'=>'active','batch_id'=>'2'],
            ['roll_no'=>'816','name'=>'Gita','status'=>'active','batch_id'=>'2'],
            ['roll_no'=>'817','name'=>'Supriya','status'=>'active','batch_id'=>'2'],
            ['roll_no'=>'818','name'=>'Sagar','status'=>'active','batch_id'=>'2'],
            ['roll_no'=>'819','name'=>'Brihat','status'=>'active','batch_id'=>'2'],
            ['roll_no'=>'820','name'=>'Pratyatna','status'=>'active','batch_id'=>'2'],
            
            ['roll_no'=>'821','name'=>'Sita Rai','status'=>'active','batch_id'=>'3'],
            ['roll_no'=>'822','name'=>'Shyam','status'=>'active','batch_id'=>'3'],
            ['roll_no'=>'823','name'=>'Denisha','status'=>'active','batch_id'=>'3'],
            ['roll_no'=>'824','name'=>'Deena','status'=>'active','batch_id'=>'3'],
            ['roll_no'=>'825','name'=>'Pradeepti','status'=>'active','batch_id'=>'3'],
            ['roll_no'=>'826','name'=>'Pradip','status'=>'active','batch_id'=>'3'],
            ['roll_no'=>'827','name'=>'Satyadeep','status'=>'active','batch_id'=>'3'],
            ['roll_no'=>'828','name'=>'Avinawa','status'=>'active','batch_id'=>'3'],
            ['roll_no'=>'829','name'=>'Sulav','status'=>'active','batch_id'=>'3'],
            ['roll_no'=>'830','name'=>'Aayam','status'=>'active','batch_id'=>'3'],
            
            ['roll_no'=>'831','name'=>'Suyog','status'=>'active','batch_id'=>'4'],
            ['roll_no'=>'832','name'=>'Kristina','status'=>'active','batch_id'=>'4'],
            ['roll_no'=>'833','name'=>'Arpan','status'=>'active','batch_id'=>'4'],
            ['roll_no'=>'834','name'=>'Brihat','status'=>'active','batch_id'=>'4'],
            ['roll_no'=>'835','name'=>'Abhay','status'=>'active','batch_id'=>'4'],
            ['roll_no'=>'836','name'=>'Pratysuh','status'=>'active','batch_id'=>'4'],
            ['roll_no'=>'837','name'=>'Sudipta','status'=>'active','batch_id'=>'4'],
            ['roll_no'=>'838','name'=>'hHari','status'=>'active','batch_id'=>'4'],
            ['roll_no'=>'839','name'=>'Kiran','status'=>'active','batch_id'=>'4'],
            ['roll_no'=>'840','name'=>'Satya','status'=>'active','batch_id'=>'4'],
            
            ['roll_no'=>'841','name'=>'Alisha','status'=>'active','batch_id'=>'5'],
            ['roll_no'=>'842','name'=>'Hira','status'=>'active','batch_id'=>'5'],
            ['roll_no'=>'843','name'=>'Moti','status'=>'active','batch_id'=>'5'],
            ['roll_no'=>'844','name'=>'Chandra','status'=>'active','batch_id'=>'5'],
            ['roll_no'=>'845','name'=>'Surya','status'=>'active','batch_id'=>'5'],
            ['roll_no'=>'846','name'=>'Abhay','status'=>'active','batch_id'=>'5'],
            ['roll_no'=>'847','name'=>'Brihat','status'=>'active','batch_id'=>'5'],
            ['roll_no'=>'848','name'=>'Denisha','status'=>'active','batch_id'=>'5'],
            ['roll_no'=>'849','name'=>'Pari','status'=>'active','batch_id'=>'5'],
            
            ['roll_no'=>'850','name'=>'Bipashree','status'=>'active','batch_id'=>'6'],
            ['roll_no'=>'851','name'=>'Yoki','status'=>'active','batch_id'=>'6'],
            ['roll_no'=>'852','name'=>'Sagar','status'=>'active','batch_id'=>'6'],
            ['roll_no'=>'853','name'=>'Sarbagya','status'=>'active','batch_id'=>'6'],
            ['roll_no'=>'854','name'=>'Sagun','status'=>'active','batch_id'=>'6'],
            ['roll_no'=>'855','name'=>'Sarita','status'=>'active','batch_id'=>'6'],
            ['roll_no'=>'856','name'=>'Sarah','status'=>'active','batch_id'=>'6'],
            ['roll_no'=>'857','name'=>'Rita','status'=>'active','batch_id'=>'6'],
            ['roll_no'=>'858','name'=>'Samjhana','status'=>'active','batch_id'=>'6'],
            
            ['roll_no'=>'859','name'=>'Ritu','status'=>'active','batch_id'=>'7'],
            ['roll_no'=>'860','name'=>'Sani','status'=>'active','batch_id'=>'7'],
            ['roll_no'=>'861','name'=>'Survi','status'=>'active','batch_id'=>'7'],
            ['roll_no'=>'862','name'=>'Bibek','status'=>'active','batch_id'=>'7'],
            ['roll_no'=>'863','name'=>'John','status'=>'active','batch_id'=>'7'],
            ['roll_no'=>'864','name'=>'Ranju','status'=>'active','batch_id'=>'7'],
            ['roll_no'=>'865','name'=>'Sanju','status'=>'active','batch_id'=>'7'],
            ['roll_no'=>'866','name'=>'Manju','status'=>'active','batch_id'=>'7'],
            ['roll_no'=>'867','name'=>'Anju','status'=>'active','batch_id'=>'7'],
            
            ['roll_no'=>'868','name'=>'Sagar','status'=>'active','batch_id'=>'8'],
            ['roll_no'=>'869','name'=>'Pradeepti','status'=>'active','batch_id'=>'8'],
            ['roll_no'=>'870','name'=>'Pradeep','status'=>'active','batch_id'=>'8'],
            ['roll_no'=>'871','name'=>'Prakash','status'=>'active','batch_id'=>'8'],
            ['roll_no'=>'872','name'=>'Sujata','status'=>'active','batch_id'=>'8'],
            ['roll_no'=>'873','name'=>'Sameer','status'=>'active','batch_id'=>'8'],
            ['roll_no'=>'874','name'=>'Neema','status'=>'active','batch_id'=>'8'],
            ['roll_no'=>'875','name'=>'Salman','status'=>'active','batch_id'=>'8'],
            ['roll_no'=>'876','name'=>'Ameer','status'=>'active','batch_id'=>'8'],
            
            // ['roll_no'=>'877','name'=>'','status'=>'active','batch_id'=>'9'],
            // ['roll_no'=>'878','name'=>'Sudipta','status'=>'active','batch_id'=>'9'],
            // ['roll_no'=>'879','name'=>'Sudipta','status'=>'active','batch_id'=>'9'],
            // ['roll_no'=>'880','name'=>'Sudipta','status'=>'active','batch_id'=>'9'],
            // ['roll_no'=>'881','name'=>'Sudipta','status'=>'active','batch_id'=>'9'],
            // ['roll_no'=>'882','name'=>'Sudipta','status'=>'active','batch_id'=>'9'],
            // ['roll_no'=>'883','name'=>'Sudipta','status'=>'active','batch_id'=>'9'],
            // ['roll_no'=>'884','name'=>'Sudipta','status'=>'active','batch_id'=>'9'],
            // ['roll_no'=>'885','name'=>'Sudipta','status'=>'active','batch_id'=>'9'],
            
            // ['roll_no'=>'886','name'=>'Sudipta','status'=>'active','batch_id'=>'10'],
            // ['roll_no'=>'887','name'=>'Sudipta','status'=>'active','batch_id'=>'10'],
            // ['roll_no'=>'888','name'=>'Sudipta','status'=>'active','batch_id'=>'10'],
            // ['roll_no'=>'889','name'=>'Sudipta','status'=>'active','batch_id'=>'10'],
            // ['roll_no'=>'890','name'=>'Sudipta','status'=>'active','batch_id'=>'10'],
            // ['roll_no'=>'891','name'=>'Sudipta','status'=>'active','batch_id'=>'10'],
            // ['roll_no'=>'892','name'=>'Sudipta','status'=>'active','batch_id'=>'10'],
            // ['roll_no'=>'893','name'=>'Sudipta','status'=>'active','batch_id'=>'10'],
            // ['roll_no'=>'894','name'=>'Sudipta','status'=>'active','batch_id'=>'10'],
        ];

        foreach ($students as $student) {
            Student::create($student);
        }
    }
}
