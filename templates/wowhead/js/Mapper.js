/*
 Mapper.js version 294
 
*/
function Mapper(d, e) {
	cO(this, d);
	if (this.parent) {
		this.parent = $(this.parent)
	} else {
		return
	}
	var c;
	this.mouseX = this.mouseY = 0;
	this.editable = this.editable || false;
	if (this.editable) {
		this.zoomable = this.toggle = false;
		this.show = this.mouse = true
	} else {
		this.zoomable = (this.zoomable == null ? true: this.zoomable);
		this.toggle = (this.toggle == null ? true: this.toggle);
		this.show = (this.show == null ? true: this.show);
		this.mouse = (this.mouse == null ? false: this.mouse)
	}
	this.zoneLink = (this.zoneLink == null ? true: this.zoneLink);
	if (location.href.indexOf("zone=") != -1) {
		this.zoneLink = false
	}
	this.zoom = (this.zoom == null ? 0 : this.zoom);
	this.zone = (this.zone == null ? 0 : this.zone);
	this.pins = [];
	this.nCoords = 0;
	this.parent.className = "mapper";
	this.parent.appendChild(this.span = ce("span"));
	c = this.span.style;
	c.display = "block";
	c.position = "relative";
	ns(this.span);
	if (this.editable) {
		this.span.onmouseup = this.addPin.bind(this);
		c = g_createGlow(LANG.mapper_tippin);
		c.style.fontSize = "11px";
		c.style.position = "absolute";
		c.style.bottom = c.style.right = "0";
		ns(c);
		this.parent.appendChild(c)
	} else {
		this.sToggle = c = g_createGlow(LANG.mapper_hidepins);
		c.style.position = "absolute";
		c.style.top = c.style.right = "0";
		c.onclick = this.toggleShow.bind(this);
		c.style.display = "none";
		ns(c);
		this.parent.appendChild(c)
	}
	if (this.zoomable) {
		this.span.onclick = this.toggleZoom.bind(this);
		this.sZoom = c = g_createGlow(LANG.mapper_tipzoom);
		c.style.fontSize = "11px";
		c.style.position = "absolute";
		c.style.bottom = c.style.right = "0";
		ns(c);
		this.span.appendChild(c)
	}
	if (this.zoneLink) {
		this.sZoneLink = c = g_createGlow("zone link");
		var f = c.childNodes[4];
		var b = ce("a");
		b.href = "?zones";
		ae(b, ct(f.firstChild.nodeValue));
		de(f.firstChild);
		ae(f, b);
		c.style.display = "none";
		c.style.position = "absolute";
		c.style.top = c.style.left = "0";
		this.parent.appendChild(c)
	}
	if (this.mouse) {
		this.parent.onmouseout = (function () {
			this.timeout = setTimeout((function () {
				this.sMouse.style.display = "none"
			}).bind(this), 1)
		}).bind(this);
		this.parent.onmouseover = (function () {
			clearTimeout(this.timeout);
			this.sMouse.style.display = ""
		}).bind(this);
		this.span.onmousemove = this.span.onmousedown = this.getMousePos.bind(this);
		this.sMouse = c = g_createGlow("(0.0, 0.0)");
		c.style.display = "none";
		c.style.position = "absolute";
		c.style.bottom = c.style.left = "0";
		c.onmouseup = sp;
		ns(c);
		this.span.appendChild(c)
	}
	this.pinBag = c = ce("div");
	ae(this.span, this.pinBag);
	if (d.coords != null) {
		this.setCoords(d.coords)
	} else {
		if (d.link != null) {
			this.setLink(d.link)
		}
	}
	this.updateMap(e)
}
Mapper.sizes = [[488, 325, "normal"], [772, 515, "zoom"]];
Mapper.prototype = {
	update: function (a, b) {
		if (a.zoom != null) {
			this.zoom = a.zoom
		}
		if (a.zone != null) {
			this.zone = a.zone
		}
		if (a.show != null) {
			this.show = a.show
		}
		if (a.coords != null) {
			this.setCoords(a.coords)
		} else {
			if (a.link != null) {
				this.setLink(a.link)
			}
		}
		this.updateMap(b)
	},
	getZone: function () {
		return this.zone
	},
	setZone: function (a, b) {
		this.zone = a;
		this.updateMap(b);
		return true
	},
	getZoom: function () {
		return this.zoom
	},
	setZoom: function (a, b) {
		this.zoom = a;
		this.updateMap(b)
	},
	toggleZoom: function (a) {
		this.zoom = 1 - this.zoom;
		this.updateMap();
		this.getMousePos(a);
		if (this.sZoom) {
			this.sZoom.style.display = "none";
			this.sZoom = null
		}
	},
	getShow: function () {
		return this.show
	},
	setShow: function (a) {
		this.show = a;
		var b = this.show ? "": "none";
		this.pinBag.style.display = b;
		g_setTextNodes(this.sToggle, (this.show ? LANG.mapper_hidepins: LANG.mapper_showpins))
	},
	toggleShow: function () {
		this.setShow(!this.show)
	},
	getCoords: function () {
		var b = [];
		for (var c in this.pins) {
			if (!this.pins[c].free) {
				b.push([this.pins[c].x, this.pins[c].y])
			}
		}
		return b
	},
	setCoords: function (a) {
		var b;
		for (var d in this.pins) {
			this.pins[d].style.display = "none";
			this.pins[d].free = true
		}
		var f;
		if (a.length) {
			f = 0
		} else {
			for (var d in a) {
				f = d;
				break
			}
			if (f == null) {
				return
			}
			a = a[f]
		}
		this.level = parseInt(f);
		this.nCoords = a.length;
		for (var d in a) {
			var e = a[d],
			c = e[2];
			b = this.getPin();
			b.x = e[0];
			b.y = e[1];
			b.style.left = b.x + "%";
			b.style.top = b.y + "%";
			if (this.editable) {
				b.a.onmouseup = this.delPin.bind(this, b)
			} else {
				if (c && c.url) {
					b.a.href = c.url;
					b.a.rel = "np";
					b.a.style.cursor = "pointer"
				}
			}
			if (c && c.label) {
				b.a.tt = c.label
			} else {
				b.a.tt = "$"
			}
			if (c && c.type) {
				b.className += " pin-" + c.type
			}
			b.a.tt = str_replace(b.a.tt, "$", b.x.toFixed(1) + ", " + b.y.toFixed(1))
		}
		this.onPinUpdate && this.onPinUpdate(this)
	},
	getLink: function () {
		var b = "";
		for (var a in this.pins) {
			if (!this.pins[a].free) {
				b += (this.pins[a].x < 10 ? "0": "") + (this.pins[a].x * 10).toFixed(0) + (this.pins[a].y < 10 ? "0": "") + (this.pins[a].y * 10).toFixed(0)
			}
		}
		return (this.zone ? this.zone: "") + (b ? ":" + b: "")
	},
	setLink: function (e) {
		var c = [];
		e = e.split(":");
		if (!this.setZone(e[0])) {
			return false
		}
		if (e.length == 2) {
			for (var d = 0; d < e[1].length; d += 6) {
				var b = e[1].substr(d, 3) / 10;
				var f = e[1].substr(d + 3, 3) / 10;
				if (isNaN(b) || isNaN(f)) {
					break
				}
				c.push([b, f])
			}
		}
		this.setCoords(c);
		return true
	},
	updateMap: function (c) {
		this.parent.style.width = this.span.style.width = Mapper.sizes[this.zoom][0] + "px";
		this.parent.style.height = this.span.style.height = Mapper.sizes[this.zoom][1] + "px";
		if (!this.editable) {
			this.parent.style.cssFloat = this.parent.style.styleFloat = "left"
		}
		if (this.zone == "0") {
			this.span.style.background = "black"
		} else {
			var d = this.level;
			if (this.zone == 4395 && d == 1) {
				d = 0
			}
			this.span.style.background = "url(images/maps/enus/" + Mapper.sizes[this.zoom][2] + "/" + this.zone + (d ? "-" + d: "") + ".jpg)"
		}
		if (this.zoneLink) {
			var b = parseInt(this.zone);
			var a = g_zones[b] != null;
			if (a) {
				g_setTextNodes(this.sZoneLink, g_zones[b]);
				this.sZoneLink.childNodes[4].firstChild.href = "?zone=" + b
			}
			this.sZoneLink.style.display = a ? "": "none"
		}
		if (this.sToggle) {
			this.sToggle.style.display = (this.toggle && this.nCoords ? "": "none")
		}
		if (!c) {
			g_scrollTo(this.parent, 3)
		}
		this.onMapUpdate && this.onMapUpdate(this)
	},
	cleanPin: function (b) {
		var a = this.pins[b];
		a.style.display = "";
		a.free = false;
		a.className = "pin";
		a.a.onmousedown = rf;
		a.a.onmouseup = rf;
		a.a.href = "javascript:;";
		a.a.style.cursor = "default";
		return a
	},
	getPin: function () {
		for (var d = 0; d < this.pins.length; ++d) {
			if (this.pins[d].free) {
				return this.cleanPin(d)
			}
		}
		var c = ce("div"),
		b = ce("a");
		c.className = "pin";
		c.appendChild(b);
		c.a = b;
		b.onmouseover = this.pinOver;
		b.onmouseout = Tooltip.hide;
		b.onclick = sp;
		this.pins.push(c);
		this.cleanPin(this.pins.length - 1);
		ae(this.pinBag, c);
		return c
	},
	addPin: function (b) {
		b = $E(b);
		if (b._button >= 2) {
			return
		}
		this.getMousePos(b);
		var a = this.getPin();
		a.x = this.mouseX;
		a.y = this.mouseY;
		a.style.left = a.x.toFixed(1) + "%";
		a.style.top = a.y.toFixed(1) + "%";
		a.a.onmouseup = this.delPin.bind(this, a);
		a.a.tt = a.x.toFixed(1) + ", " + a.y.toFixed(1);
		this.onPinUpdate && this.onPinUpdate(this);
		return false
	},
	delPin: function (a, b) {
		b = $E(b);
		a.style.display = "none";
		a.free = true;
		sp(b);
		this.onPinUpdate && this.onPinUpdate(this);
		return
	},
	pinOver: function () {
		Tooltip.show(this, this.tt, 4, 0)
	},
	getMousePos: function (b) {
		b = $E(b);
		var d = ac(this.parent);
		var a = g_getScroll();
		this.mouseX = Math.floor((b.clientX + a.x - d[0] - 3) / Mapper.sizes[this.zoom][0] * 1000) / 10;
		this.mouseY = Math.floor((b.clientY + a.y - d[1] - 3) / Mapper.sizes[this.zoom][1] * 1000) / 10;
		if (this.mouseX < 0) {
			this.mouseX = 0
		} else {
			if (this.mouseX > 100) {
				this.mouseX = 100
			}
		}
		if (this.mouseY < 0) {
			this.mouseY = 0
		} else {
			if (this.mouseY > 100) {
				this.mouseY = 100
			}
		}
		if (this.mouse) {
			g_setTextNodes(this.sMouse, "(" + this.mouseX.toFixed(1) + ", " + this.mouseY.toFixed(1) + ")")
		}
	}
};
function ma_initShowOnMap() {
	var e = ge("lenrlkn4");
	var a = ce("select");
	var c = ce("option");
	c.value = "";
	c.style.color = "#bbbbbb";
	ae(c, ct(LANG.showonmap));
	ae(a, c);
	if (showOnMap.qg_alliance || showOnMap.qg_horde) {
		var b = ce("optgroup", {
			label: LANG.som_questgivers
		});
		if (showOnMap.qg_alliance) {
			ae(b, ce("option", {
				value: "qg_alliance",
				innerHTML: g_sides[1] + sprintf(LANG.qty, showOnMap.qg_alliance.count)
			}))
		}
		if (showOnMap.qg_horde) {
			ae(b, ce("option", {
				value: "qg_horde",
				innerHTML: g_sides[2] + sprintf(LANG.qty, showOnMap.qg_horde.count)
			}))
		}
		ae(a, b)
	}
	a.onchange = a.onkeyup = function () {
		var d = this.options[this.selectedIndex].value;
		myMapper.update({
			zone: g_pageInfo.id,
			coords: d ? showOnMap[d].coords: []
		})
	};
	ae(e, a)
};