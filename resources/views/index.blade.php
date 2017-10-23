<!DOCTYPE html>
<html lang="zh"  ng-app="xiaohu">
<head>
	<title>xiaohu</title>
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<base href="/">
	<link href="">
	<!-- <link rel="stylesheet" type="text/css" href="https://necolas.github.io/normalize.css/5.0.0/normalize.css"> -->
	<link rel="stylesheet" type="text/css" href="/css/base.css">
	<link rel="stylesheet" type="text/css" href="/css/bootstrap.css">
	<script src="/js/jquery-3.2.1.js"></script>
	<script src="/js/angular/angular.js"></script>
	<script src="/js/angular/angular-route.js"></script>
	<script src="http://apps.bdimg.com/libs/angular-ui-router/0.2.15/angular-ui-router.js"></script>
	<script src="/js/base.js"></script>
</head>
<body>
	<div class="navbar clearfix">
		<div class="fl">
			<div class="navbar-item brand">xiaohu</div>
			<form style="display: inline-block;" ng-controller="QuestionAddController" ng-submit="Question.go_add_question()">
				{{ csrf_field() }}
				<div class="navbar-item">
					<input type="text" 
							name=""
							ng-model="Question.new_question.title"
							required>
				</div>
				<button type="submit">提问</button>
			</form>
		</div>
		<div class="fr">
			<div ui-sref="home" class="navbar-item">home</div>
			<div ui-sref="login" class="navbar-item">login</div>
			<div ui-sref="signup" class="navbar-item">signup</div>
		</div>
	</div>
	<div class="page">
		<div ui-view></div>
	</div>
</body>
</html>