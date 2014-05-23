<div class="panel panel-default">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-3" style="padding-right: 0px;">
				@if($seller->getProfile()->hasAvatar())
				<img class="img-rounded" src="{{{ $seller->getProfile()->avatar->getUrl() }}}" alt="" style="width: 100%;"/>
				@endif
			</div>
			<div class="col-md-9">
				<a href="{{{ route('user.public-profile', [$seller->username]) }}}">
					{{{ $seller->username }}}
				</a><br>
				<small>{{{ trans('shop.seller') }}}</small>
			</div>
		</div>
	</div>
</div>
