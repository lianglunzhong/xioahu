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