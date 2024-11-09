<?php

function isstall($name)
{
	$data=config('plug');
	return isset($data[$name]);
}