<?php

namespace CCL\Content\Visitor;

/**
 * Interface to visit the elements.
 */
interface ElementVisitorInterface
{

	/**
	 * Visit the Alert
	 *
	 * @param \CCL\Content\Element\Component\Alert $alert
	 */
	public function visitAlert(\CCL\Content\Element\Component\Alert $alert);

	/**
	 * Visit the Badge
	 *
	 * @param \CCL\Content\Element\Component\Badge $badge
	 */
	public function visitBadge(\CCL\Content\Element\Component\Badge $badge);

	/**
	 * Visit the Button
	 *
	 * @param \CCL\Content\Element\Basic\Button $button
	 */
	public function visitButton(\CCL\Content\Element\Basic\Button $button);

	/**
	 * Visit the Container
	 *
	 * @param \CCL\Content\Element\Basic\Container $container
	 */
	public function visitContainer(\CCL\Content\Element\Basic\Container $container);

	/**
	 * Visit the DescriptionDescription
	 *
	 * @param \CCL\Content\Element\Basic\Description\Description $descriptionDescription
	 */
	public function visitDescriptionDescription(\CCL\Content\Element\Basic\Description\Description $descriptionDescription);

	/**
	 * Visit the DescriptionList
	 *
	 * @param \CCL\Content\Element\Basic\DescriptionList $descriptionList
	 */
	public function visitDescriptionList(\CCL\Content\Element\Basic\DescriptionList $descriptionList);

	/**
	 * Visit the DescriptionListHorizontal
	 *
	 * @param \CCL\Content\Element\Basic\DescriptionListHorizontal $descriptionListHorizontal
	 */
	public function visitDescriptionListHorizontal(\CCL\Content\Element\Basic\DescriptionListHorizontal $descriptionListHorizontal);

	/**
	 * Visit the DescriptionTerm
	 *
	 * @param \CCL\Content\Element\Basic\Description\Term $descriptionTerm
	 */
	public function visitDescriptionTerm(\CCL\Content\Element\Basic\Description\Term $descriptionTerm);

	/**
	 * Visit the Element
	 *
	 * @param \CCL\Content\Element\Basic\Element $element
	 */
	public function visitElement(\CCL\Content\Element\Basic\Element $element);

	/**
	 * Visit the FacebookComment
	 *
	 * @param \CCL\Content\Element\Extension\FacebookComment $facebookComment
	 */
	public function visitFacebookComment(\CCL\Content\Element\Extension\FacebookComment $facebookComment);

	/**
	 * Visit the FacebookLike
	 *
	 * @param \CCL\Content\Element\Extension\FacebookLike $facebookLike
	 */
	public function visitFacebookLike(\CCL\Content\Element\Extension\FacebookLike $facebookLike);

	/**
	 * Visit the Font
	 *
	 * @param \CCL\Content\Element\Basic\Font $font
	 */
	public function visitFont(\CCL\Content\Element\Basic\Font $font);

	/**
	 * Visit the Form
	 *
	 * @param \CCL\Content\Element\Basic\Form $form
	 */
	public function visitForm(\CCL\Content\Element\Basic\Form $form);

	/**
	 * Visit the FormInput
	 *
	 * @param \CCL\Content\Element\Basic\Form\Input $formInput
	 */
	public function visitFormInput(\CCL\Content\Element\Basic\Form\Input $formInput);

	/**
	 * Visit the FormLabel
	 *
	 * @param \CCL\Content\Element\Basic\Form\Label $formLabel
	 */
	public function visitFormLabel(\CCL\Content\Element\Basic\Form\Label $formLabel);

	/**
	 * Visit the FormOption
	 *
	 * @param \CCL\Content\Element\Basic\Form\Option $formOption
	 */
	public function visitFormOption(\CCL\Content\Element\Basic\Form\Option $formOption);

	/**
	 * Visit the FormSelect
	 *
	 * @param \CCL\Content\Element\Basic\Form\Select $formSelect
	 */
	public function visitFormSelect(\CCL\Content\Element\Basic\Form\Select $formSelect);

	/**
	 * Visit the Frame
	 *
	 * @param \CCL\Content\Element\Basic\Frame $frame
	 */
	public function visitFrame(\CCL\Content\Element\Basic\Frame $frame);

	/**
	 * Visit the GoogleLike
	 *
	 * @param \CCL\Content\Element\Extension\GoogleLike $googleLike
	 */
	public function visitGoogleLike(\CCL\Content\Element\Extension\GoogleLike $googleLike);

	/**
	 * Visit the Grid
	 *
	 * @param \CCL\Content\Element\Component\Grid $grid
	 */
	public function visitGrid(\CCL\Content\Element\Component\Grid $grid);

	/**
	 * Visit the GridColumn
	 *
	 * @param \CCL\Content\Element\Component\Grid\Column $gridColumn
	 */
	public function visitGridColumn(\CCL\Content\Element\Component\Grid\Column $gridColumn);

	/**
	 * Visit the GridRow
	 *
	 * @param \CCL\Content\Element\Component\Grid\Row $gridRow
	 */
	public function visitGridRow(\CCL\Content\Element\Component\Grid\Row $gridRow);

	/**
	 * Visit the Heading
	 *
	 * @param \CCL\Content\Element\Basic\Heading $heading
	 */
	public function visitHeading(\CCL\Content\Element\Basic\Heading $heading);

	/**
	 * Visit the Icon
	 *
	 * @param \CCL\Content\Element\Component\Icon $icon
	 */
	public function visitIcon(\CCL\Content\Element\Component\Icon $icon);

	/**
	 * Visit the Image
	 *
	 * @param \CCL\Content\Element\Basic\Image $image
	 */
	public function visitImage(\CCL\Content\Element\Basic\Image $image);

	/**
	 * Visit the Link
	 *
	 * @param \CCL\Content\Element\Basic\Link $link
	 */
	public function visitLink(\CCL\Content\Element\Basic\Link $link);

	/**
	 * Visit the LinkedInShare
	 *
	 * @param \CCL\Content\Element\Extension\LinkedInShare $linkedInShare
	 */
	public function visitLinkedInShare(\CCL\Content\Element\Extension\LinkedInShare $linkedInShare);

	/**
	 * Visit the ListContainer
	 *
	 * @param \CCL\Content\Element\Basic\ListContainer $listContainer
	 */
	public function visitListContainer(\CCL\Content\Element\Basic\ListContainer $listContainer);

	/**
	 * Visit the ListItem
	 *
	 * @param \CCL\Content\Element\Basic\ListItem $listItem
	 */
	public function visitListItem(\CCL\Content\Element\Basic\ListItem $listItem);

	/**
	 * Visit the Meta
	 *
	 * @param \CCL\Content\Element\Basic\Meta $meta
	 */
	public function visitMeta(\CCL\Content\Element\Basic\Meta $meta);

	/**
	 * Visit the Paragraph
	 *
	 * @param \CCL\Content\Element\Basic\Paragraph $paragraph
	 */
	public function visitParagraph(\CCL\Content\Element\Basic\Paragraph $paragraph);

	/**
	 * Visit the Tab
	 *
	 * @param \CCL\Content\Element\Component\Tab $tab
	 */
	public function visitTab(\CCL\Content\Element\Component\Tab $tab);

	/**
	 * Visit the TabContainer
	 *
	 * @param \CCL\Content\Element\Component\TabContainer $tabContainer
	 */
	public function visitTabContainer(\CCL\Content\Element\Component\TabContainer $tabContainer);

	/**
	 * Visit the Table
	 *
	 * @param \CCL\Content\Element\Basic\Table $table
	 */
	public function visitTable(\CCL\Content\Element\Basic\Table $table);

	/**
	 * Visit the TableBody
	 *
	 * @param \CCL\Content\Element\Basic\Table\Body $tableBody
	 */
	public function visitTableBody(\CCL\Content\Element\Basic\Table\Body $tableBody);

	/**
	 * Visit the TableCell
	 *
	 * @param \CCL\Content\Element\Basic\Table\Cell $tableCell
	 */
	public function visitTableCell(\CCL\Content\Element\Basic\Table\Cell $tableCell);

	/**
	 * Visit the TableFooter
	 *
	 * @param \CCL\Content\Element\Basic\Table\Footer $tableFooter
	 */
	public function visitTableFooter(\CCL\Content\Element\Basic\Table\Footer $tableFooter);

	/**
	 * Visit the TableHead
	 *
	 * @param \CCL\Content\Element\Basic\Table\Head $tableHead
	 */
	public function visitTableHead(\CCL\Content\Element\Basic\Table\Head $tableHead);

	/**
	 * Visit the TableHeadCell
	 *
	 * @param \CCL\Content\Element\Basic\Table\HeadCell $tableHeadCell
	 */
	public function visitTableHeadCell(\CCL\Content\Element\Basic\Table\HeadCell $tableHeadCell);

	/**
	 * Visit the TableRow
	 *
	 * @param \CCL\Content\Element\Basic\Table\Row $tableRow
	 */
	public function visitTableRow(\CCL\Content\Element\Basic\Table\Row $tableRow);

	/**
	 * Visit the TextBlock
	 *
	 * @param \CCL\Content\Element\Basic\TextBlock $textBlock
	 */
	public function visitTextBlock(\CCL\Content\Element\Basic\TextBlock $textBlock);

	/**
	 * Visit the TwitterShare
	 *
	 * @param \CCL\Content\Element\Extension\TwitterShare $twitterShare
	 */
	public function visitTwitterShare(\CCL\Content\Element\Extension\TwitterShare $twitterShare);

	/**
	 * Visit the XingShare
	 *
	 * @param \CCL\Content\Element\Extension\XingShare $xingShare
	 */
	public function visitXingShare(\CCL\Content\Element\Extension\XingShare $xingShare);
}
