Plugins.Core
============

A base from which to build plugins with sensible defaults and modern features.

##ClassLoader

The Plugins.Core package contains a simple `MybbStuff_Core_ClassLoader` class which can be used to autoload classes following the PSR conventions, but using PEAR style class naming.

For example, see the following classes and their expected paths (after having registered the namespace as seen below):

```php
defined('MYBBSTUFF_CORE_PATH') or define('MYBBSTUFF_CORE_PATH', MYBB_ROOT . 'inc/plugins/MybbStuff/Core/');
define('SIMPLELIKES_PLUGIN_PATH', MYBB_ROOT . 'inc/plugins/MybbStuff/SimpleLikes');

require_once MYBBSTUFF_CORE_PATH . 'ClassLoader.php';

$classLoader = new MybbStuff_Core_ClassLoader();
$classLoader->registerNamespace('MybbStuff_SimpleLikes', array(SIMPLELIKES_PLUGIN_PATH));
```

```
MybbStuff_SimpleLikes_LikeManager           ====> inc/plugins/MybbStuff/SimpleLikes/LikeManager
MybbStuff_SimpleLikes_Import_LikeManager    ====> inc/plugins/MybbStuff/SimpleLikes/Import/LikeManager
```
