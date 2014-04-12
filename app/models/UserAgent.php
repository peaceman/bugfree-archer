<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class UserAgent extends Eloquent
{
	protected $table = 'useragents';
	protected $fillable = ['value'];
}
