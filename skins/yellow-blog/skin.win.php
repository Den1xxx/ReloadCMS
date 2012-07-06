<?php
/**
 * ReloadCMS - Opensource Content Management System
 * Skin DEFAULT - Box window template
 * @package    Skins
 * @subpackage Default
 * @copyright  Copyright (c) 2005-2009 ReloadCMS Development Team
 * @license    http://opensource.org/licenses/gpl-2.0.php
 *             The GNU General Public License (GPL) Version 2, June 1991
 */

if (!empty($title)) { ?>
    <div class="window-title"><?=$title?></div>
<?php } ?>
<div class="window-main" style="text-align: <?=$align?>;">
    <?=$content?>
</div>