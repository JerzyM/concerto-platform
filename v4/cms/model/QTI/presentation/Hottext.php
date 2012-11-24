<?php

/*
  Concerto Platform - Online Adaptive Testing Platform
  Copyright (C) 2011-2012, The Psychometrics Centre, Cambridge University

  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; version 2
  of the License, and not any of the later versions.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

class Hottext extends AChoice {

    public static $name = "hottext";
    public static $possible_attributes = array();
    public static $required_attributes = array();
    public static $possible_children = array(
        "*"
    );
    public static $required_children = array();

    public function __construct($node, $parent) {
        parent::__construct($node, $parent);
        self::$possible_attributes = array_merge(parent::$possible_attributes, self::$possible_attributes);
        self::$required_attributes = array_merge(parent::$required_attributes, self::$required_attributes);
        self::$possible_children = array_merge(parent::$possible_children, self::$possible_children);
        self::$required_children = array_merge(parent::$required_children, self::$required_children);
    }

    public function get_HTML_code() {
        if ($this->parent->maxChoices == 1) {
            return sprintf("<input type='radio' name='%s' value='%s' class='QTIhottext' /><font class='QTIhottextLabel'>%s</font>", $this->parent->responseIdentifier, $this->identifier, $this->get_contents());
        }
        return sprintf("<input type='checkbox' name='%s' value='%s' class='QTIhottext' onclick='QTI.maxChoicesCheck(%s,\"%s\",this,%s)' /><font class='QTIhottextLabel'>%s</font>", $this->parent->responseIdentifier, $this->identifier, 1, $this->parent->responseIdentifier, $this->parent->maxChoices, $this->get_contents());
    }

}

?>