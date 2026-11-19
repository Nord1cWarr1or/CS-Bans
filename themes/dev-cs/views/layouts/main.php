<!DOCTYPE html>
<?php
$g_titles = "Банлист сервера";  //название вкладки в браузере
$icon_enable = true; //Выводить иконки социальных сетей [true : false]

	/*
	 * Иконки социальных сетей под шапкой.
	 * Ссылки задаются в themes/dev-cs/social.local.php (в git не хранится), пример — social.example.php.
	 * Типы: telegram (своя SVG-иконка) или любое имя иконки Font Awesome 4.3 без префикса fa- (vk, youtube-play, twitch, steam...).
	 */
	function Social_Icons()
	{
		$file = Yii::app()->theme->basePath . '/social.local.php';
		$links = is_file($file) ? include $file : array();
		if (empty($links) || !is_array($links)) {
			return '';
		}

		$html = '<div class="icons_menu">';
		foreach ($links as $link) {
			$url   = CHtml::encode($link['url']);
			$title = CHtml::encode(isset($link['title']) ? $link['title'] : '');
			$type  = isset($link['type']) ? $link['type'] : '';
			if ($type === 'telegram') {
				$icon = '<svg class="icon-tg" width="28" height="28" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>';
			} else {
				$icon = '<i class="fa fa-2x fa-' . CHtml::encode($type) . '"></i>';
			}
			$html .= '<a href="' . $url . '" target="_blank" rel="noopener" title="' . $title . '" aria-label="' . $title . '">' . $icon . '</a> ';
		}
		return $html . '</div>';
	}

?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $g_titles; ?></title>
	<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap-responsive.min.css" rel="stylesheet">

    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font-awesome.min.css" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,800italic,400,600,800&subset=latin,cyrillic" rel="stylesheet">

    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.css?v=<?php echo @filemtime(Yii::app()->theme->basePath . '/css/style.css'); ?>" rel="stylesheet">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style-responsive.css?v=<?php echo @filemtime(Yii::app()->theme->basePath . '/css/style-responsive.css'); ?>" rel="stylesheet">
	
	<link rel="icon" href="<?php echo Yii::app()->theme->baseUrl; ?>/favicon.ico" type="image/x-icon">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	
  </head>
	
<body>
<div class="wrapper">
	<div class="header">
		<div class="container">
	<div id="nav" class="clearfix">
		<div class="container">
		
			<ul class="main-nav">
				<?php foreach(Usermenu::getMenu() as $item):?>
				<li>
					<?php echo CHtml::link(
							CHtml::encode($item['label']),
							$item['url']
						)?>
				</li>
				<?php endforeach;?>
			</ul>
			<ul class="main-nav pull-right">
				<li class="dropdown">
					<?php if(Yii::app()->user->isguest):?>
					<a href="javascript:;" data-toggle="dropdown">
						Войти
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li>
							<p>
								<form method="post" action="<?php echo Yii::app()->createUrl('/site/login')?>" accept-charset="UTF-8">
									<input style="margin-bottom: 15px;" type="text" placeholder="Логин" id="LoginForm_username" name="LoginForm[username]">
									<input style="margin-bottom: 15px;" type="password" placeholder="Пароль" id="LoginForm_password" name="LoginForm[password]">
									<label class="checkbox" style="margin-bottom: 15px; text-align: left; text-transform: none; font-size: 13px;"><input type="hidden" name="LoginForm[rememberMe]" value="0"><input type="checkbox" name="LoginForm[rememberMe]" value="1" checked> Запомнить меня</label>
									<input type="hidden" value="<?php echo Yii::app()->request->csrfToken?>" name="<?php echo Yii::app()->request->csrfTokenName?>" />
									<input class="btn btn-primary btn-block" name="yt0" type="submit" value="Войти">
								</form>
							</p>
						</li>
					</ul>
					<?php else: ?>
					<a href="javascript:;" data-toggle="dropdown">
						<?php echo Yii::app()->user->name ?>
						<span class="caret"></span>
					</a>
					
					<ul class="dropdown-menu">
						<li>
							<?php echo CHtml::link(
									'<i class="icon-globe"></i> Админцентр',
									Yii::app()->createUrl('/admin/index')
								)
							?>
						</li>
						<li>
							<hr />
						</li>
						<li>
							<?php echo CHtml::link(
									'<i class="icon-off"></i> Выйти',
									Yii::app()->createUrl('/site/logout')
								)
							?>
						</li>
					</ul>
					<?php endif; ?>
				</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="canvas-wrap">
  <canvas id="canvas"></canvas>
</div>

	<?php if($icon_enable) echo Social_Icons();	?>

	<div id="wrap">
		<div class="container" id="page">
			<?php if(isset($this->breadcrumbs)):?>
				<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
					'links'=>$this->breadcrumbs,
					'htmlOptions' => array(
						'class' => 'page-title'
					)
				)); ?>
			<?php endif?>

			<?php echo $content; ?>
			<div class="clear"></div>
			<div id="push"></div>
			
		</div>

	</div>
</div>

  
<div id="loading">
	<h1>Загрузка</h1>
	<div class="circle"></div>
	<div class="circle1"></div>
</div>

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/waveform.js?v=<?php echo @filemtime(Yii::app()->theme->basePath . '/js/waveform.js'); ?>"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/theme.js?v=<?php echo @filemtime(Yii::app()->theme->basePath . '/js/theme.js'); ?>"></script>
</body>
</html>
