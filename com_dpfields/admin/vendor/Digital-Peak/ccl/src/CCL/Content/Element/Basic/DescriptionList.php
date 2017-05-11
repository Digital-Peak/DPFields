<?php

namespace CCL\Content\Element\Basic;

use CCL\Content\Element\Basic\Description\Description;
use CCL\Content\Element\Basic\Description\Term;

class DescriptionList extends Container
{

	/**
	 *
	 * @param Term $term
	 * @return Term
	 */
	public function setTerm(Term $term)
	{
		return $this->addChild($term);
	}

	/**
	 *
	 * @param Description $description
	 * @return Description
	 */
	public function setDescription(Description $description)
	{
		return $this->addChild($description);
	}
}
