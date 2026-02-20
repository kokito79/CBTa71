<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class SystemSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $columns = Schema::getColumnListing('system_settings');

        $lookupColumn = $this->resolveLookupColumn($columns);

        $values = [
            'value' => json_encode(['enabled' => false]),
        ];

        if (in_array('updated_by', $columns, true)) {
            $values['updated_by'] = null;
        }

        if (in_array('created_at', $columns, true)) {
            $values['created_at'] = now();
        }

        if (in_array('updated_at', $columns, true)) {
            $values['updated_at'] = now();
        }

        DB::table('system_settings')->updateOrInsert(
            [$lookupColumn => 'applicant_login_enabled'],
            $values,
        );
    }

    /**
     * @param array<int, string> $columns
     */
    private function resolveLookupColumn(array $columns): string
    {
        foreach (['key', 'name', 'setting_key'] as $candidate) {
            if (in_array($candidate, $columns, true)) {
                return $candidate;
            }
        }

        throw new RuntimeException(
            'Unable to seed system_settings: expected one of [key, name, setting_key]. Found columns: '
            . implode(', ', $columns),
        );
    }
}
