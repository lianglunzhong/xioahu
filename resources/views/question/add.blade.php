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