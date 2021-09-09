        <div id="main-tab-content2" class="main-tab-content">
<?php if(isset($data['groups']) && count($data['groups'])>0): ?>
<?php
echo "FILE [", __FILE__, "] FUNC [", __FUNCTION__, "] LINE [", __LINE__, "]<br /><textarea style=\"width:100%;height:200px;color:#000;background-color:#fff;\">";
print_r($data['orders']);
echo "</textarea><br />";
?>
            <div style="text-align:right;font-style:italic">
                <?= $this->t('Order by'); ?>:
                <span class="" title="<?= $this->s('group_order')=='6' ? $this->t('The setting &quot;custom&quot; allows the manual sorting of articles via up/down arrows.') : '' ?>">
                    <?= $this->t($data['orders'][$this->s('group_order')]['order_name']) ?>
                </span>
            </div>

            <form name="modify_<?= $this->e('sectionID'); ?>" action="<?= $this->e($data['edit_url']) ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="section_id" value="<?= $this->e('sectionID'); ?>" />
                <input type="hidden" name="page_id" value="<?= $this->e('pageID'); ?>" />
                <section>
                    <ol<?php if ($this->s('group_order') == 6): ?> id="sortable-items"<?php endif; ?>>
                        <!-- The first list item is the header of the table -->
                        <li class="item-container header">
                            <div>&nbsp;</div>
                            <div><?= $this->t('Title') ?></div>
                            <div><?= $this->t('Active') ?></div>
                            <div><?= $this->t('Image') ?></div>
                            <div>&nbsp;</div>
                            <div>&nbsp;</div>
                        </li>
<?php foreach($data['groups'] as $group): ?>
                        <li class="item-container" id="group__<?= $this->e($group->group_id) ?>">
                        <?php if ($this->s('group_order') == 6): ?>
                            <div class="draghandle"><span class="j-move"></span></div>
                        <?php else: ?>
                            <div></div>
                        <?php endif; ?>
                            <div>
                                <a href="<?= $this->e($data['edit_url']) ?>section_id=<?= $this->e($data['sectionID']); ?>&amp;group_id=<?= $group->group_id; ?>&amp;tab=g" title="<?= $this->t('Modify') ?>">
                                    <?= $group->group_title ?>
                                </a>
                            </div>
                            <div>
                                <a href="javascript: confirm_link('<?= $this->t('Are you sure?') ?>', '<?= $this->e($data['edit_url']) ?>group_id=<?= $group->group_id; ?>&amp;active=<?= $group->group_active=='Y' ? '1' : '0'; ?>');" title="<?php if ($group->group_active == 'Y'): echo $this->t('Deactivate group'); else: echo $this->t('Activate group'); endif;?>">
                                    <span class="j-<?php if ($group->group_active == 'Y'): ?>checkmark<?php else: ?>cross<?php endif; ?>"></span>
                                </a> 
                            </div>
                            <div>
                                
                                bild
                            </div>

                            <div>
                                <a href="<?= $this->e($data['edit_url']) ?>group_id=<?= $this->e($group->group_id); ?>">
                                    <span class="j-pencil"></span>
                                </a>
                                <a href="javascript: confirm_link('<?= $this->t('Are you sure?') ?>', '<?= $this->e($data['base_url']) ?>');" title="<?= $this->t('Delete') ?>">
                                    <span class="j-bin"></span>
                                </a>
                            </div>
                        <?php if ($this->s('group_order') == 6): ?>
                            <div class="draghandle"><span class="j-move"></span></div>
                        <?php else: ?>
                            <div></div>
                        <?php endif; ?>
                        </li>
<?php endforeach; ?>
                    </ol>
               </section>
            </form>
<?php else: ?>
     <?= $this->t('No groups yet'); ?>
<?php endif; if(isset($data['form'])): ?>
            <h2><?= $this->t('Create group') ?></h2>
<?= $data['form']; ?>
<?php endif; ?>
        </div>