<?php

namespace Nathanmac\Utilities\Parser\Formats;

use Nathanmac\Utilities\Parser\Exceptions\ParserException;

/**
 * Serialize Formatter
 *
 * @package    Nathanmac\Utilities\Parser\Formats
 * @author     Nathan Macnamara <nathan.macnamara@outlook.com>
 * @license    https://github.com/nathanmac/Parser/blob/master/LICENSE.md  MIT
 */
class Serialize implements FormatInterface
{
    /**
     * Parse Payload Data
     *
     * @param string $payload
     *
     * @throws ParserException
     * @return array
     *
     */
    public function parse($payload)
    {
        if ($payload) {
            try {
                $data = @unserialize(trim($payload));
                if ($payload === 'b:0;' || $data !== false) {
                    return $data;
                }
                throw new ParserException('Failed To Parse Serialized Data');
            } catch (\Exception $ex) {
                throw new ParserException('Failed To Parse Serialized Data');
            }
        }

        return [];
    }
}
