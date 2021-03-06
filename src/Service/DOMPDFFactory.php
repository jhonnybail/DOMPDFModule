<?php

namespace DOMPDFModule\Service;

use Dompdf\Options;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Dompdf\Dompdf;

class DOMPDFFactory implements FactoryInterface
{
    /**
     * An array of keys that map DOMPDF define keys to DOMPDFModule config's
     * keys.
     *
     * @var array
     */
    private static $configCompatMapping = [
        'font_directory'            => 'DOMPDF_FONT_DIR',
        'font_cache_directory'      => 'DOMPDF_FONT_CACHE',
        'temporary_directory'       => 'DOMPDF_TEMP_DIR',
        'chroot'                    => 'DOMPDF_CHROOT',
        'unicode_enabled'           => 'DOMPDF_UNICODE_ENABLED',
        'enable_fontsubsetting'     => 'DOMPDF_ENABLE_FONTSUBSETTING',
        'pdf_backend'               => 'DOMPDF_PDF_BACKEND',
        'default_media_type'        => 'DOMPDF_DEFAULT_MEDIA_TYPE',
        'default_paper_size'        => 'DOMPDF_DEFAULT_PAPER_SIZE',
        'default_font'              => 'DOMPDF_DEFAULT_FONT',
        'dpi'                       => 'DOMPDF_DPI',
        'enable_php'                => 'DOMPDF_ENABLE_PHP',
        'enable_javascript'         => 'DOMPDF_ENABLE_JAVASCRIPT',
        'enable_remote'             => 'DOMPDF_ENABLE_REMOTE',
        'log_output_file'           => 'DOMPDF_LOG_OUTPUT_FILE',
        'font_height_ratio'         => 'DOMPDF_FONT_HEIGHT_RATIO',
        'enable_css_float'          => 'DOMPDF_ENABLE_CSS_FLOAT',
        'enable_html5parser'        => 'DOMPDF_ENABLE_HTML5PARSER',
        'debug_png'                 => 'DEBUGPNG',
        'debug_keep_temp'           => 'DEBUGKEEPTEMP',
        'debug_css'                 => 'DEBUGCSS',
        'debug_layout'              => 'DEBUG_LAYOUT',
        'debug_layout_links'        => 'DEBUG_LAYOUT_LINES',
        'debug_layout_blocks'       => 'DEBUG_LAYOUT_BLOCKS',
        'debug_layout_inline'       => 'DEBUG_LAYOUT_INLINE',
        'debug_layout_padding_box'  => 'DEBUG_LAYOUT_PADDINGBOX'
    ];

    /**
     * @param \Interop\Container\ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|void
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $config = $config['dompdf_module'];

        if (defined('DOMPDF_DIR') === false) {
            define("DOMPDF_DIR", __DIR__ . '/../../../../dompdf/dompdf');
        }

        if (defined('DOMPDF_LIB_DIR') === false) {
            define("DOMPDF_LIB_DIR", DOMPDF_DIR . "/lib");
        }

        if (defined('DOMPDF_AUTOLOAD_PREPEND') === false) {
            define("DOMPDF_AUTOLOAD_PREPEND", false);
        }

        if (defined('DOMPDF_ADMIN_USERNAME') === false) {
            define("DOMPDF_ADMIN_USERNAME", false);
        }

        if (defined('DOMPDF_ADMIN_PASSWORD') === false) {
            define("DOMPDF_ADMIN_PASSWORD", false);
        }

        foreach ($config as $key => $value) {
            if (! array_key_exists($key, static::$configCompatMapping)) {
                continue;
            }

            if (defined(static::$configCompatMapping[$key])) {
                continue;
            }

            define(static::$configCompatMapping[$key], $value);
        }
        define('DOMPDF_ENABLE_AUTOLOAD', false);
        require_once DOMPDF_LIB_DIR . '/html5lib/Parser.php';
        //require_once DOMPDF_DIR . '/autoload.inc.php';
        //require_once __DIR__ . '/../../../config/module.compat.php';
        require_once __DIR__ . '/../../config/module.compat.php';

         $options = [
            'temp_dir'                   => $config['temporary_directory'],
            'font_dir'                   => $config['font_directory'],
            'font_cache'                 => $config['font_cache_directory'],
            'chroot'                     => $config['chroot'],
            'log_output_file'            => $config['log_output_file'],
            'default_media_type'         => $config['default_media_type'],
            'default_paper_size'         => $config['default_paper_size'],
            'default_font'               => $config['default_font'],
            'dpi'                        => $config['dpi'],
            'font_height_ratio'          => $config['font_height_ratio'],
            'is_php_enabled'             => $config['enable_php'],
            'is_remote_enabled'          => $config['enable_remote'],
            'is_javascript_enabled'      => $config['enable_javascript'],
            'is_html5_parser_enabled'    => $config['enable_html5parser'],
            'is_font_subsetting_enabled' => $config['enable_fontsubsetting'],
            'debug_png'                  => $config['debug_png'],
            'debug_keep_temp'            => $config['debug_keep_temp'],
            'debug_css'                  => $config['debug_css'],
            'debug_layout'               => $config['debug_layout'],
            'debug_layout_links'         => $config['debug_layout_links'],
            'debug_layout_blocks'        => $config['debug_layout_blocks'],
            'debug_layout_inline'        => $config['debug_layout_inline'],
            'debug_layout_padding_box'   => $config['debug_layout_padding_box'],
            'pdf_backend'                => $config['pdf_backend'],
            //'pdflib_license'             => $config['pdflib_license']
        ];

        $options = new Options($options);
        //$options->set('tempDir', __DIR__ . '/site_uploads/dompdf_temp');
        //$options->set('isRemoteEnabled', false);
        //$options->set('debugKeepTemp', TRUE);
        //$options->set('chroot', '/'); // Just for testing :)
        //$options->set('isHtml5ParserEnabled', true);

        $pdf = new Dompdf($options);
        return $pdf;
    }
}
