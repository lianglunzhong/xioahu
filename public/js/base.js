var xiaohu = angular.module('xiaohu', ['ui.router']);

xiaohu.config(['$interpolateProvider', '$stateProvider', '$urlRouterProvider', function($interpolateProvider, $stateProvider, $urlRouterProvider) {
    // $interpolateProvider.startSymbol('[:');
    // $interpolateProvider.endSymbol(':]');

    $urlRouterProvider.otherwise('/home');

    $stateProvider
    .state('home', {
    	url: '/home',
    	templateUrl: 'page/home'
    })
    .state('login', {
    	url: '/login',
    	templateUrl: 'page/login'
    })
    .state('signup', {
    	url: '/signup',
    	templateUrl: 'page/signup'
    })
    .state('question', {
    	abstract: true, //抽象，不能直接访问
    	url: '/question',
    	template: '<div ui-view></div>'
    })
    .state('question.add', {
    	url: '/add',
    	templateUrl: 'question/add'
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
				if(res.data.status && res.data.count) {
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

xiaohu.service('TimeLineService', ['$http', 'AnswerService', function($http, AnswerService) {
	var me = this;
	me.data = [];
	me.current_page = 1;

	me.get = function(conf) {
		if(me.pending) {
			return;
		}
		me.pending = true;

		conf = conf || {page: me.current_page, _token:'{{ csrf_token() }}'};

		$http.post('/service/timeline', conf)
			.then(function(res) {
				if(res.data.status) {
					if(res.data.data.length) {
						me.data = me.data.concat(res.data.data);
						console.log(me.data);
						me.data = AnswerService.count_vote(me.data);
						me.current_page += 1;
					} else {
						me.no_more_data = true;
					}
				} else {
					console.log('net-work error');
				}
			}, function(res) {
				console.log(res);
			})
			.finally(function() {
				me.pending = false;
			})
	}

	me.vote = function(conf) {
		AnswerService.vote(conf)
			.then(function(res) {
				console.log(res)
				if(res) {
					AnswerService.update_data(conf.id);
				}
			})
	}
}]);

xiaohu.service('AnswerService', ['$http', function($http) {
	var me = this;
	me.data = {};
	me.count_vote = function(answers) {
		for(var i = 0; i < answers.length; i++){
			var item = answers[i];
			
			if(!item['question_id'] || !item['customers']) {
				continue;
			} 

			item.upvote_count = 0;
			item.downvote_count = 0;
			var votes = item['customers'];
			if(votes) {
				for(var j = 0; j < votes.length; j++) {
					var v = votes[j];
					if(v['pivot'].vote === 1) {
						item.upvote_count ++;
					}

					if(v['pivot'].vote === 2) {
						item.downvote_count ++;
					}
				}
			}
		}

		return answers;
	}

	me.vote = function(conf) {
		if(!conf.id || !conf.vote) {
			console.log('id and vote are required!');
			return;
		}

		return $http.post('/service/vote', conf)
					.then(function(res) {
						if(res.data.status) {
							return true;
						}

						return false;
					}, function(res) {
						return false;
					})
	}

	me.update_data = function(id) {
		return $http.post('/service/answer/read', {id:id})
					.then(function(res) {
						me.data[id] = res.data.data;
					})
	}
}]);

xiaohu.controller('HomeController', ['$scope', '$window', 'TimeLineService', 'AnswerService', function($scope, $window, TimeLineService, AnswerService) {
	TimeLineService.get();
	$scope.timeline = TimeLineService;

	$(window).scroll(function(){
		if($(window).scrollTop() - ($(document).height() - $(window).height()) > -30) {
			TimeLineService.get();
		}
	});

	$scope.$watch(function() {
		return AnswerService.data
	}, function (new_data, old_data) {
		var timeline_data = TimeLineService.data;
		for (var k in new_data) {
			for (var i=0; i < timeline_data.length; i++ ) {
				if(k == timeline_data[i].id) {
					timeline_data[i] = new_data[k];
				}
			}
		}
		TimeLineService.data = AnswerService.count_vote(TimeLineService.data);
	}, true);
}]);