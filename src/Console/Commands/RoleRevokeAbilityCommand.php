<?php

/*
 * NOTICE OF LICENSE
 *
 * Part of the Rinvex Fort Package.
 *
 * This source file is subject to The MIT License (MIT)
 * that is bundled with this package in the LICENSE file.
 *
 * Package: Rinvex Fort Package
 * License: The MIT License (MIT)
 * Link:    https://rinvex.com
 */

namespace Rinvex\Fort\Console\Commands;

use Rinvex\Fort\Models\Role;
use Rinvex\Fort\Models\Ability;
use Illuminate\Console\Command;

class RoleRevokeAbilityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fort:role:revokeability
                            {role? : The role identifier}
                            {ability? : The ability identifier}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revoke a ability from a role.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $roleField = $this->argument('role') ?: $this->ask(trans('rinvex.fort::artisan.role.identifier'));

        if (intval($roleField)) {
            $role = Role::find($roleField);
        } else {
            $role = Role::where(['slug' => $roleField])->first();
        }

        if (! $role) {
            return $this->error(trans('rinvex.fort::artisan.role.invalid', ['field' => $roleField]));
        }

        $abilityField = $this->argument('ability') ?: $this->anticipate(trans('rinvex.fort::artisan.role.ability'), Ability::all()->pluck('slug', 'id')->toArray());

        if (intval($abilityField)) {
            $ability = Ability::find($abilityField);
        } else {
            $ability = Ability::where(['slug' => $abilityField])->first();
        }

        if (! $ability) {
            return $this->error(trans('rinvex.fort::artisan.ability.invalid', ['field' => $abilityField]));
        }

        // Revoke role ability to..
        $role->revokeAbilities($ability);

        $this->info(trans('rinvex.fort::artisan.role.abilityrevoked', ['role' => $role->id, 'ability' => $ability->id]));
    }
}
