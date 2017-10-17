var xiaohu = angular.module('xiaohu', ['ui.router']);

xiaohu.config(['$interpolateProvider', '$stateProvider', '$urlRouterProvider', function($interpolateProvider, $stateProvider, $urlRouterProvider) {
    // $interpolateProvider.startSymbol('[:');
    // $interpolateProvider.endSymbol(':]');

    $urlRouterProvider.otherwise('/home');

    $stateProvider
    .state('home', {
    	url: '/home',
    	templateUrl: 'home.tpl'
    })
    .state('login', {
    	url: '/login',
    	templateUrl: 'login.tpl'
    })
    .state('signup', {
    	url: '/signup',
    	templateUrl: 'signup.tpl'
    })
    .state('question', {
    	abstract: true, //抽象，不能直接访问
    	url: '/question',
    	template: '<div ui-view></div>'
    })
    .state('question.add', {
    	url: '/add',
    	templateUrl: 'question.add.tpl'
    })
}]);

xiaohu.controller('testController', ['$scope', function($scope) {
	$scope.name = 'llz test';
}]);


xiaohu.service('UserService', ['$state','$http','$location', function($state,$http,$location) {
	var me = this;
	me.signup_data = {};
	me.login_data = {};

	me.signup = function() {
		$http.post('/service/customer/register', me.signup_data)
			.then(function(res) {
				// if(res.data.status) {
				if(1) {
					me.signup_data = {};
					$state.go('home');
				}
			}, function(err) {
				console.log(err);
			})
	};

	me.username_exists = function() {
		$http.post('/service/customer/exists',
			{username: me.signup_data.username})
			.then(function(res) {
				// if(res.data.status && res.data.count) {
				if(1) {
					me.signup_username_exists = true;
				} else {
					me.signup_username_exists = false;
				}
			}, function(err) {
				console.log(err);
			})
	};

	me.login = function() {
		$http.post('/service/customer/login', me.login_data)
			.then(function(res) {
				if(res.data.status) {
				// if(1) {
					$location.url('/');
				} else {
					me.login_failed = true;
				}
			}, function(e) {
				console.log(e);
			})
	}

}]);

xiaohu.controller('SignupController', ['$scope','UserService', function($scope, UserService) {
	$scope.User = UserService;

	$scope.$watch(function() {
		return UserService.signup_data;
	}, function(n, o) {
		if(n.username != o.username) {
			UserService.username_exists();
		}
	}, true)
}]);

xiaohu.controller('LoginController', ['$scope','UserService', function($scope, UserService) {
	$scope.User = UserService;
}]);


xiaohu.service('QuestionService', ['$http', '$state', function($http, $state) {
	var me = this;

	me.new_question = {};

	me.go_add_question = function() {
		$state.go('question.add');
	}

	me.add = function() {
		$http.post('/service/question/add', me.new_question)
			.then(function(res) {
				if(res.data.status){
					me.new_question = {};
					$state.go('home');
				} else {
					me.question_add_failed = true;
				}
			}, function(e) {
				console.log(e);
			})
	}
}]);

xiaohu.controller('QuestionAddController', ['$scope', 'QuestionService', function($scope, QuestionService) {
	$scope.Question = QuestionService;
}]);

xiaohu.service('TimeLineService', ['$http', function($http) {
	var me = this;
	me.data = [];
	me.get = function(conf) {
		$http.post('/service/timeline', conf)
		.then(function(res) {
			if(res.data.status) {
				me.data = me.data.concat(res.data.data);
				console.log(me.data);
			} else {
				console.log('net-work error');
			}
		}, function(res) {
			console.log(res);
		})
	}
}]);

xiaohu.controller('HomeController', ['$scope', 'TimeLineService', function($scope, TimeLineService) {
	TimeLineService.get();
	$scope.timeline = TimeLineService;
}]);