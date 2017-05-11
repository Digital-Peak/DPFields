<?php
/**
 * @package    CCL
* @author     Digital Peak http://www.digital-peak.com
* @copyright  Copyright (C) 2007 - 2016 Digital Peak. All rights reserved.
* @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
*/
namespace CCL\Content\Element\Basic;

use CCL\Content\Visitor\ElementVisitorInterface;
use CCL\Content\Element\Basic\Container;
use CCL\Content\Element\ElementInterface;

/**
 * An element represents a node in an HTML tree.
 */
class Element implements ElementInterface
{

	/**
	 * The id of the element
	 *
	 * @var string
	 */
	private $id = '';

	/**
	 * The classes of the element.
	 *
	 * @var array
	 */
	private $classes = array();

	/**
	 * The attributes of the element.
	 * The key is the name and the value the value.
	 *
	 * @var array
	 */
	private $attributes = array();

	/**
	 * The content of the element
	 *
	 * @var string
	 */
	private $content = '';

	/**
	 * Some protected class names which will not being prefixed.
	 *
	 * @var array
	 */
	private $protectedClasses = array();

	/**
	 * The parent.
	 *
	 * @var \CCL\Content\Element\Basic\Container
	 */
	private $parent = null;

	/**
	 * Defines the internal attributes structure with the given parameters.
	 *
	 * @param string $id
	 * @param array $classes
	 * @param array $attributes
	 *
	 * @throws \Exception
	 */
	public function __construct($id, array $classes = [], array $attributes = [])
	{
		if (! $id) {
			throw new \Exception('ID cannot be empty!');
		}

		$this->id         = $id;
		$this->classes    = $classes;
		$this->attributes = $attributes;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \CCL\Content\Element\ElementInterface::getId()
	 */
	public function getId()
	{
		return $this->getParent() ? $this->getParent()->getId() . '-' . $this->id : $this->id;
	}

	/**
	 * Returns the classes of the element.
	 *
	 * @return array[string]
	 */
	public function getClasses()
	{
		return $this->classes;
	}

	/**
	 * Sets the given class as protected. This means when a prefix is set, the class will not being prefixed.
	 *
	 * @param string $class
	 *
	 * @return Element
	 */
	public function setProtectedClass($class)
	{
		$this->protectedClasses[] = $class;

		return $this;
	}

	/**
	 * Adds the given class to the internal class variable.
	 *
	 * @param string $class
	 * @param boolean $protected
	 *
	 * @return Element
	 */
	public function addClass($class, $protected = false)
	{
		$this->classes[] = $class;

		if ($protected) {
			$this->setProtectedClass($class);
		}

		return $this;
	}

	/**
	 * Adds a new attribute for the given name.
	 *
	 * @param string $name
	 * @param string $value
	 *
	 * @return Element
	 */
	public function addAttribute($name, $value)
	{
		$this->attributes[$name] = $value;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \CCL\Content\Element\ElementInterface::getAttributes()
	 */
	public function getAttributes()
	{
		$attributes = $this->attributes;

		$prefix = null;
		if (key_exists('ccl-prefix', $attributes)) {
			unset($attributes['ccl-prefix']);
		}

		// Create the class attribute
		foreach ($this->classes as $class) {
			$class = trim($class);

			// Empty class is ignored
			if (! $class) {
				continue;
			}

			if (! key_exists('class', $attributes)) {
				$attributes['class'] = '';
			}

			if (! in_array($class, $this->protectedClasses) && $this->getPrefix()) {
				$class = $this->getPrefix() . $class;
			}
			$attributes['class'] .= $class . ' ';
		}

		// Cleanup the class attribute
		if (key_exists('class', $attributes)) {
			$attributes['class'] = trim($attributes['class']);
		}

		// Add the id to the attributes as well
		$attributes['id'] = $this->getId();

		return $attributes;
	}

	/**
	 * Returns the prefix of the element.
	 * If none is set, then it traverses the parents up, till one get found.
	 *
	 * @return string
	 */
	public function getPrefix()
	{
		$prefix = '';
		if (key_exists('ccl-prefix', $this->attributes)) {
			$prefix = $this->attributes['ccl-prefix'];
		}

		if (! $prefix && $this->parent) {
			return $this->parent->getPrefix();
		}
		return $prefix;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \CCL\Content\Element\ElementInterface::getContent()
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * Sets the content for the element.
	 * If append is set to true, the existing content will not being touched. If the content is invalid XML, an exception is thrown.
	 *
	 * @param string $content
	 * @param boolean $append
	 *
	 * @return Element
	 *
	 * @throws \Exception
	 */
	public function setContent($content, $append = false)
	{
		$content = ($append ? $this->content : '') . $content;

		if ($content === '' || $content === null) {
			return $this;
		}

		$this->content = $content;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \CCL\Content\Element\ElementInterface::getParent()
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * Sets the parent of the element.
	 *
	 * @param \CCL\Content\Element\ElementInterface $parent
	 *
	 * @return Element
	 */
	public function setParent(ElementInterface $parent)
	{
		$this->parent = $parent;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \CCL\Content\Element\ElementInterface::accept()
	 */
	public function accept(ElementVisitorInterface $visitor)
	{
		// Get the current class name
		$class = get_class($this);

		// Replace the none basic namespaces to the actual one
		$class = str_replace('CCL\\Content\\Element\\Component', __NAMESPACE__, $class);
		$class = str_replace('CCL\\Content\\Element\\Extension', __NAMESPACE__, $class);

		// Remove the actual namespace from the class name
		$name = str_replace(__NAMESPACE__, '', $class);

		// Create the visit funtion name
		$name = 'visit' . str_replace('\\', '', $name);

		// Check if the name exists
		if (!method_exists($visitor, $name)) {
			return;
		}

		// Call the visit function
		$visitor->{$name}($this);
	}

	/**
	 * Returns a string for the element.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->getId() . ' [' . get_class($this) . ']';
	}
}
