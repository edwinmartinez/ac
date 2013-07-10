/**
 * @author Edwin
 * https://www.youtube.com/watch?v=HsEw2i4wQMM
 */

var MyHome = (function(MyHome) {
	console.log(MyHome); 
	
	var template = function(name) {
		return Mustache.compile($('#'+name+'-template').html());
	};

	MyHome.User = Backbone.Model.extend({ });
	
	MyHome.Users = Backbone.Collection.extend({
	  	initialize: function(){
  			console.log('init users collection');
  		},
	  	model:MyHome.User,
	  	url:'/api/v1/users.json'
	}); 
	
	MyHome.NewUsersList = Backbone.View.extend({
		//el: '.new-users',
		tagName:'div',
		template: template('user-list'),
		initialize: function() {
			this.newUsers = new MyHome.Users();
			this.newUsers.on('reset', this.render, this);
			this.newUsers.fetch();
		},
		render: function() {
			this.$el.html(this.template(this));
			this.newUsers.each(this.renderNewUser, this);
      		return this;
			/*newUsers.fetch({
				success: function(newUsers) { 
					var template = _.template($('#user-list-template').html(), {users: newUsers.models});
            		that.$el.html(template);
				}
			}); */
		},
		renderNewUser:function(user) {
			//console.log(user);
			var view = new MyHome.UserView({model: user});
			$('.new-users-container').append(view.render().el);
			//$('div.messageItem').click(function(){
				//document.location.href = $(this).attr('rel');
			//});
		}
		//username: function() { return this.model.get('user_username'); }
	});
	
	
	MyHome.UserView = Backbone.View.extend({
  		tagName: 'div',
    	template: template('user-unit'),
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
	
	return MyHome;
})(MyHome || {} );


/*
  
var Router = Backbone.Router.extend({
	routes:{
		'':'home'
	}
});

var myrouter = new Router();
myrouter.on('route:home',function(){
	console.log('my router home');
});
*/