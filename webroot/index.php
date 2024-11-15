<?php
/**
 *
 * Wired.Syn : Rapid Development Framework (http://www.wiredxeco.com)
 * Copyright (c) Wiredxeco, Inc.
 *
 * Licensed under The GPL3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Wiredxeco, Inc. (http://www.wiredxeco.com)
 * @link          http://www.wiredxeco.com Project
 * @since         0.0.9
 * @license       GPL3.0 License (http://opensource.org/licenses/GPL-3.0)
 */

if(strpos($_SERVER['REQUEST_URI'],'/js.js')!==false){
  require('../smart/web/js.php');
}else if(strpos($_SERVER['REQUEST_URI'],'/css.css')!==false){
  require('../smart/web/css.php');
}else{
  require('../smart/web/index.php');
}
