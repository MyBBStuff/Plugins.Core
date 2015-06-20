<?php
/**
 * @package Simple Likes
 * @author  Euan T. <euan@euantor.com>
 * @license http://opensource.org/licenses/mit-license.php MIT license
 * @version 1.4.0
 */

/**
 * MybbStuff ClassLoader to autoload classes for use in MybbStuff plugins.
 *
 * @author euant
 */
class MybbStuff_Core_ClassLoader
{
	/**
	 * An array of registered namespaces and paths.
	 *
	 * @var array
	 */
	private $registeredNamespaces = array();

	/**
	 * Register a namespace with the autoloader.
	 *
	 * @param string $nameSpace   The namespace to add (in the form
	 *                            "MybbStuff_SimpleLikes").
	 * @param string|array $paths The path to load the classes from.
	 *
	 * @throws ArgumentException Thrown if the directory $dir does not exist.
	 */
	public function registerNamespace($nameSpace = '', $paths = '')
	{
		$nameSpace = (string) $nameSpace;

		if (!is_array($paths)) {
			$paths = (string) $paths;
			$paths = array($paths);
		}

		$nameSpace = rtrim($nameSpace, '_');

		if (!isset($this->registeredNamespaces[$nameSpace])) {
			$this->registeredNamespaces[$nameSpace] = $paths;
		} else {
			$this->registeredNamespaces[$nameSpace] = array_merge(
				$this->registeredNamespaces[$nameSpace],
				$paths
			);
		}
	}

	/**
	 * Register this instance as an autoloader.
	 *
	 * @param bool $prepend Whether to prepend or append (default) the loader.
	 */
	public function register($prepend = false)
	{
		spl_autoload_register(array($this, 'loadClass'), true, $prepend);
	}

	/**
	 * Load a class.
	 *
	 * @param string $name The name of the class to load.
	 *
	 * @return boolean Whether the class was loaded successfully.
	 */
	public function loadClass($name = '')
	{
		$name = (string) $name;

		if ($file = $this->findFile($name)) {
			require_once $file;

			return true;
		}

		return false;
	}

	/**
	 * Finds the path to the file where the class is defined.
	 *
	 * @param string $class The name of the class
	 *
	 * @return string|null The path, if found
	 */
	public function findFile($class = '')
	{
		$classParts = explode('_', $class);
		$className = array_pop($classParts);

		if (count($classParts) >= 2) {
			$baseNameSpace = $classParts[0] . '_' . $classParts[1];

			if (isset($this->registeredNamespaces[$baseNameSpace])) {
				$classParts = array_slice($classParts, 2);

				foreach ($this->registeredNamespaces[$baseNameSpace] as $dir) {
					$classPath = $dir . DIRECTORY_SEPARATOR . implode(
							DIRECTORY_SEPARATOR,
							$classParts
						) . DIRECTORY_SEPARATOR . $className . '.php';

					if (file_exists($classPath)) {
						return $classPath;
					}
				}
			}
		}
	}
}
