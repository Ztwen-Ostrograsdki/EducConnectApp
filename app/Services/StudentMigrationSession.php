<?php

namespace App\Services;


class StudentMigrationSession
{
    const KEY = 'student_migration_draft';

    public static function get(): array
    {
        return session(self::KEY, [
            'classe_id'  => null,
            'students'   => [], // [{ id, matricule, educMaster, name, prenames, conflict, conflict_classe }]
        ]);
    }

    public static function set(array $data): void
    {
        session([self::KEY => $data]);
    }

    public static function clear(): void
    {
        session()->forget(self::KEY);
    }

    public static function addStudents(array $newStudents): void
    {
        $draft = self::get();
        $existingIds = array_column($draft['students'], 'id');

        foreach ($newStudents as $s) {
            if (!in_array($s['id'], $existingIds)) {
                $draft['students'][] = $s;
            }
        }

        self::set($draft);
    }

    public static function removeStudent(int $studentId): void
    {
        $draft = self::get();
        $draft['students'] = array_values(
            array_filter($draft['students'], fn($s) => $s['id'] !== $studentId)
        );
        self::set($draft);
    }

    public static function setClasse(int $classeId): void
    {
        $draft = self::get();
        $draft['classe_id'] = $classeId;
        self::set($draft);
    }
}