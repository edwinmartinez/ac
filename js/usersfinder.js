/**
 * @author Edwin
 * https://www.youtube.com/watch?v=HsEw2i4wQMM
 */

var MyHome = (function(MyHome) {

	
	var template = function(name) {
		return Mustache.compile($('#'+name+'-template').html());
	};

	MyHome.User2 = Backbone.Model.extend({ });
	
	MyHome.Users2 = Backbone.Collection.extend({
	  	initialize: function(){
  			console.log('init usersfind collection');
  		},
	  	model:MyHome.User2,
	  	url:'/api/v1/usersfinder.json'
	}); 
	
	MyHome.FindUsersList = Backbone.View.extend({
		//el: '.new-users',
		tagName:'div',
		template: template('findusers-list'),
		initialize: function() {
			console.log('starting finduserlist');
			this.findUsers = new MyHome.Users2();
			this.findUsers.on('reset', this.render, this);
			this.findUsers.fetch();
		},
		render: function() {
			this.$el.html(this.template(this));
			this.findUsers.each(this.renderNewUser, this);
      		return this;
			/*findUsers.fetch({
				success: function(findUsers) { 
					var template = _.template($('#user-list-template').html(), {users: findUsers.models});
            		that.$el.html(template);
				}
			}); */
		},
		renderNewUser:function(user) {
			//console.log(user);
			var view = new MyHome.UserView({model: user});
			$('.find-users-container').append(view.render().el);
			//$('div.messageItem').click(function(){
				//document.location.href = $(this).attr('rel');
			//});
		}
		//username: function() { return this.model.get('user_username'); }
	});
	
	
	MyHome.UserView = Backbone.View.extend({
  		tagName: 'div',
  		className : 'finduserUnit',
    	template: template('finduser-unit'),
    	render: function() {
			this.$el.html(this.template(this));
			return this;
		},
    	//msg_user:        function() { return this.model.get('msg_thread_username'); },
		username: function() { return this.model.get('user_username'); },
		age: function() { return this.model.get('age'); },
		user_gender: function() { return this.model.get('user_gender'); },
		user_id: function() { return this.model.get('user_id'); },
		profile_img: function() { return this.model.get('profile_img'); }
	});
	
	var newFindUserList = new MyHome.FindUsersList();
	
	return MyHome;
})(MyHome || {} );