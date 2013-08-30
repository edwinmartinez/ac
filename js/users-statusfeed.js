/**
 * @author Edwin
 * https://www.youtube.com/watch?v=HsEw2i4wQMM
 */

var MyHome = (function(MyHome) {
	console.log('loading statusfeed'); 
	
	var template = function(name) {
		return Mustache.compile($('#'+name+'-template').html());
	};

	MyHome.Userfeed = Backbone.Model.extend({ });
	
	MyHome.Userfeeds = Backbone.Collection.extend({
	  	initialize: function(){
  			console.log('init feeds collection');
  		},
	  	model:MyHome.Userfeed,
	  	url:'/api/v1/statusfeed.json'
	}); 
	
	MyHome.UserFeedsView = Backbone.View.extend({
		//el: '.new-users',
		tagName:'div',
		template: template('wall-list'),
		initialize: function() {
			this.feeds = new MyHome.Userfeeds();
			this.feeds.on('reset', this.render, this);
			this.feeds.fetch();
		},
		render: function() {
			this.$el.html(this.template(this));
			var form = new MyHome.Userfeeds.Form({collection: this.feeds});
        	//$('#feeds-container').html(form.render().el);
        	$('#statusUpdateForm').html(form.render().el);
			this.feeds.each(this.renderNewFeed, this);
      		return this;
		},
		renderNewFeed:function(userFeed) {
			//console.log(user);
			//var form = new MyHome.Userfeeds.Form({collection: this.feeds});
        	//$('#feeds-container').html(form.render().el);
			var view = new MyHome.UserFeedView({model: userFeed});
			$('#feeds-container').append(view.render().el);
			//$('#feeds-container').append(view.render().el);
			
			//$('div.messageItem').click(function(){
				//document.location.href = $(this).attr('rel');
			//});
		}
		//username: function() { return this.model.get('user_username'); }
	});
	
	
	MyHome.UserFeedView = Backbone.View.extend({
  		tagName: 'div',
  		className: 'wall-unit',
    	template: template('wall-unit'),
    	render: function() {
			this.$el.html(this.template(this));
			return this;
		},
    	//msg_user:        function() { return this.model.get('msg_thread_username'); },
		username: function() { return this.model.get('status_username'); },
		profile_pic: function()	{ return this.model.get('profile_pic'); },
		statusfeed_content: function() { return this.model.get('status_text'); },
		statusfeed_id: function() { return this.model.get('status_id'); } /*,
		profile_img: function() { return this.model.get('profile_img'); } */
	});
	
	MyHome.Userfeeds.Form = Backbone.View.extend({
	    tagName: 'form',
	    className: 'form-horizontal messageThreadForm',
	    template: template('statusForm'),
	    events: {
	      'submit': 'add'
	    },
	    render: function() {
	     this.$el.html(this.template(this));
	     return this;
	    },
	    add: function(event) {
	      event.preventDefault();
			$('#status_text').val($.trim($('#status_text').val())); //trim it
			if( this.$('#status_text').val().length != 0 ) { //if the value has something
				this.collection.create({
			       status_text: this.$('#status_text').val(),
			       //to_username: MyHome.thread_username
				});
				this.render();
			}
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