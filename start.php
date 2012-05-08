<?php

require 'lessc.inc.php';

$config = Config::get('less');

$compile = function($input_file, $output_file)
{
	try
	{
		lessc::ccompile($input_file, $output_file);
	}
	catch (Exception $ex)
	{
		exit('lessc fatal error:<br />' . $ex->getMessage());
	}
};

if (isset($config['directories']))
{
	foreach ($config['directories'] as $less_dir => $css_dir)
	{
		$less_dir = rtrim($less_dir, '/') . '/';
		foreach (glob($less_dir . '*.[Ll][Ee][Ss][Ss]') as $less)
		{
			$css = rtrim($css_dir, '/') . '/' . basename($less, '.less') . '.css';
			$compile($less, $css);
		}
	}
}

if (isset($config['files']))
{
	foreach ($config['files'] as $less => $css)
	{
		$compile($less, $css);
	}
}

if (isset($config['snippets']))
{
	$less = new lessc();
	foreach ($config['snippets'] as $snippet => $css)
	{
		file_put_contents($css, $less->parse($snippet));
	}
}