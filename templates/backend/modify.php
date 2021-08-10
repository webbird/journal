<div class="mod_journal <?=CMS_NAME?> <?=THEME?>">
    <div class="tabs">
<?php
    include 'backend_tabs.php';
    include 'modify_tab_'.$data['curr_tab'].'.php';    // content of postings tab
?>
    </div>
</div>
