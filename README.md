# form-object

FormObject is freely form library

How to use it?
-------------

```php

//Define Data class for Form
class FooData extends \FormObject\Data
{
    //Item's initializing value
    public $name = '';
    public $email = '';
    public function getFooSelect()
    {
       return array('itemA', 'itemB', 'itemC');
    }
}

//Define Form class
class FooForm extends \FormObject\Base
{
    public function execute()
    {
       //validate
       //filtering
       //etc...
       return true;
    }
}

$data = new FooData;
if ($_POST === array()) {
    //Views Input Form at first

    //use your template engine
    $views->context = $data;
    return $views;
}else {
   //Submitted Form

   //pickup and overwrite values
   $data->bind($_POST);
}

//Form depends FormObject\Data
$form = new FooForm($data);
$form->execute();

//use your template engine
$views->context = $form->getData();
return $views;
```

State Pattern?
------------

```php

//Define Data
class FooData extends \FormObject\Data
{
    public $state = 'input';
    public $name = '';
}

//Define State
class FooValidate extends \FormObject\StateBase
{
    protected $name = 'validate';
    public function execute()
    {
       //You can use great Validation Libraries
       //Next State Class String Or Instance
       $this->getData()->state = $this->getName();
       return 'FooConfirm';
    }
}

class FooConfirm extends \FormObject\StateBase
{
    protected $name = 'confirm';
    public function execute()
    {
       //You can use great Validation Libraries
       $this->getData()->state = $this->getName();
       return ;
    }
}

class FooFinish extends \FormObject\StateBase
{
    protected $name = 'finish';
    public function execute()
    {
       //register data
       //send mail
       //logging
       $this->getData()->state = $this->getName();
       return ;
    }
}

$data = new FooData();
$data->bind($_POST);

switch ($data->state) {
   case 'input':
   $form = new FooValidate($data);

   //automatic switch state
   $dispatcher = new FormObject\Dispatcher($form);
   $form = $dispatcher->getState();
   break;

   case 'confirm':
   $form = new FooFinish($data);
   $form->execute();
   redirect('form/finish.html');
   break;
}

$view->context = $form->getData();
return $view;
```