<!DOCTYPE html>
<html lang="zh"  ng-app="xiaohu">
<head>
	<title>xiaohu</title>
	<meta charset="utf-8">
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

	<script type="text/ng-template" id="home.tpl">
		<div class="home container" ng-controller="HomeController">
			<h3>最新动态</h4>
			<hr>
			<div class="item-set">
				<div ng-repeat="item in timeline.data" class="item">
					<div class="vote"></div>
					<div class="item-content">
						<div ng-if="item.question_id" class="content-act">用户@{{ item.customer.username }}添加了回答</div>
						<div ng-if="!item.question_id" class="content-act">用户@{{ item.customer.username }}添加了提问</div>
						<div class="title">@{{ item.title }}</div>
						<div class="content-owner">
							<span class="name">@{{ item.customer.username }}</span>
							<spam class="desc">你有没有在某个瞬间忽然觉得读书有用</spam>
						</div>
						<div class="content-main">@{{ item.description }}</div>
						<div class="action-set">
							<div class="comment">评论</div>
						</div>
						<div class="comment-block">
							<div class="comment-item-set">
								<div class="rect"></div>
								<div class="comment-item clearfix">
									<div class="user">两轮中</div>
									<div class="comment-content">这是评论这是,评论这是评论,这是评论这是,评论这是评论,这是评论,这是评论,这是评论,这是评论,这是评论</div>
								</div>
							</div>
						</div>
						<hr>
					</div>
				</div>
			</div>
		</div>
	</script>

	<script type="text/ng-template" id="login.tpl">
		<div class="login container" ng-controller="LoginController">
			<form name="login_form" ng-submit="User.login()">
				<div class="input-group">
					<label>
						username:
					</label>
					<input type="text" 
							name="username"
							ng-model="User.login_data.username"
							required>
				</div>
				<div class="input-group">
					<label>
						password:
					</label>
					<input type="password" 
							name="password"
							ng-model="User.login_data.password"
							required>
				</div>
				<div class="input-error-set">
					<div ng-if="User.login_failed">
						username or password not right!
					</div>
				</div>
				<button type="submit" ng-disabled="login_form.$invalid">Login</button>
			</form>
		</div>
	</script>

	<script type="text/ng-template" id="signup.tpl">
		<div class="signup container" ng-controller="SignupController">
			<div class="card">
				<h3>Signup</h3>
				<form name="signup_form" ng-submit="User.signup()">
					<div class="input-group">
						<label>username:</label>
						<input name="username" 
								type="text" 
								ng-model="User.signup_data.username" 
								ng-minlength="2" 
								ng-maxlength="16" 
								ng-model-options="{debounce:500}" 
								required>
					</div>
					<div class="input-error-set" ng-if="signup_form.username.$touched">
						<div ng-if="signup_form.username.$error.required">
							username is required!
						</div>
						<div ng-if="signup_form.username.$error.minlength || signup_form.username.$error.maxlength">
							username min 2 max 16
						</div>
						<div ng-if="User.signup_username_exists">
							username exists!
						</div>
					</div>
					<div class="input-group">
						<label>password:</label>
						<input name="password" type="password" ng-model="User.signup_data.password" ng-minlength="6" ng-maxlength="16" required>
					</div>
					<div class="input-error-set" ng-if="signup_form.password.$touched">
						<div ng-if="signup_form.password.$error.required">
							password is required!
						</div>
						<div ng-if="signup_form.password.$error.minlength || signup_form.password.$error.maxlength">
							password min 6 max 16
						</div>
					</div>
					<div class="input-group">
						<label>email:</label>
						<input name="email" type="email" ng-model="User.signup_data.email" ng-model-options="{debounce:500}" required>
					</div>
					<div class="input-error-set" ng-if="signup_form.email.$touched">
						<div ng-if="signup_form.email.$error.required">
							email is required!
						</div>
						<div ng-if="signup_form.email.$error.email">
							Not valid email!
						</div>
					</div>
					<button type="submit" ng-disabled="signup_form.$invalid">submit</button>
				</form>
			</div>
		</div>
	</script>

	<script type="text/ng-template" id="question.add.tpl">
		<div class="question-add container" ng-controller="QuestionAddController">
			<div class="card">
				<form name="question_add_form" ng-submit="Question.add()">
					<div class="input-group">
						<label>title</label><br>
						<input type="text" 
								name="title"
								ng-model="Question.new_question.title"
								ng-minlength="5"
								required>
					</div>
					<div class="input-group">
						<label>description</label><br>
						<textarea rows="5" ng-model="Question.new_question.desc"></textarea>
					</div>
					<button type="submit" ng-disabled="question_add_form.$invalid">submit</button>
				</form>
			</div>
		</div>
	</script>
</body>
</html>