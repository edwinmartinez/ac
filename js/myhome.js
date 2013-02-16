(function() {
  var MyHome = {};
  window.MyHome = MyHome;
	
  var template = function(name) {
    return Mustache.compile($('#'+name+'-template').html());
  };

  MyHome.Recipe = Backbone.Model.extend({
  });

  MyHome.Recipes = Backbone.Collection.extend({
    localStorage: new Store("recipes")
  });

  MyHome.Index = Backbone.View.extend({
    template: template('index'),
    initialize: function() {
      this.recipes = new MyHome.Recipes();
      this.recipes.on('all', this.render, this);
      this.recipes.fetch({ success: this.fetchSuccess, error: this.fetchError });
      //this.fetch({ success: this.fetchSuccess, error: this.fetchError });
      console.log('init index');
    },
    render: function() {
      this.$el.html(this.template(this));
      return this;
    },
    count: function() {
      return this.recipes.length;
    }
  });

  /*
   * To do:
   *
   * * MyHome.Index.Form
   *   A view that renders a form which can be submitted
   *   to create a new recipe
   * * MyHome.Index should add a subview for each
   *   recipe in the database
   * * MyHome.Recipe
   *   A view that renders an individual recipe
   *   Also, a delete button to remove it
   */

  MyHome.Router = Backbone.Router.extend({
    initialize: function(options) {
      this.el = options.el
    },
    routes: {
      "": "index"
    },
    index: function() {
      var view = new MyHome.Index();
      this.el.empty();
      this.el.append(view.render().el);
    }
  });

  MyHome.boot = function(container) {
    container = $(container);
    var router = new MyHome.Router({el: container})
    Backbone.history.start();
  }
})()