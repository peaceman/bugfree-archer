<div class="rating">
	@for ($i = 0; $i < $shopItem->roundedAverageRating; $i++)
	<span class="glyphicon glyphicon-star"></span>
	@endfor
	@for ($i = 0; $i < 5 - $shopItem->roundedAverageRating; $i++)
	<span class="glyphicon glyphicon-star-empty"></span>
	@endfor
</div>
<em>{{{ $shopItem->ratings()->count() }}} {{{ trans('shop.ratings') }}}</em>
