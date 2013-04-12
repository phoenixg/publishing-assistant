<?php

# Copyright 2010 Splunk, Inc.
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at 
# http://www.apache.org/licenses/LICENSE-2.0
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License. 

require_once 'Stack.php';

define('SOAP_NATIVE_TYPE', 1);
define('SOAP_USER_TYPE', 2);
define('SOAP_PROPERTY', 3);
define('SOAP_OPEN_BRACE', 4);
define('SOAP_CLOSE_BRACE', 5);
define('SOAP_SEMICOLON', 6);
define('SOAP_WHITESPACE', 7);

define('SOAP_STATE_DEFAULT', 10);
define('SOAP_STATE_TYPE', 11);
define('SOAP_STATE_PROPERTY', 12);

define('SOAP_TYPE_BASE64', 'base64');
define('SOAP_TYPE_BOOLEAN', 'boolean');
define('SOAP_TYPE_BYTE', 'byte');
define('SOAP_TYPE_DATE', 'date');
define('SOAP_TYPE_DATETIME', 'dateTime');
define('SOAP_TYPE_DOUBLE', 'double');
define('SOAP_TYPE_INT', 'int');
define('SOAP_TYPE_STRING', 'string');
define('SOAP_TYPE_STRUCT', 'struct');
define('SOAP_TYPE_TIME', 'time');

/**
 * Takes SOAP type declarations and splits them into tokens
 * Doesn't handle non-standard, extraneous whitespaces
 */
class SoapTypeTokenizer {
    public static $nativeTypes = array('base64', 'boolean', 'byte', 'date', 'dateTime', 'double', 'int', 'string', 'struct', 'time');
    public static $whitespace = array(' ', "\n", "\r", "\t");

    /** 
     * Tokenize a soap type
     *
     * @param string $type  Type
     *
     * @return array         Tokens
     */
    public static function tokenize($type) {
        $stack = new Stack();
        $stack->push(SOAP_STATE_DEFAULT);
        $stack->push(SOAP_STATE_TYPE);
        $tokens = array();
        $token = '';
        $len = strlen($type);

        // We don't actually care whether we're inside of a type or not
        // That's why there aren't separate states for inside and outside of braces
        for ($pos = 0; $pos < $len; ++$pos) { 
            $char = $type[$pos];
            $nextChar = isset($type[$pos + 1]) ? $type[$pos + 1] : null;

            switch ($stack->top()) {
                case SOAP_STATE_DEFAULT:
                    if (ctype_alnum($nextChar)) {
                        $stack->push(SOAP_STATE_TYPE);
                    } elseif (in_array($char, self::$whitespace)) {
                        $tokens[] = array('code' => SOAP_WHITESPACE, 'token' => $char);
                    } elseif ($char === '{') {
                        $tokens[] = array('code' => SOAP_OPEN_BRACE, 'token' => $char);
                    } elseif ($char === '}') {
                        $tokens[] = array('code' => SOAP_CLOSE_BRACE, 'token' => $char);
                    } elseif ($char === ';') {
                        $tokens[] = array('code' => SOAP_SEMICOLON, 'token' => $char);
                    }
                    break;

                case SOAP_STATE_TYPE:
                    if (ctype_alnum($char)) {
                        $token .= $char;
                        if ($nextChar === ' ') {
                            if (in_array($token, self::$nativeTypes)) {
                                $tokens[] = array('code' => SOAP_NATIVE_TYPE, 'token' => $token);
                            } else {
                                $tokens[] = array('code' => SOAP_USER_TYPE, 'token' => $token);
                            }
                            $token = '';
                            $stack->pop();
                            $stack->push(SOAP_STATE_PROPERTY);
                        }
                    }
                    break;

                case SOAP_STATE_PROPERTY:
                    if (ctype_alnum($char)) {
                        $token .= $char;
                        if ($nextChar === ';' || $nextChar === ' ' || $nextChar === null) {
                            $tokens[] = array('code' => SOAP_PROPERTY, 'token' => $token);
                            $token = '';
                            $stack->pop();
                        }
                    } elseif ($char === ' ') {
                        $tokens[] = array('code' => SOAP_WHITESPACE, 'token' => $char);
                    }
                    break;
            }
        }

        return $tokens;
    }
}

?>
