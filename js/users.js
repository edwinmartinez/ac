/**
 * @author Edwin
 */

var MyHome = (function(MyHome) {
	console.log(MyHome); 
	MyHome.User = Backbone.Model.extend({ });
	
	MyHome.Users = Backbone.Collection.extend({
	  	//model:MyHome.User,
	  	url:'/api/v1/users.json'
	});
	//MyHome.router - 
	
	MyHome.NewUsersList = Backbone.View.extend({
		el: '.new-users',
		initialize: function() {
			//_.bindAll(this); //Make all methods in this class have `this` bound to this class
		},
		render: function() {
			var that = this;
			var newUsers = new MyHome.Users();
			newUsers.fetch({
				success: function(newUsers) { that.$el.html('something should show here'); }
			});
			
		}
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