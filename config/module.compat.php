<?php

/**
 * Ensure that PHP is working with text internally using UTF8 character encoding.
 */
mb_internal_encoding('UTF-8');

/**
 * Global array of warnings generated by DomDocument parser and
 * stylesheet class
 *
 * @var array
 */
global $_dompdf_warnings;
$_dompdf_warnings = [];

/**
 * If true, $_dompdf_warnings is dumped on script termination when using
 * dompdf/dompdf.php or after rendering when using the DOMPDF class.
 * When using the class, setting this value to true will prevent you from
 * streaming the PDF.
 *
 * @var bool
 */
global $_dompdf_show_warnings;
$_dompdf_show_warnings = false;

/**
 * If true, the entire tree is dumped to stdout in dompdf.cls.php.
 * Setting this value to true will prevent you from streaming the PDF.
 *
 * @var bool
 */
global $_dompdf_debug;
$_dompdf_debug = false;

/**
 * Array of enabled debug message types
 *
 * @var array
 */
global $_DOMPDF_DEBUG_TYPES;
$_DOMPDF_DEBUG_TYPES = []; //array("page-break" => 1);
