<?php

namespace App\Console\Commands\Users;

use App\Models\User;
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
     * The User attributes for the new User.
     */
    protected array $attributes = [
        'email' => null,
        'is_admin' => false,
    ];

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->components->info($this->description);
        $this->determineEmail();
        $this->determineIsAdminState();
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

        $this->components->info(
            sprintf('The user %s was created with the password %s', $this->attributes['email'], $password)
        );
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
            ['email' => 'required|email:rfc,dns|unique:users|max:255']
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            $this->determineEmail();
        }
    }
}
