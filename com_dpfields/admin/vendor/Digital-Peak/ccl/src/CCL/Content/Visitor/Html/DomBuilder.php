<?php

namespace CCL\Content\Visitor\Html;

use CCL\Content\Element\Basic\Element;
use CCL\Content\Element\Basic\Container;
use CCL\Content\Visitor\ElementVisitorInterface;
use CCL\Content\Element\ElementInterface;

/**
 * Builds a dom from the element tree.
 */
class DomBuilder implements ElementVisitorInterface
{
	/**
	 * The dom document.
	 *
	 * @var \DOMDocument
	 */
	private $dom = null;

	/**
	 * Classes to debug.
	 *
	 * @var array
	 */
	private static $debugClasses = [];

	/**
	 * Helper function which allows to add classes to debug when the element tree is visited.
	 *
	 * @param string $class
	 */
	public static function debugClass($class)
	{
		self::$debugClasses[$class] = $class;
	}

	/**
	 * Initialises the internal dom document.
	 */
	public function __construct()
	{
		// Prepare the dom document
		$this->dom               = new \DOMDocument('1.0', 'UTF-8');
		$this->dom->formatOutput = true;
	}

	/**
	 * Renders the given element and returns a HTML string.
	 *
	 * @return string
	 *
	 * @throws \Exception
	 */
	public function render()
	{
		$handler = function ($errno, $errstr) {
			throw new \DOMException($errstr);
		};

		// Set a new handler
		$oldHandler = set_error_handler($handler);

		// Gather the output
		$output = trim($this->dom->saveHTML());

		// Set the old handler back
		set_error_handler($oldHandler);

		// Return the output
		return $output;
	}

	/**
	 * Builds the element as DOMElement with the defined tag.
	 *
	 * @param ElementInterface $element
	 * @param string           $tagName
	 *
	 * @return \DOMNode
	 *
	 * @throws \DOMException
	 */
	protected function build(ElementInterface $element, $tagName = 'div')
	{
		// Determine the parent the element belongs to
		$parent = $this->dom;
		if ($element->getParent() != null) {
			$x      = new \DOMXPath($this->dom);
			$parent = $x->query("//*[@id='" . $element->getParent()->getId() . "']")->item(0) ?: $this->dom;
		}

		$root = $parent->appendChild($this->dom->createElement($tagName));

		// Set the attributes
		foreach ($element->getAttributes() as $name => $attr) {
			$attr = trim($attr);
			if ($attr == '' || $attr === null) {
				continue;
			}

			$root->setAttribute($name, $attr);
		}

		if ($element->getContent()) {
			if (strpos($element->getContent(), '<') >= 0) {
				$handler    = function ($errno, $errstr, $errfile, $errline) use ($element) {
					throw new \DOMException($errstr . ' in file ' . $errfile .
						' on line ' . $errline . PHP_EOL . htmlentities($element->getContent()));
				};
				$oldHandler = set_error_handler($handler);

				$fragment = $this->dom->createDocumentFragment();

				// If the content contains alrady cdata, then we assume it wil be valid at all
				if (strpos($element->getContent(), '<![CDATA[') !== false) {
					$fragment->appendXML($element->getContent());
				} else {
					$fragment->appendXML('<![CDATA[' . $element->getContent() . ']]>');
				}

				// If the fragment is not empty, append it
				if ($fragment->childNodes->length > 0) {
					$root->appendChild($fragment);
				}

				set_error_handler($oldHandler);
			} else {
				$root->nodeValue = htmlspecialchars($element->getContent());
			}
		}

		// Problem helper function
		if (key_exists(get_class($element), self::$debugClasses)) {
			echo '<pre>' . $element . '<br>Dom id attribute from parent: ' . $parent->getAttribute('id') .
				'<br/>' . htmlentities($this->dom->saveXML($root)) . '</pre>';
		}

		return $root;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitAlert()
	 */
	public function visitAlert(\CCL\Content\Element\Component\Alert $alert)
	{
		$this->build($alert);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitBadge()
	 */
	public function visitBadge(\CCL\Content\Element\Component\Badge $badge)
	{
		$this->build($badge);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitButton()
	 */
	public function visitButton(\CCL\Content\Element\Basic\Button $button)
	{
		$this->build($button, 'button');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitContainer()
	 */
	public function visitContainer(\CCL\Content\Element\Basic\Container $container)
	{
		$this->build($container);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitDescriptionDescription()
	 */
	public function visitDescriptionDescription(\CCL\Content\Element\Basic\Description\Description $descriptionDescription)
	{
		$this->build($descriptionDescription, 'dd');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitDescriptionTerm()
	 */
	public function visitDescriptionTerm(\CCL\Content\Element\Basic\Description\Term $descriptionTerm)
	{
		$this->build($descriptionTerm, 'dt');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitDescriptionList()
	 */
	public function visitDescriptionList(\CCL\Content\Element\Basic\DescriptionList $descriptionList)
	{
		$this->build($descriptionList, 'dl');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitDescriptionListHorizontal()
	 */
	public function visitDescriptionListHorizontal(\CCL\Content\Element\Basic\DescriptionListHorizontal $descriptionListHorizontal)
	{
		$this->build($descriptionListHorizontal, 'dl');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitElement()
	 */
	public function visitElement(\CCL\Content\Element\Basic\Element $element)
	{
		$this->build($element);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitFont()
	 */
	public function visitFont(\CCL\Content\Element\Basic\Font $font)
	{
		$this->build($font, 'font');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitForm()
	 */
	public function visitForm(\CCL\Content\Element\Basic\Form $form)
	{
		$this->build($form, 'form');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitFormInput()
	 */
	public function visitFormInput(\CCL\Content\Element\Basic\Form\Input $formInput)
	{
		$this->build($formInput, 'input');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitFormLabel()
	 */
	public function visitFormLabel(\CCL\Content\Element\Basic\Form\Label $formLabel)
	{
		$this->build($formLabel, 'label');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitFormOption()
	 */
	public function visitFormOption(\CCL\Content\Element\Basic\Form\Option $formOption)
	{
		$this->build($formOption, 'option');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitFormSelect()
	 */
	public function visitFormSelect(\CCL\Content\Element\Basic\Form\Select $formSelect)
	{
		$this->build($formSelect, 'select');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitFrame()
	 */
	public function visitFrame(\CCL\Content\Element\Basic\Frame $frame)
	{
		$this->build($frame, 'iframe');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitGrid()
	 */
	public function visitGrid(\CCL\Content\Element\Component\Grid $grid)
	{
		$this->build($grid);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitGridColumn()
	 */
	public function visitGridColumn(\CCL\Content\Element\Component\Grid\Column $gridColumn)
	{
		$this->build($gridColumn);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitGridRow()
	 */
	public function visitGridRow(\CCL\Content\Element\Component\Grid\Row $gridRow)
	{
		$this->build($gridRow);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitHeading()
	 */
	public function visitHeading(\CCL\Content\Element\Basic\Heading $heading)
	{
		$this->build($heading, 'h' . $heading->getSize());
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitIcon()
	 */
	public function visitIcon(\CCL\Content\Element\Component\Icon $icon)
	{
		$this->build($icon, 'i');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitImage()
	 */
	public function visitImage(\CCL\Content\Element\Basic\Image $image)
	{
		$this->build($image, 'img');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitLink()
	 */
	public function visitLink(\CCL\Content\Element\Basic\Link $link)
	{
		$this->build($link, 'a');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitListContainer()
	 */
	public function visitListContainer(\CCL\Content\Element\Basic\ListContainer $listContainer)
	{
		$this->build($listContainer, $listContainer->getType() == \CCL\Content\Element\Basic\ListContainer::UNORDERED ? 'ul' : 'ol');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitListItem()
	 */
	public function visitListItem(\CCL\Content\Element\Basic\ListItem $listItem)
	{
		$this->build($listItem, 'li');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitMeta()
	 */
	public function visitMeta(\CCL\Content\Element\Basic\Meta $meta)
	{
		$this->build($meta, 'meta');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitParagraph()
	 */
	public function visitParagraph(\CCL\Content\Element\Basic\Paragraph $paragraph)
	{
		$this->build($paragraph, 'p');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTab()
	 */
	public function visitTab(\CCL\Content\Element\Component\Tab $tab)
	{
		$this->build($tab);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTabContainer()
	 */
	public function visitTabContainer(\CCL\Content\Element\Component\TabContainer $tabContainer)
	{
		$this->build($tabContainer);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTable()
	 */
	public function visitTable(\CCL\Content\Element\Basic\Table $table)
	{
		$this->build($table, 'table');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTableBody()
	 */
	public function visitTableBody(\CCL\Content\Element\Basic\Table\Body $tableBody)
	{
		$this->build($tableBody, 'tbody');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTableCell()
	 */
	public function visitTableCell(\CCL\Content\Element\Basic\Table\Cell $tableCell)
	{
		$this->build($tableCell, 'td');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTableFooter()
	 */
	public function visitTableFooter(\CCL\Content\Element\Basic\Table\Footer $tableFooter)
	{
		$this->build($tableFooter, 'tfoot');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTableHead()
	 */
	public function visitTableHead(\CCL\Content\Element\Basic\Table\Head $tableHead)
	{
		$this->build($tableHead, 'thead');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTableHeadCell()
	 */
	public function visitTableHeadCell(\CCL\Content\Element\Basic\Table\HeadCell $tableHeadCell)
	{
		$this->build($tableHeadCell, 'th');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTableRow()
	 */
	public function visitTableRow(\CCL\Content\Element\Basic\Table\Row $tableRow)
	{
		$this->build($tableRow, 'tr');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTextBlock()
	 */
	public function visitTextBlock(\CCL\Content\Element\Basic\TextBlock $textBlock)
	{
		$this->build($textBlock, 'span');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitFacebookComment()
	 */
	public function visitFacebookComment(\CCL\Content\Element\Extension\FacebookComment $facebookComment)
	{
		$this->build($facebookComment);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitFacebookLike()
	 */
	public function visitFacebookLike(\CCL\Content\Element\Extension\FacebookLike $facebookLike)
	{
		$this->build($facebookLike);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitGoogleLike()
	 */
	public function visitGoogleLike(\CCL\Content\Element\Extension\GoogleLike $googleLike)
	{
		$this->build($googleLike);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitLinkedInShare()
	 */
	public function visitLinkedInShare(\CCL\Content\Element\Extension\LinkedInShare $linkedInShare)
	{
		$this->build($linkedInShare);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTwitterShare()
	 */
	public function visitTwitterShare(\CCL\Content\Element\Extension\TwitterShare $twitterShare)
	{
		$this->build($twitterShare);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitXingShare()
	 */
	public function visitXingShare(\CCL\Content\Element\Extension\XingShare $xingShare)
	{
		$this->build($xingShare);
	}
}
