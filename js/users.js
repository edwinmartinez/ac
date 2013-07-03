/**
 * @author Edwin
 */

(function() {
  var MyHome = {};
  //window.MyHome = MyHome;
	
  var template = function(name) {
    return Mustache.compile($('#'+name+'-template').html());
  };
  
  MyHome.member = Backbone.Model.extend({  });
  
  MyHome.members = Backbone.Collection.extend({
  	initialize: function(){
  		console.log('init members collection');
  	},
  	model:MyHome.member,
  	url:'/index.php/api/v1/users.json'
  });
  
  
})();
  
