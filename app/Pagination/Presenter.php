<?php
namespace App\Pagination;

use Illuminate\Pagination\BootstrapThreePresenter;

class Presenter extends BootstrapThreePresenter
{
	/**
	 * Override the previous button text.
	 * @param  string $text
	 * @return string
	 */
	public function getPreviousButton($text = '')
	{
		return parent::getPreviousButton('<span class="fa fa-long-arrow-left"></span>');
	}

	/**
	 * Override the next button text.
	 * @param  string $text
	 * @return string
	 */
	public function getNextButton($text = '')
	{
		return parent::getNextButton('<span class="fa fa-long-arrow-right"></span>');
	}
}