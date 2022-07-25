<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeFormCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:form {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a symfony form';

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

namespace App\Forms;

use App\Models\Classroom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class '.$this->argument('name').' extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $options[\'data\'];
        $builder
            ->add(\'name\', TextType::class, [
                \'label\' => \'Naam\'
            ]);

            if($data->id){
            $builder->setAction("/'.$this->argument('name').'/".$data->id);
            $builder->setMethod("PUT");
        }else{
            $builder->setAction("/'.$this->argument('name').'s");
        }
    }
}';
        File::put("App\Forms\\" . $this->argument('name') . "Form.php", $file);
    }
}
