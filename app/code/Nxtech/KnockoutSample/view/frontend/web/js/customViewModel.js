define(["uiComponent"], function (Component) {
    "use strict";
    return Component.extend({
        /* initialize: function () {
            this._super();
        }, */
        /* defaults: {
            title: "Add Title",
            content: "Add Content Here",
        }, */
        title: "Add title from JS",
        content: "Add content fron JS",
        description: "<p>Add Description Text add here</p>",

        getCustomContent: function () {
            return "Custom Text Function: " + this.content;
        },
    });
});
