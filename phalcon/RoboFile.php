<?php
require_once 'vendor/autoload.php';
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{

    // run local php server
    public function serve()
    {
        $this->taskServer(8000)
            ->dir('public')
            //->background()
            ->run();
    }

    /**
     * generate paseto authentication key and token expiration time in hours
     * example:  php robo.phar paseto --time 24
     * @param array $options
     * @return bool
     * @throws TypeError
     */
    public function paseto($options = ['time' => 12]){
        $key = (new \ParagonIE\Paseto\Keys\SymmetricKey(random_bytes(32)))->encode();
        $this->taskReplaceInFile('.env')
            ->from('PASETO_AUTH_KEY=')
            ->to('PASETO_AUTH_KEY=' . $key)
            ->run();
        $this->taskReplaceInFile('.env')
            ->from('PASETO_AUTH_EXPIRE_AFTER_HOURS=')
            ->to('PASETO_AUTH_EXPIRE_AFTER_HOURS=' . $options['time'])
            ->run();
        return true;
    }

    /**
     * create route unit in app/Units directory
     * example:  php robo.phar unit --name category --plural categories
     * @param array $options
     * @return bool
     */

    public function unit($options = ['name' => null, 'plural' => null])
    {
        if ( ! $options['name']) {
            $this->say('name argument not optional');

            return false;
        }

        $name      = ucfirst($options['name']);

        $this->taskFilesystemStack()
            ->mkdir('app/Units/' . $name)
            ->mkdir('app/Units/' . $name . '/Plugs')
            ->mkdir('app/Units/' . $name . '/Write')
            ->mkdir('app/Units/' . $name . '/Read')
            ->mkdir('app/Units/' . $name . '/Validators')
            ->run();

        if(!$options['plural']){
            $options['plural'] = $options['name'] . 's';
        }
        $plural = $options['plural'];
        $name = $options['name'];
        $upperName = ucfirst($name);
        $upperPlural = ucfirst($plural);
        $options = new stdClass();
        $options->name = $name;
        $options->plural = $plural;
        $options->upperPlural = $upperPlural;
        $options->upperName = $upperName;

        $this->createRoute($options);
        $this->createController($options);
        $this->createValidators($options);
        $this->createCreator($options);
        $this->createUpdater($options);
        $this->createDelete($options);
        $this->createShow($options);
        $this->createSearchAndPaginate($options);
        $this->createTransformer($options);
        $this->createPresenter($options);
        return true;
    }

    protected function createSearchAndPaginate($options){
        $path = 'app/Units/' . $options->upperName . '/Read/' . 'SearchAndPaginate' . $options->upperPlural . '.php';

        $this->taskFilesystemStack()
            ->touch($path)
            ->run();

        $this->taskWriteToFile($path)
            ->textFromFile('robo-templates/SearchAndPaginate.php')
            ->place('upperName', $options->upperName)
            ->place('name', $options->name)
            ->place('plural', $options->plural)
            ->place('upperPlural', $options->upperPlural)
            ->run();
    }

    protected function createShow($options){
        $path = 'app/Units/' . $options->upperName . '/Read/' . 'Show' . $options->upperName . '.php';

        $this->taskFilesystemStack()
            ->touch($path)
            ->run();

        $this->taskWriteToFile($path)
            ->textFromFile('robo-templates/Show.php')
            ->place('upperName', $options->upperName)
            ->place('name', $options->name)
            ->place('plural', $options->plural)
            ->place('upperPlural', $options->upperPlural)
            ->run();
    }

    protected function createDelete($options){
        $path = 'app/Units/' . $options->upperName . '/Write/' . 'Delete' . $options->upperName . '.php';

        $this->taskFilesystemStack()
            ->touch($path)
            ->run();

        $this->taskWriteToFile($path)
            ->textFromFile('robo-templates/Delete.php')
            ->place('upperName', $options->upperName)
            ->place('name', $options->name)
            ->place('plural', $options->plural)
            ->place('upperPlural', $options->upperPlural)
            ->run();
    }

    protected function createUpdater($options){
        $path = 'app/Units/' . $options->upperName . '/Write/' . 'Update' . $options->upperName . '.php';

        $this->taskFilesystemStack()
            ->touch($path)
            ->run();

        $this->taskWriteToFile($path)
            ->textFromFile('robo-templates/Update.php')
            ->place('upperName', $options->upperName)
            ->place('name', $options->name)
            ->place('plural', $options->plural)
            ->place('upperPlural', $options->upperPlural)
            ->run();
    }

    protected function createCreator($options){
        $path = 'app/Units/' . $options->upperName . '/Write/' . 'Create' . $options->upperName . '.php';

        $this->taskFilesystemStack()
            ->touch($path)
            ->run();

        $this->taskWriteToFile($path)
            ->textFromFile('robo-templates/Create.php')
            ->place('upperName', $options->upperName)
            ->place('name', $options->name)
            ->place('plural', $options->plural)
            ->place('upperPlural', $options->upperPlural)
            ->run();
    }

    protected function createValidators($options){

        $actions = ['Create', 'Update'];
        foreach ($actions as $action) {
            $path = 'app/Units/' . $options->upperName . '/Validators/' . 'On' . $action . $options->upperName . '.php';

            $this->taskFilesystemStack()
                ->touch($path)
                ->run();

            $this->taskWriteToFile($path)
                ->textFromFile('robo-templates/Validator.php')
                ->place('upperName', $options->upperName)
                ->place('name', $options->name)
                ->place('plural', $options->plural)
                ->place('upperPlural', $options->upperPlural)
                ->place('action', $action)
                ->run();
        }
    }

    protected function createController($options) {

        $path = 'app/Units/' . $options->upperName . '/' . $options->upperPlural . 'Controller.php';

        $this->taskFilesystemStack()
            ->touch($path)
            ->run();

        $this->taskWriteToFile($path)
            ->textFromFile('robo-templates/Controller.php')
            ->place('upperName', $options->upperName)
            ->place('name', $options->name)
            ->place('plural', $options->plural)
            ->place('upperPlural', $options->upperPlural)
            ->run();
    }

    protected function createRoute($options){

        $path = 'app/Units/' . $options->upperName . '/';
        $this->taskFilesystemStack()
            ->touch($path . 'route.php')
            ->run();

        $this->taskWriteToFile($path . 'route.php')
            ->textFromFile('robo-templates/route.php')
            ->place('upperName', $options->upperName)
            ->place('name', $options->name)
            ->place('plural', $options->plural)
            ->place('upperPlural', $options->upperPlural)
            ->run();
    }

    protected function createTransformer($options)
    {
        $path = 'app/Units/' . $options->upperName . '/' . $options->upperPlural . 'Transformer.php';

        $this->taskFilesystemStack()
            ->touch($path)
            ->run();

        $this->taskWriteToFile($path)
            ->textFromFile('robo-templates/Transformer.php')
            ->place('upperName', $options->upperName)
            ->place('name', $options->name)
            ->place('plural', $options->plural)
            ->place('upperPlural', $options->upperPlural)
            ->run();
    }

    protected function createPresenter($options)
    {
        $path = 'app/Units/' . $options->upperName . '/' . $options->upperPlural . 'Presenter.php';

        $this->taskFilesystemStack()
            ->touch($path)
            ->run();

        $this->taskWriteToFile($path)
            ->textFromFile('robo-templates/Presenter.php')
            ->place('upperName', $options->upperName)
            ->place('name', $options->name)
            ->place('plural', $options->plural)
            ->place('upperPlural', $options->upperPlural)
            ->run();
    }
}
