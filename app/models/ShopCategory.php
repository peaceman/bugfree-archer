<?php
use Baum\Node, Carbon\Carbon;

/**
 * Class ShopCategory
 *
 * @property int $id
 * @property int $parent_id
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @property string $name
 * @property string $slug
 * @property boolean $can_contain_items
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ShopCategory extends Node
{
	const SAMPLES = 'shop.category.samples';
	const PROJECT_FILES = 'shop.category.project_files';
	const TEMPLATES = 'shop.category.templates';
	const PRESETS = 'shop.category.presets';

	/**
	 * Table name.
	 *
	 * @var string
	 */
	protected $table = 'shop_categories';

	/**
	 * Column name which stores reference to parent's node.
	 *
	 * @var int
	 */
	protected $parentColumn = 'parent_id';

	/**
	 * Column name for the left index.
	 *
	 * @var int
	 */
	protected $leftColumn = 'lft';

	/**
	 * Column name for the right index.
	 *
	 * @var int
	 */
	protected $rightColumn = 'rgt';

	/**
	 * Column name for the depth field.
	 *
	 * @var int
	 */
	protected $depthColumn = 'depth';

	/**
	 * With Baum, all NestedSet-related fields are guarded from mass-assignment
	 * by default.
	 *
	 * @var array
	 */
	protected $guarded = array( 'id', 'parent_id', 'lft', 'rgt', 'depth' );

	/**
	 * Columns which restrict what we consider our Nested Set list
	 *
	 * @var array
	 */
	protected $scoped = array();

	public function shopItemRevisions()
	{
		return $this->hasMany('ShopItemRevision', 'shop_category_id');
	}

	public function getAcceptedShopItems()
	{
		$result = ShopItem::query()
			->whereHas('activeRevision', function ($query) {
				$query->where('shop_category_id', $this->id);
			});

		return $result;
	}

	public function getSlugList()
	{
		$slugList = [];

		$currentCategory = $this;
		while ($currentCategory) {
			array_unshift($slugList, $currentCategory->slug);
			$currentCategory = $currentCategory->parent;
		}

		return $slugList;
	}
}
