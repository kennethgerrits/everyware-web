<?php

namespace App\Passport\src\Console;


use App\Passport\src\Passport;
use Illuminate\Console\Command;

class HashCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passport2:hash {--force : Force the operation to run without confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hash all of the existing secrets in the clients table';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (! Passport::$hashesClientSecrets) {
            $this->warn('Please enable client hashing yet in your AppServiceProvider before continuing.');

            return;
        }

        if ($this->option('force') || $this->confirm('Are you sure you want to hash all client secrets? This cannot be undone.')) {
            $model = Passport::clientModel();

            foreach ((new $model)->whereNotNull('secret')->cursor() as $client) {
                if (password_get_info($client->secret)['algo'] === PASSWORD_BCRYPT) {
                    continue;
                }

                $client->timestamps = false;

                $client->forceFill([
                    'secret' => $client->secret,
                ])->save();
            }

            $this->info('All client secrets were successfully hashed.');
        }
    }
}
