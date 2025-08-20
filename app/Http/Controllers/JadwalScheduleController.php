<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class JadwalScheduleController extends Controller
{
    public function getSchedule(Request $request): JsonResponse
    {
        $query = Jadwal::with(['guru', 'guru.mapel', 'mapel', 'kelas', 'tahunAjaran']);

        // Filter by tahun ajaran
        if ($request->filled('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        }

        // Filter by kelas
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $jadwals = $query->get();

        // Organize data by day and time
        $scheduleData = [
            'Senin' => [],
            'Selasa' => [],
            'Rabu' => [],
            'Kamis' => [],
            'Jumat' => [],
        ];

        // Check for schedule conflicts
        $conflicts = [];
        foreach ($jadwals as $jadwal) {
            $time = date('H:i', strtotime($jadwal->jam));
            $key = $jadwal->hari . '_' . $time . '_' . $jadwal->kelas_id;
            
            if (!isset($conflicts[$key])) {
                $conflicts[$key] = [];
            }
            $conflicts[$key][] = $jadwal;
        }

        foreach ($jadwals as $jadwal) {
            $time = date('H:i', strtotime($jadwal->jam));
            $key = $jadwal->hari . '_' . $time . '_' . $jadwal->kelas_id;
            $hasConflict = count($conflicts[$key]) > 1;
            
            $mapelName = $jadwal->mapel?->nama ?? $jadwal->guru?->mapel?->nama ?? '';

            $scheduleData[$jadwal->hari][$time] = [
                'id' => $jadwal->id,
                'mapel' => $mapelName,
                'guru' => $jadwal->guru ? $jadwal->guru->nama : 'N/A',
                'kelas' => $jadwal->kelas ? $jadwal->kelas->nama_kelas : 'N/A',
                'jam' => $time,
                'tahun_ajaran' => $jadwal->tahunAjaran ? $jadwal->tahunAjaran->nama : 'N/A',
                'has_conflict' => $hasConflict,
            ];
        }

        return response()->json($scheduleData);
    }

    public function getConflicts(Request $request): JsonResponse
    {
        $query = Jadwal::with(['guru', 'guru.mapel', 'mapel', 'kelas', 'tahunAjaran']);

        // Filter by tahun ajaran
        if ($request->filled('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        }

        // Filter by kelas
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $jadwals = $query->get();

        // Find conflicts
        $conflicts = [];
        $conflictGroups = [];

        foreach ($jadwals as $jadwal) {
            $time = date('H:i', strtotime($jadwal->jam));
            $key = $jadwal->hari . '_' . $time . '_' . $jadwal->kelas_id;
            
            if (!isset($conflictGroups[$key])) {
                $conflictGroups[$key] = [];
            }
            $conflictGroups[$key][] = $jadwal;
        }

        foreach ($conflictGroups as $key => $group) {
            if (count($group) > 1) {
                $conflicts[] = [
                    'hari' => $group[0]->hari,
                    'jam' => date('H:i', strtotime($group[0]->jam)),
                    'kelas' => $group[0]->kelas ? $group[0]->kelas->nama_kelas : 'N/A',
                    'jadwals' => collect($group)->map(function ($jadwal) {
                        $mapelName = $jadwal->mapel?->nama ?? $jadwal->guru?->mapel?->nama ?? '';
                        return [
                            'id' => $jadwal->id,
                            'mapel' => $mapelName,
                            'guru' => $jadwal->guru ? $jadwal->guru->nama : 'N/A',
                        ];
                    })->toArray()
                ];
            }
        }

        return response()->json($conflicts);
    }

    public function destroy(int $id): JsonResponse
    {
        // Role-based authorization to avoid policy signature mismatch
        $user = Auth::user();
        if (!$user || !$user->hasAnyRole(['Admin', 'Super_Admin'])) {
            abort(403);
        }

        $jadwal = \App\Models\jadwal::findOrFail($id);
        $jadwal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dihapus.'
        ]);
    }
}
