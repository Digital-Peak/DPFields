<?php

namespace CCL\Content\Visitor;

/**
 * Abstract class which implements ElementVisitorInterface.
 */
abstract class AbstractElementVisitor implements ElementVisitorInterface
{

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitAlert()
	 */
	public function visitAlert(\CCL\Content\Element\Component\Alert $alert)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitBadge()
	 */
	public function visitBadge(\CCL\Content\Element\Component\Badge $badge)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitButton()
	 */
	public function visitButton(\CCL\Content\Element\Basic\Button $button)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitContainer()
	 */
	public function visitContainer(\CCL\Content\Element\Basic\Container $container)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitDescriptionDescription()
	 */
	public function visitDescriptionDescription(\CCL\Content\Element\Basic\Description\Description $descriptionDescription)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitDescriptionList()
	 */
	public function visitDescriptionList(\CCL\Content\Element\Basic\DescriptionList $descriptionList)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitDescriptionListHorizontal()
	 */
	public function visitDescriptionListHorizontal(\CCL\Content\Element\Basic\DescriptionListHorizontal $descriptionListHorizontal)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitDescriptionTerm()
	 */
	public function visitDescriptionTerm(\CCL\Content\Element\Basic\Description\Term $descriptionTerm)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitElement()
	 */
	public function visitElement(\CCL\Content\Element\Basic\Element $element)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitFacebookComment()
	 */
	public function visitFacebookComment(\CCL\Content\Element\Extension\FacebookComment $facebookComment)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitFacebookLike()
	 */
	public function visitFacebookLike(\CCL\Content\Element\Extension\FacebookLike $facebookLike)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitFont()
	 */
	public function visitFont(\CCL\Content\Element\Basic\Font $font)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitForm()
	 */
	public function visitForm(\CCL\Content\Element\Basic\Form $form)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitFormInput()
	 */
	public function visitFormInput(\CCL\Content\Element\Basic\Form\Input $formInput)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitFormLabel()
	 */
	public function visitFormLabel(\CCL\Content\Element\Basic\Form\Label $formLabel)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitFormOption()
	 */
	public function visitFormOption(\CCL\Content\Element\Basic\Form\Option $formOption)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitFormSelect()
	 */
	public function visitFormSelect(\CCL\Content\Element\Basic\Form\Select $formSelect)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitFrame()
	 */
	public function visitFrame(\CCL\Content\Element\Basic\Frame $frame)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitGoogleLike()
	 */
	public function visitGoogleLike(\CCL\Content\Element\Extension\GoogleLike $googleLike)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitGrid()
	 */
	public function visitGrid(\CCL\Content\Element\Component\Grid $grid)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitGridColumn()
	 */
	public function visitGridColumn(\CCL\Content\Element\Component\Grid\Column $gridColumn)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitGridRow()
	 */
	public function visitGridRow(\CCL\Content\Element\Component\Grid\Row $gridRow)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitHeading()
	 */
	public function visitHeading(\CCL\Content\Element\Basic\Heading $heading)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitIcon()
	 */
	public function visitIcon(\CCL\Content\Element\Component\Icon $icon)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitImage()
	 */
	public function visitImage(\CCL\Content\Element\Basic\Image $image)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitLink()
	 */
	public function visitLink(\CCL\Content\Element\Basic\Link $link)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitLinkedInShare()
	 */
	public function visitLinkedInShare(\CCL\Content\Element\Extension\LinkedInShare $linkedInShare)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitListContainer()
	 */
	public function visitListContainer(\CCL\Content\Element\Basic\ListContainer $listContainer)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitListItem()
	 */
	public function visitListItem(\CCL\Content\Element\Basic\ListItem $listItem)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitMeta()
	 */
	public function visitMeta(\CCL\Content\Element\Basic\Meta $meta)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitParagraph()
	 */
	public function visitParagraph(\CCL\Content\Element\Basic\Paragraph $paragraph)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTab()
	 */
	public function visitTab(\CCL\Content\Element\Component\Tab $tab)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTabContainer()
	 */
	public function visitTabContainer(\CCL\Content\Element\Component\TabContainer $tabContainer)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTable()
	 */
	public function visitTable(\CCL\Content\Element\Basic\Table $table)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTableBody()
	 */
	public function visitTableBody(\CCL\Content\Element\Basic\Table\Body $tableBody)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTableCell()
	 */
	public function visitTableCell(\CCL\Content\Element\Basic\Table\Cell $tableCell)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTableFooter()
	 */
	public function visitTableFooter(\CCL\Content\Element\Basic\Table\Footer $tableFooter)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTableHead()
	 */
	public function visitTableHead(\CCL\Content\Element\Basic\Table\Head $tableHead)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTableHeadCell()
	 */
	public function visitTableHeadCell(\CCL\Content\Element\Basic\Table\HeadCell $tableHeadCell)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTableRow()
	 */
	public function visitTableRow(\CCL\Content\Element\Basic\Table\Row $tableRow)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTextBlock()
	 */
	public function visitTextBlock(\CCL\Content\Element\Basic\TextBlock $textBlock)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTwitterShare()
	 */
	public function visitTwitterShare(\CCL\Content\Element\Extension\TwitterShare $twitterShare)
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitXingShare()
	 */
	public function visitXingShare(\CCL\Content\Element\Extension\XingShare $xingShare)
	{
	}
}
