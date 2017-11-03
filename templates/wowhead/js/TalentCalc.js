/*
 talentcalc.js version 278
 Differences from origin:
  1. /?		->		?		(8)
  2. http://static.wowhead.com/images/talent/	-> images/talent/
*/
var $WowheadTalentCalculator;
function TalentCalc() {
	var a6 = 0,
	aI = 1,
	C = 85,
	aZ = this,
	t, M = {},
	a0 = {},
	g, aC, N, a7, aF = -1,
	ad = -1,
	K = 0,
	aa, a2 = (Browser.opera),
	J = false,
	aB,
	w,
	a1,
	Z,
	aj,
	ag,
	aT,
	r = 0,
	R,
	aY,
	aK,
	z,
	ao,
	j,
	aM,
	v,
	aD,
	d,
	at,
	e,
	p = {},
	D = {},
	y,
	a4,
	V,
	aR,
	aH,
	I,
	l,
	ay,
	aS = [],
	ah = "0zMcmVokRsaqbdrfwihuGINALpTjnyxtgevElBCDFHJKOPQSUWXYZ123456789",
	L = "Z",
	W = [7, 24, 26, 27, 30, 34, 37, 38],
	aq,
	aG,
	A = {};
	this.addGlyph = function (bc) {
		if (bc) {
			au(aa, bc)
		} else {
			X(aa)
		}
		Lightbox.hide()
	};
	this.getBlizzBuild = function () {
		if (aF == -1) {
			return
		}
		var bf = M[aF],
		be = "";
		for (var bc = 0; bc < w; ++bc) {
			for (var bd = 0; bd < bf[bc].t.length; ++bd) {
				be += bf[bc].t[bd].k
			}
		}
		be = rtrim(be, "0");
		return be
	};
	this.getBlizzGlyphs = function () {
		if (aF == -1) {
			return
		}
		var be = M[aF],
		bc = "";
		for (var bd = 0; bd < Z; ++bd) {
			if (bd > 0) {
				bc += ":"
			}
			if (be.glyphs[bd]) {
				bc += be.glyphs[bd]
			} else {
				bc += "0"
			}
		}
		return bc
	};
	this.getGlyphs = function () {
		var bc = [];
		if (aF != -1) {
			var be = M[aF];
			if (be) {
				for (var bd = 0; bd < Z; ++bd) {
					if (be.glyphs[bd]) {
						bc.push(g_glyphs[be.glyphs[bd]])
					}
				}
			}
		}
		return bc
	};
	this.getSpentFromBlizzBuild = function (bj, bh) {
		var bi = M[bh],
		bg = [0, 0, 0];
		if (bi) {
			var bk = 0,
			bc = 0;
			for (var bf = 0; bf < bj.length; ++bf) {
				var bd = Math.min(parseInt(bj.charAt(bf)), bi[bk].t[bc].m);
				if (isNaN(bd)) {
					continue
				}
				for (var be = 0; be < bd; ++be) {++bg[bk]
				}
				if (++bc > bi[bk].t.length - 1) {
					bc = 0;
					if (++bk > w - 1) {
						break
					}
				}
			}
		}
		return bg
	};
	this.getTalents = function () {
		var bd = [];
		if (aF != -1) {
			var be = M[aF];
			if (be) {
				for (var bc = 0; bc < w; ++bc) {
					for (i = 0; i < be[bc].t.length; ++i) {
						if (be[bc].t[i].k) {
							bd.push(be[bc].t[i])
						}
					}
				}
			}
		}
		return bd
	};
	this.getWhBuild = function () {
		if (aF == -1) {
			return
		}
		var bh = M[aF],
		bd = "",
		bg,
		bf;
		for (var bc = 0; bc < w; ++bc) {
			bg = "";
			for (bf = 0; bf < bh[bc].t.length; ++bf) {
				bg += bh[bc].t[bf].k
			}
			bg = rtrim(bg, "0");
			bd += x(bg);
			bf = bg.length;
			if (bf % 2 == 1) {++bf
			}
			if (bf < bh[bc].t.length) {
				bd += L
			}
		}
		var be;
		if (aB == aI) {
			be = ah.charAt(Math.floor(aF / 10)) + ah.charAt((2 * (aF % 10)) + (r ? 1 : 0))
		} else {
			be = ah.charAt(aw(aF) * 3)
		}
		be += rtrim(bd, L);
		return be
	};
	this.getWhGlyphs = function () {
		if (aF == -1) {
			return
		}
		var bf = M[aF],
		bc = {
			1 : "",
			2 : ""
		};
		for (var be = 0; be < Z; ++be) {
			if (bf.glyphs[be]) {
				bc[aJ(be)] += ah.charAt(g_glyphs[bf.glyphs[be]].index)
			}
		}
		var bd = bc[1];
		if (bd.length < 3) {
			bd += L
		}
		bd += bc[2];
		bd = rtrim(bd, L);
		return bd
	};
	/*this.installAd = function () {
		if (t.noAd) {
			az()
		}
		if (!t.adFilled) {
			Ads.fillSpot("medrect", ge("talentcalc-sidebar-ad"))
		}
		t.adFilled = 1
	};*/
	this.initialize = function (bd, bc) {
		if (z) {
			return
		}
		bd = $(bd);
		if (!bd) {
			return
		}
		z = bd;
		z.className = "talentcalc";
		if (bc == null) {
			bc = {}
		}
		t = bc;
		if (t.onChange) {
			aG = t.onChange
		}
		if (t.mode == aI) {
			aB = aI;
			w = 1;
			a1 = 6;
			Z = 0;
			ag = 3;
			aT = 16;
			aY = g_pet_families;
			z.className += " talentcalc-pet"
		} else {
			aB = a6;
			w = 3;
			a1 = 11;
			Z = 6;
			aj = {
				1 : [0, 1, 2],
				2 : [3, 4, 5]
			};
			ag = 5;
			aT = 71;
			aY = g_chr_classes;
			z.className += " talentcalc-default";
			$WowheadTalentCalculator = aZ;
			o()
		}
		R = aT + r;
		k();
		a9();
		ap();
		ac();
		if (t.whBuild) {
			aN(t.whBuild)
		} else {
			if (t.classId > 0 && aY[t.classId]) {
				if (blizzBuild) {
					Q(t.classId, t.blizzBuild)
				} else {
					q(t.classId)
				}
			}
		}
		if (t.whGlyphs) {
			G(t.whGlyphs)
		} else {
			if (t.blizzGlyphs) {
				a8(t.blizzGlyphs)
			}
		}
	};
	this.promptBlizzBuild = function () {
		if (aB == aI) {
			return
		}
		var be, bc = prompt(LANG.prompt_importblizz, "");
		if (!bc) {
			return
		}
		if (bc.match(/\?cid=([0-9]+)&tal=([0-9]+)/)) {
			be = parseInt(RegExp.$1);
			Q(be, RegExp.$2);
			return
		} else {
			var bf = bc.indexOf("?tal=");
			if (bf != -1) {
				for (var bd in g_file_classes) {
					if (bc.indexOf(g_file_classes[bd]) != -1) {
						be = parseInt(bd);
						break
					}
				}
				if (be) {
					Q(be, bc.substring(bf + 5));
					return
				}
			}
		}
		alert(LANG.alert_invalidurl)
	};
	this.promptWhBuild = function () {
		var bd;
		var be, bc = prompt(LANG.prompt_importwh, "");
		if (!bc) {
			return
		}
		var bf = bc.indexOf("=");
		if (bf != -1) {
			be = bc.substr(bf + 1);
			bd = aN(be)
		}
		if (!bd) {
			alert(LANG.alert_invalidurl);
			return
		}
	};
	this.registerClass = function (bd, bc) {
		T(bd, bc)
	};
	this.reset = function (bc) {
		if (aF == -1) {
			return
		}
		if (bc > w - 1) {
			return
		}
		J = false;
		aV(bc, aF, true)
	};
	this.resetAll = function () {
		if (!M[aF]) {
			return
		}
		J = false;
		am(aF);
		S()
	};
	this.resetBuild = function () {
		if (!M[aF]) {
			return
		}
		J = false;
		a5(aF);
		c(aF);
		S()
	};
	this.resetGlyphs = function () {
		aL();
		S()
	};
	this.restore = function () {
		af()
	};
	this.setBlizzBuild = function (bc, bd) {
		Q(bc, bd)
	};
	this.setBlizzGlyphs = function (bc) {
		if (aF == -1) {
			return
		}
		a8(bc)
	};
	this.setBonusPoints = function (bc) {
		if (aB != aI) {
			return
		}
		ab(bc)
	};
	this.setClass = function (bc) {
		return q(bc)
	};
	this.setLevelCap = function (bd) {
		bd = parseInt(bd);
		if (isNaN(bd) || bd < 1 || bd > 80) {
			return
		}
		var bc;
		if (aB == aI) {
			bc = Math.max(0, Math.floor((bd - 16) / 4))
		} else {
			bc = Math.max(0, bd - 9)
		}
		m(bc, -1)
	};
	this.setLock = function (bc) {
		if (aF == -1) {
			return
		}
		ai(bc)
	};
	this.setWhBuild = function (bc) {
		return aN(bc)
	};
	this.setWhGlyphs = function (bc) {
		if (aF == -1) {
			return
		}
		G(bc)
	};
	this.showSummary = function (bh) {
		if (aF == -1) {
			return
		}
		var bi = M[aF],
		bg = window.open("", "", "toolbar=no,menubar=yes,status=yes,scrollbars=yes,resizable=yes"),
		be,
		bd,
		bc,
		bf = "<html><head><title>" + document.title + '</title></head><body style="font-family: Arial, sans-serif; font-size: 13px">';
		bg.document.open();
		if (bh) {
			bf += "<h2>";
			if (aB == aI) {
				bf += sprintf(LANG.tc_printh, aQ(), g_pet_families[bi.n])
			} else {
				bf += sprintf(LANG.tc_printh, aQ(), g_chr_classes[bi.n]) + " (" + bi[0].k + "/" + bi[1].k + "/" + bi[2].k + ")"
			}
			bf += "</h2>";
			bf += "<p></p>";
			for (be = 0; be < w; ++be) {
				bf += "<h3>" + bi[be].n + " (" + bi[be].k + " " + LANG[bi[be].k == 1 ? "tc_point": "tc_points"] + ")</h3>";
				bf += "<blockquote>";
				bc = 0;
				for (bd = 0; bd < bi[be].t.length; ++bd) {
					if (bi[be].t[bd].k) {
						if (bc) {
							bf += "<br /><br />"
						}
						bf += "<b>" + bi[be].t[bd].n + "</b>" + LANG.hyphen + sprintf(LANG.tc_rank, bi[be].t[bd].k, bi[be].t[bd].m) + "<br />";
						bf += ar(bi[be].t[bd]); ++bc
					}
				}
				if (bc == 0) {
					bf += LANG.tc_none
				}
				bf += "</blockquote>"
			}
			bf += "<h3>" + LANG.tc_glyphs + "</h3>";
			bf += "<blockquote>";
			glyphCount = 0;
			for (be = 0; be < Z; ++be) {
				glyph = g_glyphs[bi.glyphs[be]];
				if (glyph) {
					if (glyphCount) {
						bf += "<br /><br />"
					}
					bf += "<b>" + glyph.name + "</b> ";
					if (glyph.type == 1) {
						bf += "(" + LANG.tc_majgly + ")<br />"
					} else {
						bf += "(" + LANG.tc_mingly + ")<br />"
					}
					bf += glyph.description;
					glyphCount++
				}
			}
			if (glyphCount == 0) {
				bf += LANG.tc_none
			}
			bf += "</blockquote>"
		} else {
			bf += "<pre>";
			for (be = 0; be < w; ++be) {
				bf += "<b>" + bi[be].n + " (" + bi[be].k + " " + LANG[bi[be].k == 1 ? "tc_point": "tc_points"] + ")</b>\n\n";
				bc = 0;
				for (bd = 0; bd < bi[be].t.length; ++bd) {
					if (bi[be].t[bd].k) {
						bf += "&nbsp;&nbsp;&nbsp;&nbsp;" + bi[be].t[bd].k + "/" + bi[be].t[bd].m + " " + bi[be].t[bd].n + "\n"; ++bc
					}
				}
				if (bc == 0) {
					bf += "&nbsp;&nbsp;&nbsp;&nbsp;" + LANG.tc_none + "\n"
				}
				bf += "\n"
			}
			bf += "</pre>"
		}
		bf += "</body></html>";
		bg.document.write(bf);
		bg.document.close()
	};
	this.simplifyGlyphName = function (bc) {
		return av(bc)
	};
	this.toggleLock = function () {
		if (aF == -1) {
			return
		}
		s()
	};
	function au(bf, bd, bc) {
		var be = M[aF];
		glyph = g_glyphs[bd];
		if (glyph && n(glyph, bf)) {
			if (be.glyphs[bf]) {
				be.glyphItems[be.glyphs[bf]] = 0
			}
			be.glyphs[bf] = bd;
			be.glyphItems[bd] = 1;
			if (!bc) {
				aX(bf);
				S()
			}
		}
	}
	function u() {
		var bg = M[aF];
		if (bg.k > R) {
			for (var bc = w - 1; bc >= 0; --bc) {
				for (var bf = bg[bc].t.length - 1; bf >= 0; --bf) {
					var bd = bg[bc].t[bf].k;
					for (var be = 0; be < bd; ++be) {
						h(bg[bc].t[bf]);
						if (bg.k <= R) {
							return
						}
					}
				}
			}
		}
	}
	function E(bd, bc) {
		if (r) {
			ab(0)
		} else {
			ab(4)
		}
		an(bd, bc)
	}
	function an(bd, bc) {
		Tooltip.showAtCursor(bc, LANG[r ? "tc_rembon": "tc_addbon"], null, null, "q")
	}
	function aU(bc, bf, bg) {
		var bh = ce("div"),
		be,
		bd;
		bh.className = "talentcalc-arrow";
		switch (bc) {
		case 0:
			bf = 15;
			be = aO(1, 2);
			be.className = "talentcalc-arrow-down";
			bd = be.firstChild.childNodes[0].childNodes[0].style;
			bd.width = "15px";
			bd.height = "4px";
			bd = be.firstChild.childNodes[1].childNodes[0].style;
			bd.backgroundPosition = "bottom";
			bd.height = (bg - 4) + "px";
			break;
		case 1:
			be = aO(2, 2, true);
			be.className = "talentcalc-arrow-leftdown";
			bd = be.firstChild.childNodes[0].childNodes[0].style;
			bd.backgroundPosition = "left";
			bd.width = (bf - 4) + "px";
			bd.height = "11px";
			bd = be.firstChild.childNodes[0].childNodes[1].style;
			bd.backgroundPosition = "right";
			bd.width = "4px";
			bd = be.firstChild.childNodes[1].childNodes[0].style;
			bd.backgroundPosition = "bottom left";
			bd.backgroundRepeat = "no-repeat";
			bd.height = (bg - 11) + "px";
			break;
		case 2:
			be = aO(2, 2, true);
			be.className = "talentcalc-arrow-rightdown";
			bd = be.firstChild.childNodes[0].childNodes[0].style;
			bd.backgroundPosition = "left";
			bd.width = "4px";
			bd = be.firstChild.childNodes[0].childNodes[1].style;
			bd.backgroundPosition = "right";
			bd.width = (bf - 4) + "px";
			bd.height = "11px";
			bd = be.firstChild.childNodes[1].childNodes[0].style;
			bd.backgroundPosition = "bottom right";
			bd.backgroundRepeat = "no-repeat";
			bd.height = (bg - 11) + "px";
			break;
		case 3:
			bg = 15;
			be = aO(2, 1);
			be.className = "talentcalc-arrow-right";
			bd = be.firstChild.childNodes[0].childNodes[0].style;
			bd.backgroundPosition = "left";
			bd.width = "4px";
			bd = be.firstChild.childNodes[0].childNodes[1].style;
			bd.backgroundPosition = "right";
			bd.width = (bf - 4) + "px";
			break;
		case 4:
			bg = 15;
			be = aO(2, 1);
			be.className = "talentcalc-arrow-left";
			bd = be.firstChild.childNodes[0].childNodes[0].style;
			bd.backgroundPosition = "left";
			bd.width = (bf - 4) + "px";
			bd = be.firstChild.childNodes[0].childNodes[1].style;
			bd.backgroundPosition = "right";
			bd.width = "4px";
			break
		}
		bh.style.width = bf + "px";
		bh.style.height = bg + "px";
		ae(bh, be);
		return bh
	}
	function ac() {
		var be, bf, bd;
		ay = ce("div");
		ay.className = "talentcalc-lower";
		ay.style.display = "none";
		for (var bc = 0; bc < w; ++bc) {
			be = aS[bc] = ce("div");
			be.className = "talentcalc-lower-tree" + (bc + 1);
			bf = ce("p");
			bf.className = "rcorners";
			ae(bf, ce("b"));
			ae(bf, ce("span"));
			bd = ce("a");
			bd.href = "javascript:;";
			bd.onclick = aZ.reset.bind(null, bc);
			ae(bf, bd);
			ae(bf, ce("tt"));
			ae(bf, ce("strong"));
			ae(bf, ce("var"));
			ae(bf, ce("em"));
			ae(be, bf);
			ae(ay, be)
		}
		ae(z, ay)
	}
	function ap() {
		l = ce("div");
		l.className = "talentcalc-main";
		var bc = ce("div");
		bc.className = "clear";
		ae(l, bc);
		ae(z, l)
	}
	function bb(bk) {
		var bi = [{}],
		bc,
		bl;
		var be = in_array(W, bk) != -1;
		for (var bh = 0, bj = g_pet_talents.length; bh < bj; ++bh) {
			var bd = g_pet_talents[bh];
			if (in_array(bd.f, bk) >= 0) {
				bi[0].n = bd.n;
				bi[0].t = [];
				bi[0].i = bh;
				for (var bg = 0, bf = bd.t.length; bg < bf; ++bg) {
					bc = bd.t[bg];
					bl = bi[0].t[bg] = {};
					cO(bl, bc);
					if (bh == 0 && ((bg == 1 && be) || (bg == 2 && !be) || (bg == 11 && be) || (bg == 12 && !be))) {
						bl.hidden = true
					}
					if (bh == 2 && ((bg == 1 && be) || (bg == 2 && !be) || (bg == 6 && be) || (bg == 7 && !be))) {
						bl.hidden = true
					}
				}
				break
			}
		}
		return bi
	}
	function k() {
		var bc, bp, bo, bg;
		ao = ce("div");
		ao.className = "talentcalc-sidebar rcorners";
		ae(ao, ce("tt"));
		ae(ao, ce("strong"));
		ae(ao, ce("var"));
		ae(ao, ce("em"));
		bc = ce("div");
		bc.className = "talentcalc-sidebar-inner";
		bp = ce("a");
		bp.className = "talentcalc-button-help";
		bp.href = (aB == aI ? "http://petopia.brashendeavors.net/html/patch30/patch30faq_talents.php": "?help=talent-calculator");
		bp.target = "_blank";
		ae(bp, ct(LANG.tc_help));
		ae(bc, bp);
		j = ce("div");
		j.className = "talentcalc-sidebar-controls";
		j.style.display = "none";
		bp = ce("a");
		bp.className = "talentcalc-button-reset";
		bp.href = "javascript:;";
		bp.onclick = aZ.resetAll;
		ae(bp, ct(LANG.tc_resetall));
		ae(j, bp);
		bp = v = ce("a");
		bp.className = "talentcalc-button-lock";
		bp.href = "javascript:;";
		bp.onclick = s;
		ae(bp, ct(LANG.tc_lock));
		ae(j, bp);
		bp = ce("div");
		bp.className = "clear";
		ae(j, bp);
		ae(bc, j);
		bp = ce("div");
		bp.className = "talentcalc-sidebar-controls2";
		bo = ce("a");
		bo.className = "talentcalc-button-import";
		bo.href = "javascript:;";
		bo.onclick = aZ.promptBlizzBuild;
		ae(bo, ct(LANG.tc_import));
		ae(bp, bo);
		bo = aD = ce("a");
		bo.className = "talentcalc-button-summary";
		bo.style.display = "none";
		bo.href = "javascript:;";
		bo.onclick = aZ.showSummary.bind(null, 1);
		ae(bo, ct(LANG.tc_summary));
		ae(bp, bo);
		bo = d = ce("a");
		bo.className = "talentcalc-button-restore";
		bo.style.display = "none";
		bo.href = "javascript:;";
		bo.onclick = af;
		ae(bo, ct(LANG.tc_restore));
		ae(bp, bo);
		if (t.profiler) {
			bo = at = ce("a");
			bo.className = "talentcalc-button-export";
			bo.style.display = "none";
			bo.href = "#";
			bo.target = "_blank";
			ae(bo, ct(LANG.tc_export));
			ae(bp, bo)
		}
		bo = ce("div");
		bo.className = "clear";
		ae(bp, bo);
		ae(bc, bp);
		aM = bp = ce("div");
		ae(bc, bp);
		/*if (!t.noAd) {
			az()
		}*/
		if (aB == a6) {
			e = ce("div");
			e.style.display = "none";
			bp = ce("h3");
			ae(bp, ct(LANG.tc_glyphs));
			ae(e, bp);
			bo = ce("a");
			bo.href = "javascript:;";
			bo.onclick = aZ.resetGlyphs;
			ae(bo, ct("[x]"));
			ae(bp, bo);
			bp = ce("div");
			bp.className = "talentcalc-sidebar-majorglyphs q9";
			bo = ce("b");
			ae(bo, ct(g_item_glyphs[1]));
			ae(bp, bo);
			ae(e, bp);
			bp = ce("div");
			bp.className = "talentcalc-sidebar-minorglyphs q9";
			bo = ce("b");
			ae(bo, ct(g_item_glyphs[2]));
			ae(bp, bo);
			ae(e, bp);
			bp = ce("div");
			bp.className = "clear";
			ae(e, bp);
			var bq = ce("table"),
			bf = ce("tbody"),
			bh,
			bd,
			be,
			bk,
			bj,
			bm;
			bq.className = "icontab";
			for (var bi = 0; bi < 3; ++bi) {
				bh = ce("tr");
				for (var bl = 0; bl < 2; ++bl) {
					var bn = (bl * 3) + bi;
					bd = ce("th");
					bk = Icon.create("inventoryslot_empty", 1, null, "javascript:;");
					bj = Icon.getLink(bk);
					p[bn] = bk;
					ae(bd, bk);
					ae(bh, bd);
					be = ce("td");
					bm = ce("a");
					D[bn] = bm;
					ae(be, bm);
					ae(bh, be);
					bm.target = bj.target = "_blank";
					bm.rel = bj.rel = "np";
					bm.onmousedown = bj.onmousedown = rf;
					bm.onclick = bj.onclick = rf;
					g_onClick(bm, ak.bind(bm, bn));
					bm.onmouseover = F.bind(null, bm, bn);
					bm.onmousemove = Tooltip.cursorUpdate;
					bm.onmouseout = Tooltip.hide;
					g_onClick(bj, ak.bind(bj, bn));
					bj.onmouseover = F.bind(null, bj, bn);
					bj.onmouseout = Tooltip.hide;
					be.oncontextmenu = rf
				}
				ae(bf, bh)
			}
			ae(bq, bf);
			ae(e, bq);
			ae(bc, e)
		}
		ae(ao, bc);
		bp = ce("div");
		bp.className = "talentcalc-sidebar-anchor";
		ae(bp, ao);
		ae(z, bp)
	}
	/*function az() {
		var bc = ce("div");
		bc.id = "talentcalc-sidebar-ad";
		ae(aM, bc);
		delete t.noAd
	}*/
	function aO(be, bi, bc) {
		var bk = ce("table"),
		bd = ce("tbody"),
		bf,
		bj;
		for (var bg = 0; bg < bi; ++bg) {
			bf = ce("tr");
			for (var bh = 0; bh < be; ++bh) {
				if (bc && bg > 0) {
					bj = ce("th");
					bj.colSpan = 2;
					ae(bf, bj);
					break
				} else {
					ae(bf, ce("td"))
				}
			}
			ae(bd, bf)
		}
		ae(bk, bd);
		return bk
	}
	function P(bo) {
		var bw = M[bo],
		bA;
		bw.k = 0;
		bw.div = ce("div");
		bw.div.style.display = "none";
		aef(l, bw.div);
		for (var bm = 0; bm < w; ++bm) {
			bw[bm].k = 0;
			var bv = ce("div");
			d2 = ce("div");
			bv.style.backgroundRepeat = "no-repeat";
			bv.style.cssFloat = bv.style.styleFloat = "left";
			if (bm > 0) {
				bv.style.borderLeft = "1px solid #404040"
			}
			d2.style.overflow = "hidden";
			d2.style.width = (aB == a6 ? "204px": "244px");
			ae(d2, aO(4, a1));
			ae(bv, d2);
			ae(bw.div, bv);
			var br = gE(bv, "td"),
			by,
			bx = "";
			if (!Browser.ie6) {
				bx = "?" + C
			}
			if (aB == aI) {
				bv.style.backgroundImage = "url(http://static.wowhead.com/images/pet/petcalc" + (g_locale.id == 25 ? "-ptr": "") + "/bg_" + (bw[0].i + 1) + ".jpg" + bx + ")";
				by = "http://static.wowhead.com/images/pet/petcalc" + (g_locale.id == 25 ? "-ptr": "") + "/icons_" + (bw[0].i + 1) + ".jpg" + bx
			} else {
				bv.style.backgroundImage = "url(images/talent/classes/backgrounds/" + g_file_classes[bo] + "_" + (bm + 1) + ".jpg" + bx + ")";
				//by = "images/talent/classes/icons" + (g_locale.id == 25 ? "-ptr": "") + "/" + g_file_classes[bo] + "_" + (bm + 1) + ".jpg" + bx
			}
			for (var bq = bw[bm].t.length - 1; bq >= 0; --bq) {
				var bj = bw[bm].t[bq],
				//bu = Icon.create(by, 1, null, "javascript:;"),
				bu = Icon.create(bj.iconname, 1, null, "javascript:;"),
				bf = Icon.getLink(bu),
				bn = br[(bj.y * 4 + bj.x + 1) - 1];
				if (Browser.ie6) {
					bf.onfocus = tb
				}
				bf.rel = "np";
				bf.target = "_blank";
				bf.onmousedown = rf;
				bf.onclick = rf;
				g_onClick(bf, H.bind(bf, bj));
				bf.onmouseover = Y.bind(null, bf, bj);
				bf.onmouseout = Tooltip.hide;
				var bt = ce("div"),
				bz = ce("div");
				ae(bz, ct("0"));
				bt.className = "icon-border";
				bz.className = "icon-bubble";
				ae(bu, bt);
				ae(bu, bz);
				bj.k = 0;
				bj.i = bq;
				bj.tree = bm;
				bj.classId = bo;
				bj.icon = bu;
				bj.link = bf;
				bj.border = bt;
				bj.bubble = bz;
				if (!bj.hidden) {
					ae(bn, bu)
				}
				if (bj.r) {
					var bh = bw[bm].t[bj.r[0]],
					be = bj.x - bh.x,
					bd = bj.y - bh.y,
					bp,
					bk,
					bi,
					bs,
					bg = -1;
					if (bh.links == null) {
						bh.links = [bq]
					} else {
						bh.links.push(bq)
					}
					if (bd > 0) {
						if (be == 0) {
							bg = 0
						} else {
							if (be < 0) {
								bg = 1
							} else {
								bg = 2
							}
						}
					} else {
						if (bd == 0) {
							if (be > 0) {
								bg = 3
							} else {
								if (be < 0) {
									bg = 4
								}
							}
						}
					}
					if (aB == aI) {
						bi = (Math.abs(be) - 1) * 60;
						bs = (Math.abs(bd) - 1) * 60
					} else {
						bi = (Math.abs(be) - 1) * 50;
						bs = (Math.abs(bd) - 1) * 50
					}
					if (aB == aI) {
						switch (bg) {
						case 0:
							bs += 27;
							bp = 21;
							bk = 6 - bs;
							break
						}
					} else {
						switch (bg) {
						case 0:
							bs += 17;
							bp = 16;
							bk = 6 - bs;
							break;
						case 1:
							bi += 36;
							bs += 42;
							bp = 16;
							bk = 6 - bs;
							break;
						case 2:
							bi += 37;
							bs += 42;
							bp = -6;
							bk = 6 - bs;
							break;
						case 3:
							bi += 15;
							bp = -6;
							bk = 12;
							break;
						case 4:
							bi += 15;
							bp = 37;
							bk = 12;
							break
						}
					}
					var bc = aU(bg, bi, bs);
					bc.style.left = bp + "px";
					bc.style.top = bk + "px";
					var bl = ce("div");
					bl.className = "talentcalc-arrow-anchor";
					ae(bl, bc);
					if (!bj.hidden) {
						bn.insertBefore(bl, bn.firstChild)
					}
					bj.arrow = bc
				}
			}
		}
	}
	function a9() {
		var bf, bd, be;
		y = ce("div");
		y.className = "talentcalc-upper rcorners";
		y.style.display = "none";
		ae(y, ce("tt"));
		ae(y, ce("strong"));
		ae(y, ce("var"));
		ae(y, ce("em"));
		bf = ce("div");
		bf.className = "talentcalc-upper-inner";
		bd = ce("span");
		bd.className = "talentcalc-upper-class";
		be = a4 = ce("b");
		if (aB == aI) {
			var bc = ce("a");
			bc.target = "_blank";
			ae(a4, bc);
			a4 = bc
		}
		ae(bd, be);
		ae(bd, ct(" "));
		V = ce("b");
		ae(bd, V);
		ae(bf, bd);
		bd = ce("span");
		bd.className = "talentcalc-upper-ptsleft";
		ae(bd, ct(LANG.tc_ptsleft));
		aH = ce("b");
		ae(bd, aH);
		ae(bf, bd);
		if (aB == aI) {
			be = I = ce("a");
			be.href = "javascript:;";
			be.onclick = E.bind(null, be);
			be.onmouseover = an.bind(null, be);
			be.onmousemove = Tooltip.cursorUpdate;
			be.onmouseout = Tooltip.hide;
			ae(bd, be)
		}
		bd = ce("span");
		bd.className = "talentcalc-upper-reqlevel";
		ae(bd, ct(LANG.tc_reqlevel));
		aR = ce("b");
		ae(bd, aR);
		ae(bf, bd);
		bd = ce("div");
		bd.className = "clear";
		ae(bf, bd);
		ae(y, bf);
		ae(z, y)
	}
	function x(bg) {
		var bc = "";
		var bf = [];
		for (var be = 0; be < bg.length; be += 2) {
			for (var bd = 0; bd < 2; ++bd) {
				bf[bd] = parseInt(bg.substring(be + bd, be + bd + 1));
				if (isNaN(bf[bd])) {
					bf[bd] = 0
				}
			}
			bc += ah.charAt(bf[0] * 6 + bf[1])
		}
		return bc
	}
	function h(bj, bf, bi) {
		var bh = M[bj.classId];
		if (bj.k > 0) {
			if (bj.links) {
				for (bd = 0; bd < bj.links.length; ++bd) {
					if (bh[bj.tree].t[bj.links[bd]].k) {
						return
					}
				}
			}
			var be = 0;
			bj.k--;
			for (var bd = 0; bd < bh[bj.tree].t.length; ++bd) {
				var bg = bh[bj.tree].t[bd];
				if (bg.k && bj.y != bg.y) {
					if (be < bg.y * ag) {
						bj.k++;
						return
					}
				}
				be += bg.k
			}
			bh[bj.tree].k--;
			bd = bh.k--;
			ba(bj.tree, bf, null, bj.classId);
			if (bf) {
				Y(bi, bj);
				if (bd >= R) {
					for (var bc = 0; bc < w; ++bc) {
						ba(bc, true, null, bj.classId)
					}
				}
				S()
			}
		}
	}
	function f(be) {
		var bc = f.L;
		if (bc == null) {
			bc = f.L = {};
			for (var bd in aw.L) {
				bc[aw.L[bd]] = bd
			}
		}
		return bc[be]
	}
	function aw(bc) {
		return aw.L[bc]
	}
	aw.L = {
		6 : 9,
		11 : 0,
		3 : 1,
		8 : 2,
		2 : 3,
		5 : 4,
		4 : 5,
		7 : 6,
		9 : 7,
		1 : 8
	};
	function aJ(bc) {
		return (bc >= 0 && bc <= 2 ? 1 : 2)
	}
	function aQ() {
		var bc = M[aF];
		if (aB == aI) {
			return Math.max(r ? 60 : 0, bc.k > 0 ? (bc.k - r) * 4 + 16 : 0)
		} else {
			return (bc.k ? bc.k + 9 : 0)
		}
	}
	function ar(bf, bd) {
		var bc = bf.d;
		var be = Math.max(0, bf.k - 1) + (bd ? 1 : 0);
		return bf.d[be]
	}
	function ak(bd, bc) {
		if (!J) {
			if (bc) {
				if (X(bd)) {
					F(this, bd)
				}
			} else {
				aa = bd;
				Lightbox.show("glyphpicker", {
					onShow: a3
				})
			}
		}
	}
	function a3(bj, bg, bc) {
		Lightbox.setSize(800, 564);
		var be;
		if (bg) {
			bj.className = "talentcalc-glyphpicker listview";
			var bd = [],
			bh = ce("div"),
			bi = ce("a"),
			bf = ce("div");
			bd.push({
				none: 1
			});
			for (var bk in g_glyphs) {
				bd.push(g_glyphs[bk])
			}
			bh.className = "listview";
			ae(bj, bh);
			bi.className = "screenshotviewer-close";
			bi.href = "javascript:;";
			bi.onclick = Lightbox.hide;
			ae(bi, ce("span"));
			ae(bj, bi);
			bf.className = "clear";
			ae(bj, bf);
			be = new Listview({
				template: "glyph",
				id: "glyphs",
				parent: bh,
				data: bd,
				customFilter: n
			});
			if (Browser.gecko) {
				aE(be.getClipDiv(), "DOMMouseScroll", ax)
			} else {
				be.getClipDiv().onmousewheel = ax
			}
		} else {
			be = g_listviews.glyphs;
			be.clearSearch();
			be.updateFilters(true)
		}
		setTimeout(function () {
			be.focusSearch()
		},
		1)
	}
	function ax(bc) {
		bc = $E(bc);
		if (bc._wheelDelta < 0) {
			this.scrollTop += 27
		} else {
			this.scrollTop -= 27
		}
	}
	function H(bd, bc) {
		if (J) {
			return
		}
		if (bc) {
			h(bd, true, this)
		} else {
			b(bd, true, this)
		}
	}
	function b(bg, bd, bf) {
		var be = M[bg.classId];
		if (be.k < R) {
			if (bg.enabled && bg.k < bg.m) {
				be.k++;
				be[bg.tree].k++;
				bg.k++;
				ba(bg.tree, bd, bg, bg.classId);
				if (bd) {
					Y(bf, bg);
					if (be.k == R) {
						for (var bc = 0; bc < w; ++bc) {
							if (bc != bg.tree) {
								ba(bc, bd, null, bg.classId)
							}
						}
					}
					S()
				}
			}
		} else {
			if (aB == aI && be.k == R && !bd) {
				m( - 1, 4, true);
				b(bg, bd, bf)
			}
		}
	}
	function o() {
		var bg, bi, be, bf = [];
		for (var bh in g_glyphs) {
			bf.push(bh)
		}
		bf.sort();
		for (var bd = 0, bc = bf.length; bd < bc; ++bd) {
			var bh = bf[bd];
			bg = g_glyphs[bh];
			bi = bg.classs;
			be = bg.type;
			if (!a0[bi]) {
				a0[bi] = {
					1 : [],
					2 : []
				}
			}
			bg.id = bh;
			bg.index = a0[bi][be].length;
			a0[bi][be].push(bg.id)
		}
	}
	function n(bc, be) {
		if (bc.none) {
			return true
		}
		var bd = M[aF];
		return (bc.classs == aF && bc.type == aJ(be != null ? be: aa) && !bd.glyphItems[bc.id])
	}
	function S() {
		if (aq) {
			clearTimeout(aq)
		}
		aq = setTimeout(al, 50)
	}
	function al() {
		var be = M[aF];
		if (!be) {
			return
		}
		A.mode = aB;
		A.classId = aF;
		A.locked = J;
		A.requiredLevel = aQ();
		A.pointsLeft = R - be.k;
		A.pointsSpent = (aB == aI ? be[0].k: [be[0].k, be[1].k, be[2].k]);
		A.bonusPoints = r;
		st(V, "(" + (aB == aI ? be.k: A.pointsSpent.join("/")) + ")");
		st(aR, A.requiredLevel ? A.requiredLevel: "-");
		st(aH, A.pointsLeft);
		if (J) {
			st(v, LANG.tc_unlock);
			v.className = "talentcalc-button-unlock"
		} else {
			st(v, LANG.tc_lock);
			v.className = "talentcalc-button-lock"
		}
		if (aB == aI) {
			if (r) {
				st(I, "[-]");
				I.className = "q10"
			} else {
				st(I, "[+]");
				I.className = "q2"
			}
		}
		if (at) {
			at.href = "?talent#" + aZ.getWhBuild() + ":" + aZ.getWhGlyphs()
		}
		for (var bc = 0; bc < w; ++bc) {
			var bd = aS[bc].firstChild.childNodes[1];
			st(bd, " (" + be[bc].k + ")")
		}
		if (aG) {
			aG(aZ, A, be)
		}
	}
	function aW() {
		st(a4, aY[aF]);
		if (aB == aI) {
			a4.href = "?pet=" + aF
		} else {
			a4.className = "c" + aF
		}
		if (K == 0) {
			j.style.display = "";
			aD.style.display = "";
			if (at) {
				at.style.display = ""
			}
			if (e) {
				e.style.display = ""
			}
			y.style.display = "";
			ay.style.display = ""
		}
		var be = M[aF];
		for (var bc = 0; bc < w; ++bc) {
			var bd = aS[bc].firstChild.childNodes[0];
			if (aB == a6) {
				bd.style.backgroundImage = "url(images/talent/classes/trees/" + g_file_classes[aF] + "_" + (bc + 1) + ".gif)"
			}
			st(bd, be[bc].n)
		}
		u();
		c(aF);
		S(); ++K
	}
	function aP(bk, bi) {
		var bj = M[bi];
		var bl = 0,
		bd = 0;
		var bf = null,
		bc;
		for (var bh = 0; bh < bk.length; ++bh) {
			var be = Math.min(parseInt(bk.charAt(bh)), bj[bl].t[bd].m);
			if (isNaN(be)) {
				continue
			}
			for (var bg = 0; bg < be; ++bg) {
				b(bj[bl].t[bd])
			}
			if (bf) {
				for (var bg = 0; bg < bc; ++bg) {
					b(bf)
				}
				bf = null
			}
			if (bj[bl].t[bd].k < be) {
				bf = bj[bl].t[bd];
				bc = be - bj[bl].t[bd].k
			}
			if (++bd > bj[bl].t.length - 1) {
				bd = 0;
				if (++bl > w - 1) {
					break
				}
			}
		}
	}
	function a(bk) {
		var be = ("" + bk).split(":", Z),
		bh = 0,
		bd = 0;
		for (var bf = 0, bg = be.length; bf < bg && bf < Z; ++bf) {
			var bc = be[bf],
			bi = g_glyphs[bc];
			if (bi) {
				var bj = -1;
				if (bi.type == 1) {
					if (bh < aj[1].length) {
						bj = aj[1][bh]; ++bh
					}
				} else {
					if (bd < aj[2].length) {
						bj = aj[2][bd]; ++bd
					}
				}
				if (bj != -1) {
					au(bj, bc, true)
				}
			} else {
				if (aJ(bf) == 1) {++bh
				} else {++bd
				}
			}
		}
	}
	function aA(bn, bk) {
		var bl = M[bk];
		var bo = 0,
		be = 0;
		var bm = [];
		var bg = null,
		bd;
		for (var bj = 0; bj < bn.length; ++bj) {
			var bc = bn.charAt(bj);
			if (bc != L) {
				var bf = ah.indexOf(bc);
				if (bf < 0) {
					continue
				}
				bm[1] = bf % 6;
				bm[0] = (bf - bm[1]) / 6;
				for (var bi = 0; bi < 2; ++bi) {
					bf = Math.min(bm[bi], bl[bo].t[be].m);
					for (var bh = 0; bh < bf; ++bh) {
						b(bl[bo].t[be])
					}
					if (bg) {
						for (var bh = 0; bh < bd; ++bh) {
							b(bg)
						}
						bg = null
					}
					if (bl[bo].t[be].k < bf) {
						bg = bl[bo].t[be];
						bd = bf - bl[bo].t[be].k
					}
					if (++be >= bl[bo].t.length) {
						break
					}
				}
			}
			if (be >= bl[bo].t.length || bc == L) {
				be = 0;
				if (++bo > w - 1) {
					return
				}
			}
		}
	}
	function B(bd) {
		var bg = 0;
		for (var be = 0, bc = bd.length; be < bc && be < Z; ++be) {
			var bf = bd.charAt(be);
			if (bf == "Z") {
				bg = 3;
				continue
			}
			au(bg, a0[aF][aJ(bg)][ah.indexOf(bf)], true); ++bg
		}
	}
	function O(be) {
		if (!Browser.ie6) {
			return
		}
		if (!aK) {
			var bd = aK = ce("div");
			bd.style.position = "absolute";
			bd.style.left = bd.style.top = "-2323px";
			bd.style.visibility = "hidden";
			ae(ge("layers"), bd)
		}
		var bh = M[be];
		for (var bc = 0; bc < w; ++bc) {
			var bg = ce("img"),
			bf = ce("img");
			if (aB == aI) {
				bg.src = "http://static.wowhead.com/images/pet/petcalc" + (g_locale.id == 25 ? "-ptr": "") + "/bg_" + (bc + 1) + ".jpg";
				bf.src = "http://static.wowhead.com/images/pet/petcalc" + (g_locale.id == 25 ? "-ptr": "") + "/icons_" + (bc + 1) + ".jpg"
			} else {
				bg.src = "images/talent/classes/backgrounds/" + g_file_classes[be] + "_" + (bc + 1) + ".jpg";
				//bf.src = "images/talent/classes/icons" + (g_locale.id == 25 ? "-ptr": "") + "/" + g_file_classes[be] + "_" + (bc + 1) + ".jpg"
			}
			ae(aK, bg);
			ae(aK, bf)
		}
	}
	function c(bd) {
		U();
		for (var bc = 0; bc < w; ++bc) {
			ba(bc, true, null, bd)
		}
	}
	function U() {
		if (aB != a6) {
			return
		}
		var bc = 0;
		for (var bd = 0; bd < Z; ++bd) {
			if (aX(bd)) {++bc
			}
		}
		e.style.display = (bc == 0 && J && t.profiler ? "none": "")
	}
	function T(be, bd) {
		if (M[be] == null) {
			bd.n = be;
			M[be] = bd;
			var bf = M[be];
			bf.glyphs = [];
			bf.glyphItems = {};
			P(be);
			if (g && g.classId == be) {
				for (var bc = 0; bc < w; ++bc) {
					ba(bc, false, null, be)
				}
				if (g.wh || g.blizz) {
					J = true;
					if (g.wh) {
						aA(g.wh, be)
					} else {
						aP(g.blizz, be)
					}
				}
			} else {
				J = false
			}
			g = null;
			if (a7 && a7.classId == be) {
				if (a7.wh) {
					B(a7.wh)
				} else {
					a(a7.blizz)
				}
			}
			a7 = null;
			if (be == aF) {
				aW();
				bf.div.style.display = "";
				for (var bc = 0; bc < w; ++bc) {
					ba(bc, true, null, be)
				}
			}
		}
	}
	function X(be, bc) {
		var bd = M[aF];
		if (bd.glyphs[be]) {
			bd.glyphItems[bd.glyphs[be]] = 0;
			bd.glyphs[be] = 0;
			if (!bc) {
				aX(be);
				S()
			}
			return true
		}
	}
	function am(bc) {
		a5(bc);
		aL();
		c(bc)
	}
	function a5(bd) {
		if (aB == aI) {
			m( - 1, 0, true)
		}
		for (var bc = 0; bc < w; ++bc) {
			aV(bc, bd, false)
		}
	}
	function aL(bc) {
		var be = M[aF];
		if (!be) {
			return
		}
		for (var bd = 0; bd < Z; ++bd) {
			X(bd, !bc)
		}
		U()
	}
	function aV(bc, bf, be) {
		var bg = M[bf];
		var bd;
		for (bd = 0; bd < bg[bc].t.length; ++bd) {
			bg[bc].t[bd].k = 0
		}
		bd = (bg.k < R);
		bg.k -= bg[bc].k;
		bg[bc].k = 0;
		if (be) {
			if (bd) {
				ba(bc, true, null, bf)
			} else {
				for (bc = 0; bc < w; ++bc) {
					ba(bc, true, null, bf)
				}
			}
			S()
		}
	}
	function af() {
		if (aC) {
			if (aC.wh) {
				aN(aC.wh)
			} else {
				Q(aC.classId, aC.blizz)
			}
		}
		if (N) {
			if (N.wh) {
				G(N.wh)
			}
		}
	}
	function Q(bc, bd) {
		if (aY[bc] == null) {
			return
		}
		if (!bd) {
			return
		}
		J = true;
		if (!aC) {
			aC = {
				classId: bc,
				blizz: bd
			};
			d.style.display = ""
		}
		if (M[bc]) {
			a5(bc);
			c(bc);
			aP(bd, bc);
			c(bc)
		} else {
			g = {
				classId: bc,
				blizz: bd
			}
		}
		if (!q(bc)) {
			S()
		}
	}
	function a8(bc) {
		if (!bc) {
			return
		}
		if (M[aF]) {
			aL();
			a(bc);
			U();
			S()
		} else {
			a7 = {
				classId: aF,
				blizz: bc
			}
		}
	}
	function ab(bc) {
		if (isNaN(bc) || (bc != 0 && bc != 4)) {
			return
		}
		m( - 1, bc)
	}
	function q(bc) {
		if (aY[bc] == null) {
			return
		}
		if (bc != aF) {
			ad = aF;
			aF = bc;
			if (aB == aI && M[bc] == null) {
				T(bc, bb(bc))
			} else {
				if (M[bc]) {
					aW();
					var bd = M[bc];
					bd.div.style.display = ""
				} else {
					O(bc);
					g_ajaxIshRequest("?data=talents&class=" + bc + "&" + C)
				}
			}
			if (M[ad]) {
				M[ad].div.style.display = "none"
			}
			return true
		}
	}
	function ai(bc) {
		if (J != bc) {
			J = bc;
			c(aF);
			S()
		}
	}
	function m(be, bf, bd) {
		var bc = R;
		if (be == -1) {
			be = aT
		}
		if (bf == -1) {
			bf = r
		}
		aT = be;
		r = bf;
		R = be + bf;
		if (aF != -1) {
			if (R < bc) {
				u()
			}
			c(aF);
			if (!bd) {
				S()
			}
		}
	}
	function aN(bg) {
		if (!bg) {
			return
		}
		var bc = bg,
		bd = false,
		be;
		if (aB == aI) {
			var bh = ah.indexOf(bg.charAt(0));
			if (bh >= 0 && bh <= 4) {
				var bf = ah.indexOf(bg.charAt(1));
				if (bf % 2 == 1) {
					m( - 1, 4, true); --bf
				} else {
					m( - 1, 0, true)
				}
				be = bh * 10 + (bf / 2);
				if (g_pet_families[be] != null) {
					bg = bg.substr(2);
					bd = true
				}
			}
		} else {
			var bh = ah.indexOf(bg.charAt(0));
			if (bh >= 0 && bh <= 27) {
				var bf = bh % 3,
				be = (bh - bf) / 3;
				be = f(be);
				if (be != null) {
					bg = bg.substr(1);
					bd = true
				}
			}
		}
		if (bd) {
			if (bg.length) {
				J = true;
				if (!aC) {
					aC = {
						wh: bc
					};
					d.style.display = ""
				}
			}
			if (M[be]) {
				a5(be);
				aA(bg, be);
				c(be)
			} else {
				g = {
					classId: be,
					wh: bg
				}
			}
			if (!q(be)) {
				S()
			}
			return be
		}
	}
	function G(bc) {
		if (!bc) {
			return
		}
		if (!N) {
			N = {
				wh: bc
			}
		}
		if (M[aF]) {
			aL();
			B(bc);
			U();
			S()
		} else {
			a7 = {
				classId: aF,
				wh: bc
			}
		}
	}
	function F(be, bd) {
		var bc = M[aF];
		upper = "",
		lower = "";
		glyph = g_glyphs[bc.glyphs[bd]];
		if (glyph) {
			upper += "<b>" + glyph.name + "</b>";
			upper += '<br /><span class="q9">' + LANG[bd <= 2 ? "tc_majgly": "tc_mingly"] + "</span>";
			lower += '<span class="q">' + glyph.description + "</span>";
			if (!J) {
				lower += '<br /><span class="q10">' + LANG[a2 ? "tc_remgly2": "tc_remgly"] + "</span>"
			}
		} else {
			upper += '<b class="q0">' + LANG.tc_empty + "</b>";
			upper += '<br /><span class="q9">' + LANG[bd <= 2 ? "tc_majgly": "tc_mingly"] + "</span>";
			if (!J) {
				lower += '<span class="q2">' + LANG.tc_addgly + "</span>"
			}
		}
		if (glyph && be.parentNode.className.indexOf("icon") != 0) {
			Tooltip.setIcon(glyph.icon)
		} else {
			Tooltip.setIcon(null)
		}
		Tooltip.show(be, "<table><tr><td>" + upper + "</td></tr></table><table><tr><td>" + lower + "</td></tr></table>")
	}
	function Y(bf, be) {
		var bd = M[be.classId],
		bc = "<table><tr><td><b>";
		if (be.z) {
			bc += '<span style="float: right" class="q0">' + be.z + "</span>"
		}
		bc += be.n + "</b><br />" + sprintf(LANG.tc_rank, be.k, be.m) + "<br />";
		if (be.r) {
			if (bd[be.tree].t[be.r[0]].k < be.r[1]) {
				bc += '<span class="q10">';
				bc += sprintf(LANG[be.r[1] == 1 ? "tc_prereq": "tc_prereqpl"], be.r[1], bd[be.tree].t[be.r[0]].n);
				bc += "</span><br />"
			}
		}
		if (bd[be.tree].k < be.y * ag) {
			bc += '<span class="q10">' + sprintf(LANG.tc_tier, (be.y * ag), bd[be.tree].n) + "</span><br />"
		}
		if (be.t && be.t.length >= 1) {
			bc += be.t[0]
		}
		bc += "</td></tr></table><table><tr><td>";
		if (be.t && be.t.length > 1) {
			bc += be.t[1] + "<br />"
		}
		bc += '<span class="q">' + ar(be) + "</span><br />";
		if (J) {} else {
			if (be.enabled) {
				if (!be.k) {
					bc += '<span class="q2">' + LANG.tc_learn + "</span><br />"
				} else {
					if (be.k == be.m) {
						bc += '<span class="q10">' + LANG[a2 ? "tc_unlearn2": "tc_unlearn"] + "</span><br />"
					}
				}
				if (be.k && be.k < be.m) {
					bc += "<br />" + LANG.tc_nextrank + '<br /><span class="q">' + ar(be, 1) + "</span><br />"
				}
			}
		}
		bc += "</td></tr></table>";
		Tooltip.show(bf, bc)
	}
	function av(bc) {
		if (g_locale.id == 0 || g_locale.id == 25) {
			return bc.substr(9)
		}
		return bc
	}
	function s() {
		J = !J;
		c(aF);
		S();
		return J
	}
	function aX(bh) {
		var bg = M[aF],
		bd = p[bh],
		bf = Icon.getLink(bd),
		bc = D[bh];
		if (bg.glyphs[bh]) {
			var be = g_glyphs[bg.glyphs[bh]];
			Icon.setTexture(bd, 1, be.icon);
			bc.href = bf.href = "?item=" + be.id;
			st(bc, av(be.name));
			bc.className = "q1";
			return true
		} else {
			Icon.setTexture(bd, 1, "inventoryslot_empty");
			bc.href = bf.href = "javascript:;";
			st(bc, LANG.tc_empty);
			bc.className = "q0";
			return false
		}
	}
	function ba(bl, bh, bd, bi) {
		var bj = M[bi];
		var bg;
		var bc;
		if (!bd || bj.k == R) {
			bc = 0;
			bg = R - 21
		} else {
			bc = bd.i;
			bg = Math.floor(bj[bl].k / 5) * 5 + 5
		}
		if (bd != null && bd.links != null) {
			for (var be = 0, bf = bd.links.length; be < bf; ++be) {
				if (bc > bd.links[be]) {
					bc = bd.links[be]
				}
			}
		}
		for (var be = bc; be < bj[bl].t.length; ++be) {
			bd = bj[bl].t[be];
			if (bj.k == R && !bd.k) {
				bd.enabled = 0
			} else {
				if (bj[bl].k >= bd.y * ag) {
					if (bd.r) {
						if (bj[bl].t[bd.r[0]].k >= bd.r[1]) {
							bd.enabled = 1
						} else {
							bd.enabled = 0
						}
					} else {
						bd.enabled = 1
					}
				} else {
					bd.enabled = 0
				}
			}
			if (bh) {
				if (bd.enabled && (!J || bd.k)) {
					if ((bd.k == bd.m)) {
						bd.border.style.backgroundPosition = "-42px 0";
						bd.bubble.style.color = "#E7BA00"
					} else {
						bd.border.style.backgroundPosition = "-84px 0";
						bd.bubble.style.color = "#17FD17"
					}
					//Icon.moveTexture(bd.icon, 1, be, 0);
					Icon.setTexture(bd.icon, 1, bd.iconname);
					bd.link.className = "bubbly";
					bd.bubble.style.visibility = "visible";
					if (bd.r) {
						var bk = bd.arrow.firstChild;
						if (bk.className.charAt(bk.className.length - 1) != "2") {
							bk.className += "2"
						}
					}
				} else {
					bd.border.style.backgroundPosition = "0 0";
					//Icon.moveTexture(bd.icon, 1, be, 1);
					Icon.setTexture(bd.icon, 1, "?data=talent-icon&icon=" + bd.iconname);
					bd.link.className = "";
					bd.bubble.style.visibility = "hidden";
					if (bd.r) {
						var bk = bd.arrow.firstChild;
						if (bk.className.charAt(bk.className.length - 1) == "2") {
							bk.className = bk.className.substr(0, bk.className.length - 1)
						}
					}
				}
				bd.bubble.firstChild.nodeValue = bd.k;
				bd.link.href = "?spell=" + bd.s[Math.max(0, bd.k - 1)]
			}
		}
	}
}
TalentCalc.MODE_DEFAULT = 0;
TalentCalc.MODE_PET = 1;
Listview.templates.glyph = {
	sort: [1],
	nItemsPerPage: -1,
	hideBands: 2,
	hideNav: 1 | 2,
	hideHeader: 1,
	searchable: 1,
	searchDelay: 100,
	poundable: 0,
	filtrable: 0,
	clip: {
		w: 780,
		h: 486
	},
	onBeforeCreate: function () {
		this.applySort();
		if (!Browser.ie67) {
			this.template.columns[0].span = 2
		}
	},
	columns: [{
		id: "glyph",
		type: "text",
		align: "left",
		value: "name",
		compute: function (f, h, g) {
			if (f.none) {
				return
			}
			if (!Browser.ie67) {
				var c = ce("td");
				c.style.width = "1px";
				c.style.padding = "0";
				c.style.borderRight = "none";
				var d = Icon.create(f.icon, 0, null, "?item=" + f.id),
				e = Icon.getLink(d);
				ae(c, d);
				ae(g, c);
				h.style.borderLeft = "none";
				e.onclick = rf
			}
			var b = ce("a");
			b.style.fontFamily = "Verdana, sans-serif";
			b.onclick = rf;
			b.href = "?item=" + f.id;
			ae(b, ct($WowheadTalentCalculator.simplifyGlyphName(f.name)));
			h.style.whiteSpace = "nowrap";
			ae(h, b);
			g.onclick = $WowheadTalentCalculator.addGlyph.bind(null, f.id)
		},
		sortFunc: function (d, c, e) {
			if (d.none) {
				return - 1
			}
			return strcmp(d.name, c.name)
		}
	},
	{
		id: "description",
		type: "text",
		align: "left",
		value: "description",
		compute: function (a, c) {
			if (a.none) {
				return
			}
			var b = ce("div");
			b.className = "small crop";
			c.title = a.description;
			ae(b, ct(a.description));
			ae(c, b)
		}
	},
	{
		id: "skill",
		type: "text",
		align: "center",
		getValue: function (a) {
			if (a.none) {
				return
			}
			return g_spell_skills[a.skill]
		},
		compute: function (a, c, b) {
			if (a.none) {
				ee(b);
				b.onclick = $WowheadTalentCalculator.addGlyph.bind(null, 0);
				c.colSpan = (Browser.ie67 ? 3 : 4);
				c.style.fontWeight = "bold";
				c.style.textAlign = "center";
				return LANG.dash + LANG.tc_nonegly + LANG.dash
			}
			if (a.skill) {
				c.className = "small q0";
				c.style.whiteSpace = "nowrap";
				return g_spell_skills[a.skill]
			}
		}
	}]
};