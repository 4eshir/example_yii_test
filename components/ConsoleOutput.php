<?php

namespace app\components;

use Yii;


class ConsoleOutput
{
	public $text;
	public $color;

	public function __construct($tText, $tColor)
    {
        $this->text = $tText ? : 'default_text';
        $this->color = $tColor ? : Console::FG_WHITE;
    }
}
