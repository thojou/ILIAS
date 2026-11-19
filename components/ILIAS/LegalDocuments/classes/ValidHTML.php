<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 *
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 *
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *
 *********************************************************************/

declare(strict_types=1);

namespace ILIAS\LegalDocuments;

use DOMDocument;
use ErrorException;
use LibXMLError;
use Throwable;

class ValidHTML
{
    // @see: https://gnome.pages.gitlab.gnome.org/libxml2/devhelp/libxml2-xmlerror.html enum xmlParserErrors.
    // The xmlParserErrors enum in the C API is not made available in PHP.
    private const UNKNOWN_TAG = 801;

    public function isTrue(string $string): bool
    {
        if (!preg_match('/<[^>]+?>/', $string)) {
            return false;
        }

        $error_state = libxml_use_internal_errors(true);
        libxml_clear_errors();

        try {
            set_error_handler(static function (int $severity, string $message, string $file, int $line): void {
                throw new ErrorException($message, $severity, $severity, $file, $line);
            });

            $dom = new DOMDocument();
            $import_succeeded = $dom->loadHTML($string, LIBXML_HTML_NOIMPLIED);

            $errors = libxml_get_errors();
            libxml_clear_errors();
            libxml_use_internal_errors($error_state);

            if (
                !$import_succeeded ||
                [] !== array_filter(
                    $errors,
                    static fn(LibXMLError $error): bool => $error->code !== self::UNKNOWN_TAG
                )
            ) {
                return false;
            }

            // libxml repairs `<div </body>` into a div and reports no error.
            if ($this->containsUnclosedTag($string)) {
                return false;
            }

            if ($this->containsDisallowedElementName($dom)) {
                return false;
            }

            return true;
        } catch (Throwable) {
            $errors = libxml_get_errors();
            libxml_clear_errors();
            libxml_use_internal_errors($error_state);
            return false;
        } finally {
            restore_error_handler();
        }
    }

    private function containsUnclosedTag(string $string): bool
    {
        $length = strlen($string);
        $index = 0;
        while ($index < $length) {
            if ($string[$index] !== '<') {
                $index++;
                continue;
            }

            $next = $index + 1 < $length ? $string[$index + 1] : '';
            if ($next === '!' && str_starts_with(substr($string, $index), '<!--')) {
                $end = strpos($string, '-->', $index + 4);
                if ($end === false) {
                    return true;
                }
                $index = $end + 3;
                continue;
            }

            $starts_tag = $next === '/' || $next === '!' || $next === '?'
                || ($next >= 'A' && $next <= 'Z')
                || ($next >= 'a' && $next <= 'z');
            if (!$starts_tag) {
                $index++;
                continue;
            }

            $tag_start = $index;
            $quote = '';
            $index++;
            $closed = false;
            for (; $index < $length; $index++) {
                $char = $string[$index];
                if ($quote !== '') {
                    if ($char === $quote) {
                        $quote = '';
                    }
                    continue;
                }
                if ($char === '"' || $char === "'") {
                    $quote = $char;
                    continue;
                }
                if ($char === '>') {
                    $closed = true;
                    $index++;
                    break;
                }
                if ($char === '<') {
                    return true;
                }
            }
            if (!$closed) {
                return true;
            }

            $tag = substr($string, $tag_start, $index - $tag_start);
            $is_raw_text = preg_match('/^<(script|style)\b/i', $tag) === 1;
            if (!$is_raw_text) {
                continue;
            }

            $name = strtolower(substr($tag, 1, strcspn(substr($tag, 1), " \t\n\r>/")));
            $end = stripos($string, '</' . $name, $index);
            if ($end === false) {
                return true;
            }
            $index = $end;
        }

        return false;
    }

    // libxml accepts `<info@ilias.de>` as an element name and reports no error.
    private function containsDisallowedElementName(DOMDocument $dom): bool
    {
        for ($nodes = [$dom]; $nodes !== []; array_shift($nodes)) {
            $element = current($nodes);
            if ($element->nodeType === XML_ELEMENT_NODE && !preg_match('/^[a-zA-Z0-9-]+$/', $element->nodeName)) {
                return true;
            }
            $nodes = array_merge($nodes, iterator_to_array($element->childNodes));
        }

        return false;
    }
}
