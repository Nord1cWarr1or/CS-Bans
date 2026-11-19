<?php
/**
 * Шаблон с меню раздела (тема dev-cs).
 * В оригинале меню было боковой колонкой span3 справа; на широкой странице колонка отрезала
 * четверть ширины у форм и таблиц. Здесь меню — строка кнопок над контентом, контент на всю ширину.
 */

$this->beginContent('//layouts/main'); ?>
<?php if (!empty($this->menu)): ?>
<div class="section-menu">
	<?php $this->widget('bootstrap.widgets.TbMenu', array(
		'type' => 'pills',
		'items' => $this->menu,
		'htmlOptions' => array('class' => 'operations'),
	)); ?>
</div>
<?php endif; ?>
<div id="content">
	<?php echo $content; ?>
</div>
<?php $this->endContent(); ?>
