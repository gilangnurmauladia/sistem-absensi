<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\PerformanceReview;
use App\Models\Leave;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin
        $admin = User::create([
            'name' => 'Bayu Saputra Pamungkas',
            'email' => 'admin@sunsetbridge.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Create Employees
        $employeesData = [
            ['name' => 'Amirah Azhara Cahyani', 'position' => 'Supervisor', 'phone' => '081234567891'],
            ['name' => 'Azhiritz', 'position' => 'Barista', 'phone' => '081234567892'],
            ['name' => 'Kevin Akdio Esa A', 'position' => 'Barista', 'phone' => '081234567893'],
            ['name' => 'Yuri Ramadhani', 'position' => 'Barista', 'phone' => '081234567894'],
            ['name' => 'Heinza Seta Agnaini A', 'position' => 'Kasir', 'phone' => '081234567895'],
            ['name' => 'Friyaal Ramandha', 'position' => 'Kasir', 'phone' => '081234567896'],
            ['name' => 'Nazwa Carissa', 'position' => 'Waiters', 'phone' => '081234567897'],
            ['name' => 'Rahma Alia Zahrani', 'position' => 'Waiters', 'phone' => '081234567898'],
            ['name' => 'Herman Suhadi', 'position' => 'Kitchen', 'phone' => '081234567899'],
            ['name' => 'Fasya', 'position' => 'Kitchen', 'phone' => '081234567890'],
        ];

        $employees = [];
        $i = 1;
        foreach ($employeesData as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => strtolower(explode(' ', $data['name'])[0]) . '@sunsetbridge.id',
                'password' => Hash::make('password'),
                'role' => 'karyawan',
            ]);

            $emp = Employee::create([
                'user_id' => $user->id,
                'employee_code' => 'KRY' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name' => $data['name'],
                'position' => $data['position'],
                'phone' => $data['phone'],
                'join_date' => Carbon::now()->subMonths(rand(1, 12))->format('Y-m-d'),
                'status' => 'aktif',
            ]);
            $employees[] = $emp;
            $i++;
        }

        // 3. Create Schedules & Attendances for current month
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $daysInMonth = Carbon::now()->daysInMonth;
        $today = Carbon::today()->day;

        foreach ($employees as $emp) {
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $date = Carbon::createFromDate($currentYear, $currentMonth, $d);
                
                // Random shift
                $rand = rand(1, 10);
                $shiftType = 'pagi';
                if ($rand > 4 && $rand <= 8) $shiftType = 'siang';
                elseif ($rand > 8) $shiftType = 'libur';

                $start_time = null; $end_time = null;
                if ($shiftType == 'pagi') { $start_time = '07:00'; $end_time = '15:00'; }
                elseif ($shiftType == 'siang') { $start_time = '15:00'; $end_time = '23:00'; }

                Schedule::create([
                    'employee_id' => $emp->id,
                    'date' => $date->format('Y-m-d'),
                    'shift_type' => $shiftType,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                ]);

                // Create Attendance if date <= today and not libur
                if ($d <= $today && $shiftType != 'libur') {
                    // 80% hadir tepat waktu, 15% terlambat, 5% alpha
                    $attRand = rand(1, 100);
                    $status = 'hadir';
                    
                    if ($shiftType == 'pagi') {
                        $check_in = '06:' . str_pad(rand(45, 59), 2, '0', STR_PAD_LEFT);
                        $check_out = '15:' . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT);
                    } else {
                        $check_in = '14:' . str_pad(rand(45, 59), 2, '0', STR_PAD_LEFT);
                        $check_out = '23:' . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT);
                    }

                    if ($attRand > 80 && $attRand <= 95) {
                        $status = 'terlambat';
                        if ($shiftType == 'pagi') {
                            $check_in = '07:' . str_pad(rand(16, 45), 2, '0', STR_PAD_LEFT);
                        } else {
                            $check_in = '15:' . str_pad(rand(16, 45), 2, '0', STR_PAD_LEFT);
                        }
                    } elseif ($attRand > 95) {
                        $status = 'alpha';
                        $check_in = null;
                        $check_out = null;
                    }

                    Attendance::create([
                        'employee_id' => $emp->id,
                        'date' => $date->format('Y-m-d'),
                        'check_in' => $check_in,
                        'check_out' => ($d == $today && rand(1,10) > 5) ? null : $check_out, // Some haven't checked out today
                        'status' => $status,
                    ]);
                } elseif ($d <= $today && $shiftType == 'libur') {
                    // Libur
                    Attendance::create([
                        'employee_id' => $emp->id,
                        'date' => $date->format('Y-m-d'),
                        'status' => 'libur',
                    ]);
                }
            }
        }

        // 4. Create Performance Reviews (last month)
        $lastMonth = Carbon::now()->subMonth();
        foreach ($employees as $emp) {
            PerformanceReview::create([
                'employee_id' => $emp->id,
                'reviewed_by' => $admin->id,
                'month' => $lastMonth->month,
                'year' => $lastMonth->year,
                'punctuality' => rand(2, 3),
                'attendance' => rand(2, 3),
                'discipline' => rand(2, 3),
                'cleanliness' => rand(2, 3),
                'friendliness' => rand(2, 3),
                'notes' => 'Kinerja bulan lalu cukup baik.',
            ]);
        }

        // 5. Create some Leaves
        Leave::create([
            'employee_id' => $employees[0]->id,
            'type' => 'sakit',
            'start_date' => Carbon::today()->addDays(2)->format('Y-m-d'),
            'end_date' => Carbon::today()->addDays(3)->format('Y-m-d'),
            'reason' => 'Demam dan flu',
            'status' => 'pending',
        ]);
    }
}
