<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\File;
use Jenssegers\Mongodb\Eloquent\Model;

class MakeMongoModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:mongo {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new mongo Model';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $file = '<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class ' . $this->argument('name') . ' extends Model
{
    use HasFactory;
    protected $collection = "' . strtolower($this->argument('name')) . 's";
}';
        File::put("App\Models\\" . $this->argument('name') . ".php", $file);
    }
}
