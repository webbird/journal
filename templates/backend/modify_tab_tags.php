        <div id="main-tab-content2" class="main-tab-content">
<?php if(isset($data['tags']) && count($data['tags'])>0): ?>
            <div style="text-align:right;font-style:italic">
                <?= $this->t('Order by'); ?>:
                <span class="" title="<?= $this->s('tag_order')=='6' ? $this->t('The setting &quot;custom&quot; allows the manual sorting of articles via up/down arrows.') : '' ?>">
                    <?= $this->t($data['orders'][$this->s('tag_order')]['order_name']) ?>
                </span>
            </div>

            <form name="modify_<?= $this->e('sectionID'); ?>" action="<?= $this->e($data['edit_url']) ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="section_id" value="<?= $this->e('sectionID'); ?>" />
                <input type="hidden" name="page_id" value="<?= $this->e('pageID'); ?>" />
                <section>
                    <ol<?php if ($this->s('tag_order') == 6): ?> id="sortable-items"<?php endif; ?>>
                        <!-- The first list item is the header of the table -->
                        <li class="item-container header">
                            <div>&nbsp;</div>
                            <div><?= $this->t('Title') ?></div>
                            <div><?= $this->t('Active') ?></div>
                            <div>&nbsp;</div>
                            <div>&nbsp;</div>
                        </li>
<?php foreach($data['tags'] as $tag): ?>
                        <li class="item-container" id="tag__<?= $this->e($tag->tag_id) ?>">
                        <?php if ($this->s('tag_order') == 6): ?>
                            <div class="draghandle"><span class="j-move"></span></div>
                        <?php else: ?>
                            <div></div>
                        <?php endif; ?>
                            <div>
                                <a href="<?= $this->e($data['edit_url']) ?>section_id=<?= $this->e($data['sectionID']); ?>&amp;tag_id=<?= $tag->tag_id; ?>&amp;tab=g" title="<?= $this->t('Modify') ?>">
                                    <?= $tag->tag_title ?>
                                </a>
                            </div>
                            <div>
                                <a href="javascript: confirm_link('<?= $this->t('Are you sure?') ?>', '<?= $this->e($data['edit_url']) ?>&amp;tag_id=<?= $tag->tag_id; ?>&amp;active=<?= $tag->tag_active=='Y' ? '1' : '0'; ?>');" title="<?php if ($tag->tag_active == 'Y'): echo $this->t('Deactivate tag'); else: echo $this->t('Activate tag'); endif;?>">
                                    <span class="j-<?php if ($tag->tag_active == 'Y'): ?>checkmark<?php else: ?>cross<?php endif; ?>"></span>
                                </a> 
                            </div>
                            <div>
                                <a href="<?= $this->e($data['edit_url']) ?>tag_id=<?= $this->e($tag->tag_id); ?>">
                                    <span class="j-pencil"></span>
                                </a>
                                <a href="javascript: confirm_link('<?= $this->t('Are you sure?') ?>', '<?= $this->e($data['base_url']) ?>');" title="<?= $this->t('Delete') ?>">
                                    <span class="j-bin"></span>
                                </a>
                            </div>
                        <?php if ($this->s('tag_order') == 6): ?>
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
     <?= $this->t('No tags yet'); ?>
<?php endif; if(isset($data['form'])): ?>
            <h2><?= $this->t('Create tag') ?></h2>
<?= $data['form']; ?>
<?php endif; ?>
        </div>