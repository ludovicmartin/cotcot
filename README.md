# Cotcot
Cotcot is a Lightweight dependency injection framework for PHP developped for fun.
The core is very light (1 class and 2 interfaces only !) but it comes with many components that can be used to build web sites ant CLI scripts.

# Use it ?
```php
//Build a configuration
$config = [ /* ... the configuration... */ ];

//Build a runtime context
$context = new \cotcot\core\RuntimeContext($config);

//Get a component
$aComponent = $context->getComponent('services.myService');

//Use it
$aComponent->doSomething();
```

# How do I build a configuration ?
```php
$config = [
  'initializers' => [
      //Initializers are automaticaly "runed" on runtime context instanciation they must implément cotcot\core\Initializable interface
      'initializer1' => [
        //Initializer configuration
      ]
  ],
  'abcd => array(
    //Components are grouped into categories ("abcd" here)
    'component1' => [
        //Component configuration
    ]
  ]
]
```

# How do I build a component/initializer configuration ?
```php
'myComponent' => [
  //Class of the object to create (can implement the "cotcot\core\RuntimeContextAware" interface to make the runtime context beeing injected)
  'classname' => '\\myPackage\\MyClass',
  //Simple attributes
  'attributes' => [
    'attrib1' => 'value1',
    'attrib2' => 'value2'
  ],
  //Create a new instance on each call of "getComponent" or not ?
  'singleton' => true,
  //Components to inject
  'components' => [
    'compo1' => 'abcd.component_xxx', //Inject "component_xxx" from 'abcd' category to property "compo1"
    'compo2' => 'abcd.component_yyy', //Inject "component_yyy" from 'abcd' category to property "compo2"
    'compo3' => '&abcd.component_zzz', //Inject configuration copy of "component_zzz" from 'abcd' category to property "compo2"
    'abcd.compo4', //Inject "compo4" from 'abcd' category to property "compo4"
    'compo5' //Inject "compo5" from the same category of "myComponent" to property "compo5"
  ]
]
```

# How do I design a component class ?
```php
class MyComponent {
  
  public $attrib1; //Public property style
  
  private $attrib2; //Private property style
  public function setAttrib2($value) {
    $this->attrib2 = $value;
  }
  
  public $compo1; //Public property style
  
  private $compo2; //Private property style
  public function setCompo2($value) {
    $this->compo2 = $value;
  }
}
```
