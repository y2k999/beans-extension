!function (e) {
	"use strict";
	var t = function (t, i) {
		this.container = e(t),
		this.isMultiple = this
			.container
			.find(".bs-images-wrap")
			.data("multiple"),
		this.init()
	};
	t.prototype = {
		constructor: t,
		addImage: function (e) {
			var t,
				i = this,
				a = e.parents(".bs-field-wrap");
			(t = wp.media({multiple: i.isMultiple})).on("select", function () {
				var n = t.state().get("selection");
				n && n.each(function (t) {
					var n = a
							.find(".bs-image-wrap.bs-image-template")
							.clone()
							.removeClass("bs-image-template"),
						s = i.updateImage(n, t);
					a.find(".bs-images-wrap").append(s),
					i.isMultiple || e.hide()
				})
			}),
			t.open()
		},
		editImage: function (e) {
			var t,
				i = this;
			e.parents(".bs-field-wrap");
			(t = wp.media({
				multiple: !1
			})).on("open", function () {
				var i = t.state().get("selection"),
					a = e
						.parents(".bs-image-wrap")
						.find("input[type=hidden]")
						.val(),
					n = wp
						.media
						.model
						.Attachment
						.get(a);
				n.fetch(),
				i.add(
					n
						? [n]
						: []
				)
			}),
			t.on("select", function () {
				var a = t.state().get("selection");
				a && a.each(function (t) {
					i.updateImage(e.parents(".bs-image-wrap"), t)
				})
			}),
			t.open()
		},
		updateImage: function (e, t) {
			if ("thumbnail" in t.attributes.sizes) 
				var i = t
					.attributes
					.sizes
					.thumbnail
					.url;
			 else 
				i = t.attributes.url;
			
			return e
				.find("input[type=hidden]")
				.attr("value", t.id)
				.removeAttr("disabled"),
			e.find("img").attr("src", i),
			e
		},
		deleteImage: function (e) {
			e.closest(".bs-image-wrap").remove(),
			this.isMultiple || this
				.container
				.find(".bs-add-image")
				.show()
		},
		sortable: function () {
			if (this.isMultiple) {
				var e = this;
				this
					.container
					.find(".bs-images-wrap")
					.sortable({
						handle: ".bs-toolbar .bs-button-menu",
						placeholder: "bs-image-placeholder",
						cursor: "move",
						start: function (t, i) {
							i.placeholder.height(e
								.container
								.find(".bs-image-wrap")
								.outerHeight() - 6),
							i.placeholder.width(e
								.container
								.find(" .bs-image-wrap")
								.outerWidth() - 6)
						}
					})
			}
		},
		init: function () {
			this.sortable(),
			this.listen()
		},
		listen: function () {
			var t = this;
			this.container.on("click", ".bs-add-image", function () {
				t.addImage(e(this))
			}),
			this.container.on("click", ".bs-button-trash", function (i) {
				i.preventDefault(),
				t.deleteImage(e(this))
			}),
			this.container.on("click", ".bs-button-edit", function (i) {
				i.preventDefault(),
				t.editImage(e(this))
			})
		}
	},
	e.fn.bsFieldImage = function (i) {
		return this.each(function () {
			e.data(this, "plugin_bsFieldImages") || e.data(this, "plugin_bsFieldImage", new t(this, i))
		})
	},
	e(document).ready(function (e) {
		e(".bs-field.bs-image").bsFieldImage()
	})
}(jQuery);
