<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeCrudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a controller, form and model for one keyword';

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
        //Make model
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
        $this->info('New Model '."App\Models\\" . $this->argument('name') . ".php". " is created.");

        //Form
        $file = '<?php

namespace App\Forms;

use App\Models\Classroom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class '.$this->argument('name').'Form'.' extends AbstractType
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
        $this->info('New Form '."App\Forms\\" . $this->argument('name') . "Form.php". " is created.");

        //Controller

        $file = '<?php

namespace App\Http\Controllers;


use App\Forms\\'.$this->argument('name').'Form'.';
use App\Models\\'.$this->argument('name').';
use Illuminate\Http\Request;

class '.$this->argument('name').'Controller extends CrudController
{
    protected $indexView = \'example.index\';
    protected $editView = \'crud.edit\';
    protected $rules = [
        //Insert Rules
    ];

    protected function model()
    {
        //Return model of choice
        return new '.$this->argument('name').';
    }

    public function getForm($data = []){
        return $this->createNamed(\''.strtolower($this->argument('name')).'\', '.$this->argument('name').'Form::class, $data);
    }

    public function index(){
        $view = view($this->indexView);
        //Insert models
        return $view;
    }

    public function edit($id)
    {
        $item = $this->model()->find($id);

        $view = view($this->editView);

        $view->item = $item;
        $view->form = $this->getForm($item)->createView();

        return $view;
    }

    public function update(Request $request, $id)
    {
        $item = $this->model()->find($id);

        $rules = $this->getRules($this->rules, $id);

        $this->handleForm($request, $item, $rules);

        return redirect()->route($this->indexView);
    }

    public function create(){
        $view = view($this->editView);

        $formView = $this->getForm($this->model())->createView();

        $view->form = $formView;

        return $view;
    }

    /**
     * Save a new item
     *
     * @param  Request  $request
     * @return \Response
     */
    public function store(Request $request)
    {
        $item = $this->model();

        $rules = $this->getRules($this->rules);

        $this->handleForm($request, $item, $rules);

        return redirect()->route($this->indexView);
    }

    public function destroy($id)
    {
        $item = $this->model()->find($id);
        $item->delete();

        return redirect()->route($this->indexView);
    }


}';
        File::put("App\Http\Controllers\\" . $this->argument('name') . "Controller.php", $file);
        $this->info('New Controller '."App\Http\Controllers\\" . $this->argument('name') . "Controller.php". " is created.");
    }
}
