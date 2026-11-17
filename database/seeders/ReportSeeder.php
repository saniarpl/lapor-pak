<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Report;
use App\Models\ReportHistory;
use App\Models\Urgency;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();
        $workers = Worker::orderBy('id')->get()->values();

        $P = Report::STATUS_PENDING;
        $A = Report::STATUS_ACCEPTED;
        $G = Report::STATUS_PROGRESS;
        $D = Report::STATUS_DONE;
        $R = Report::STATUS_REJECTED;

        // [tiket, siswa, kategori, urgensi, indeks petugas|null, lokasi, deskripsi, status, alasan tolak, catatan perbaikan, [riwayat: [lama, baru, oleh admin?, catatan, hari lalu]]]
        $samples = [
            ['LK-SEED-0001', 'siswa1', 'Kerusakan Kursi', 'Sedang', null, 'Ruang X IPA 1',
                'Dua kursi di barisan belakang patah pada bagian kaki.', $P, null, null,
                [[null, $P, false, 'Laporan dibuat oleh siswa.', 1]]],
            ['LK-SEED-0002', 'siswa2', 'Kerusakan Lampu', 'Tinggi', 0, 'Ruang X IPS 2',
                'Tiga lampu di tengah ruangan mati sehingga kelas gelap.', $A, null, null,
                [[null, $P, false, 'Laporan dibuat oleh siswa.', 3],
                 [$P, $A, true, 'Laporan terverifikasi, akan dikerjakan petugas listrik.', 2]]],
            ['LK-SEED-0003', 'siswa3', 'Kerusakan Toilet', 'Darurat', 1, 'Toilet siswa lantai 2',
                'Keran patah dan air menggenang di lantai.', $G, null, null,
                [[null, $P, false, 'Laporan dibuat oleh siswa.', 5],
                 [$P, $A, true, 'Diterima dan dinilai darurat.', 4],
                 [$A, $G, true, 'Petugas sedang memperbaiki saluran air.', 3]]],
            ['LK-SEED-0004', 'siswa4', 'Kerusakan Meja', 'Rendah', 2, 'Perpustakaan',
                'Permukaan meja baca terkelupas.', $D, null, 'Permukaan meja diganti dan diamplas ulang.',
                [[null, $P, false, 'Laporan dibuat oleh siswa.', 10],
                 [$P, $A, true, 'Laporan diterima.', 9],
                 [$A, $G, true, 'Perbaikan dimulai.', 8],
                 [$G, $D, true, 'Perbaikan selesai.', 6]]],
            ['LK-SEED-0005', 'siswa5', 'Kerusakan Komputer', 'Sedang', null, 'Lab Komputer 1',
                'Komputer nomor 12 tidak mau menyala.', $R, 'Perangkat sudah terdaftar untuk penggantian oleh bagian pengadaan.', null,
                [[null, $P, false, 'Laporan dibuat oleh siswa.', 7],
                 [$P, $R, true, 'Perangkat sudah terdaftar untuk penggantian oleh bagian pengadaan.', 6]]],
            ['LK-SEED-0006', 'siswa1', 'Kerusakan Atap', 'Tinggi', null, 'Koridor lantai 1',
                'Atap koridor bocor saat hujan dan lantai licin.', $P, null, null,
                [[null, $P, false, 'Laporan dibuat oleh siswa.', 0]]],
        ];

        foreach ($samples as [$ticket, $username, $category, $urgency, $workerIndex, $location, $description, $status, $reason, $repairNote, $histories]) {
            $student = User::where('username', $username)->firstOrFail();

            $report = Report::updateOrCreate(
                ['ticket_number' => $ticket],
                [
                    'user_id' => $student->id,
                    'category_id' => Category::where('name', $category)->firstOrFail()->id,
                    'urgency_id' => Urgency::where('name', $urgency)->firstOrFail()->id,
                    'worker_id' => $workerIndex !== null ? optional($workers->get($workerIndex))->id : null,
                    'location' => $location,
                    'description' => $description,
                    'photo' => null,
                    'status' => $status,
                    'rejection_reason' => $reason,
                    'repair_note' => $repairNote,
                ]
            );

            foreach ($histories as [$old, $new, $byAdmin, $note, $daysAgo]) {
                ReportHistory::firstOrCreate(
                    ['report_id' => $report->id, 'old_status' => $old, 'new_status' => $new],
                    [
                        'admin_id' => $byAdmin ? $admin->id : null,
                        'note' => $note,
                        'created_at' => now()->subDays($daysAgo),
                    ]
                );
            }
        }
    }
}