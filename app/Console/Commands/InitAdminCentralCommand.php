<?php

namespace App\Console\Commands;

use App\Models\CentralUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InitAdminCentralCommand extends Command
{
    protected $signature = '
        central:init
        {name : Nom du user central}
    ';

    protected $description = 'Créer un utilisateur central (SaaS admin)';

    public function handle(): int
    {
        $name = trim($this->argument('name'));

        /*
        |--------------------------------------------------------------------------
        | Formatage
        |--------------------------------------------------------------------------
        */

        $slug = Str::slug($name);

        $email = Str::lower(
            str_replace('-', '', $slug)
        ) . '@central.local';

        $password = 'password';

        /*
        |--------------------------------------------------------------------------
        | Vérification
        |--------------------------------------------------------------------------
        */

        if (CentralUser::where('email', $email)->exists()) {

            $this->error('Utilisateur central existe déjà.');

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | Création user central
        |--------------------------------------------------------------------------
        */

        $user = CentralUser::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_super_admin' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Output
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info('✅ Utilisateur central créé avec succès');

        $this->table(
            ['Champ', 'Valeur'],
            [
                ['Nom', $name],
                ['Email', $email],
                ['Password', $password],
                ['Role', 'super-admin'],
            ]
        );

        return self::SUCCESS;
    }
}