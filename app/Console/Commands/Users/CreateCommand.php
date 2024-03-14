<?php

namespace App\Console\Commands\Users;

use App\Models\User;
use App\Rules\AbilitiesRule;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:users:create')]
class CreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:users:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creat a new User resource.';

    /**
     * The User instance for the current batch.
     */
    protected ?User $user = null;

    /**
     * The User attributes for the new User.
     */
    protected array $attributes = [
        'email' => null,
        'is_admin' => false,
        'abilities' => ['*'],
    ];

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->components->info($this->description);
        $this->determineEmail();
        $this->determineIsAdminState();
        $this->determineAbilities();
        $this->finalize();
    }

    /**
     * Finalize the execution of the command.
     */
    protected function finalize(): void
    {
        $password = Str::password();
        $this->attributes['password'] = $password;
        User::forceCreate($this->attributes);

        $this->components->info('User created successfully.');
        $this->table(
            [],
            [
                ['Email', $this->attributes['email']],
                ['Password', $this->attributes['password']],
                ['Administrator', $this->attributes['is_admin'] ? 'Yes' : 'No'],
                ['Abilities', implode(', ', $this->attributes['abilities'])],
            ]
        );
    }

    /**
     * Determine the abilities for the User.
     */
    protected function determineAbilities(): void
    {
        if ($this->attributes['is_admin']) {
            $this->attributes['abilities'] = ['*'];
            $this->info('User is an administrator, all abilities will be granted.');

            return;
        }

        $this->call('app:list-grouped-abilities');

        $this->attributes['abilities'] = $this->ask(
            'Abilities (comma separated)',
            implode(',', $this->attributes['abilities'])
        );

        $this->attributes['abilities'] = array_map('trim', explode(',', $this->attributes['abilities']));

        $validator = Validator::make(
            ['abilities' => $this->attributes['abilities']],
            ['abilities' => ['nullable', 'array', new AbilitiesRule()]]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            $this->determineAbilities();
        }
    }

    /**
     * Determine if the User is an administrator.
     */
    protected function determineIsAdminState(): void
    {
        $this->attributes['is_admin'] = $this->confirm('Is this user an admin?', $this->attributes['is_admin']);
    }

    /**
     * Determine the email address for the User.
     */
    protected function determineEmail(): void
    {
        $this->attributes['email'] = $this->ask('Email address', $this->attributes['email']);

        $validator = Validator::make(
            ['email' => $this->attributes['email']],
            ['email' => [
                'required',
                'email:rfc,dns',
                $this->user ? 'unique:users,id,' . $this->user->getKey() : 'unique:users',
            ]]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            $this->determineEmail();
        }
    }
}
