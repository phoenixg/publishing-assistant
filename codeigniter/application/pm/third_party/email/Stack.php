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

class StackException extends Exception {}

/**
 * Stack
 *
 * Sadly, the SplStack does not exist until PHP 5.3.0
 * This is modeled after the C++ STL stack
 */
class Stack {
  protected $_elements = array();
  protected $_size = 0;

  /**
   * Stack ctor
   * Don't take anything here, as there's no good way to order the elements in the stack
   * (if we take an array, for instance)
   */
  public function __construct() {}

  /**
   * Convenience method
   *
   * @return bool  Is empty
   */
  public function isEmpty() {
    return $this->_size === 0;
  }

  /**
   * Cache size
   *
   * @return int  Size
   */
  public function size() {
    return $this->_size;
  }

  /**
   * Remove element from the top of the stack and return it
   * If there were an underlying list (no doubly-linked lists until 5.3.0 either),
   * it would return the back element.  but alas.
   *
   * @return mixed  Element
   */
  public function top() {
    if ($this->_size > 0) {
      return $this->_elements[$this->_size - 1];
    } else {
      throw new StackException('Stack holds no elements');
    }
  }

  /**
   * Push an element onto the stack
   *
   * @param mixed $element  Element
   */
  public function push($element) {
    $this->_elements[] = $element;
    ++$this->_size;
  }


  /**
   * Remove element from the top of the stack
   * If there were an underlying list (no doubly-linked lists until 5.3.0 either),
   * it would return the back element.  but alas.
   *
   * @return mixed  Element
   */
  public function pop() {
    if ($this->_size > 0) {
      --$this->_size;
      array_pop($this->_elements);
    } else {
      throw new StackException('Stack holds no elements');
    }
  }
}

?>
