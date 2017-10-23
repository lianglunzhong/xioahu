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