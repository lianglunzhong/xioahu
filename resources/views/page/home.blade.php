<div class="home container" ng-controller="HomeController">
	<h3>最新动态</h4>
	<hr>
	<div class="item-set">
		<div ng-repeat="item in timeline.data" class="item">
			<div ng-if="item.question_id" class="vote">
				<div ng-click="timeline.vote({id:item.id, vote:1})" class="up">zan: @{{ item.upvote_count }}</div>
				<div ng-click="timeline.vote({id:item.id, vote:2})" class="down">cai: @{{ item.downvote_count }}</div>
			</div>
			<div class="item-content">
				<div ng-if="item.question_id" class="content-act">用户@{{ item.customer.username }}添加了回答</div>
				<div ng-if="!item.question_id" class="content-act">用户@{{ item.customer.username }}添加了提问</div>
				<div class="title">@{{ item.title }}</div>
				<div class="content-owner">
					<span class="name">@{{ item.customer.username }}</span>
					<spam class="desc">你有没有在某个瞬间忽然觉得读书有用</spam>
				</div>
				<div class="content-main">@{{ item.content }}</div>
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
	<div ng-if="timeline.pending" class="tec">加载中...</div>
	<div ng-if="timeline.no_more_data" class="tec">没有更多数据了</div>
</div>