<?php
/**
 * Options for the logocontrol plugin
 *
 * @author Ruud Habing <ruud at habing dot net>
 */


//$meta['fixme'] = array('string');

$meta['url_d3'] = array('string', '_pattern' => '#^(?:(?:(?:https?:)?/)?/)?(?:[\w.][\w./]*/)?d3(?:\.min)?\.js$#');

$meta['url_c3'] = array('string', '_pattern' => '#^(?:(?:(?:https?:)?/)?/)?(?:[\w.][\w./]*/)?c3(?:\.min)?\.js$#');

$meta['url_c3_css'] = array('string', '_pattern' => '#^(?:(?:(?:https?:)?/)?/)?(?:[\w.][\w./]*/)?c3\.css$#');

