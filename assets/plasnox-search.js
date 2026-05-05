/* Plasnox Search v1.4.0 — suporta múltiplas instâncias e modos responsive */
( function ( $ ) {
	'use strict';

	var DELAY   = 300;
	var MIN_LEN = 2;

	// Breakpoints padrão do Elementor
	var BP_TABLET = 1024;
	var BP_MOBILE = 767;

	var SVG_BLANK = '<svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>';
	var SVG_ARR   = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>';

	// ── Fábrica de instância ─────────────────────

	function PlsInstance( $wrapper ) {
		var self      = this;
		self.$wrapper = $wrapper;
		self.$toggle  = $wrapper.find( '.pls-toggle' ).first();
		self.$expand  = $wrapper.find( '.pls-expand' ).first();
		self.$input   = $wrapper.find( '.pls-input' ).first();
		self.$results = $wrapper.find( '.pls-results' ).first();

		self.timer    = null;
		self.last     = '';
		self.focusIdx = -1;
		self.isOpen   = false;

		self.modeD = $wrapper.data( 'mode-d' ) || 'closed';
		self.modeT = $wrapper.data( 'mode-t' ) || self.modeD;
		self.modeM = $wrapper.data( 'mode-m' ) || self.modeT;

		self.applyMode();
		self.bind();
	}

	PlsInstance.prototype.currentMode = function () {
		var w = window.innerWidth;
		if ( w <= BP_MOBILE )  return this.modeM;
		if ( w <= BP_TABLET )  return this.modeT;
		return this.modeD;
	};

	PlsInstance.prototype.applyMode = function () {
		var mode = this.currentMode();
		if ( mode === 'open' ) {
			this.$wrapper.addClass( 'pls-mode-open' ).removeClass( 'pls-open' );
			this.$input.attr( 'tabindex', '0' );
			this.isOpen = true;
		} else {
			this.$wrapper.removeClass( 'pls-mode-open pls-open' );
			this.isOpen = false;
			this.$input.attr( 'tabindex', '-1' );
		}
	};

	PlsInstance.prototype.bind = function () {
		var self = this;

		self.$toggle.on( 'click', function () { self.onToggle(); } );
		self.$input.on( 'input keyup', function () { self.onInput(); } );
		self.$input.on( 'keydown', function ( e ) { self.onKey( e ); } );
		self.$results.on( 'click', 'a.pls-item', function () {
			if ( self.currentMode() !== 'open' ) { self.closeSearch( true ); }
		} );

		$( document ).on( 'click touchstart', function ( e ) {
			if ( self.isOpen && ! $( e.target ).closest( self.$wrapper ).length ) {
				if ( self.currentMode() !== 'open' ) {
					self.closeSearch( true );
				} else {
					self.closeResults();
				}
			}
		} );

		var resizeTimer;
		$( window ).on( 'resize', function () {
			clearTimeout( resizeTimer );
			resizeTimer = setTimeout( function () { self.applyMode(); }, 150 );
		} );
	};

	PlsInstance.prototype.onToggle = function () {
		if ( this.currentMode() === 'open' ) return; // modo sempre aberto: toggle não faz nada
		this.isOpen ? this.closeSearch( true ) : this.openSearch();
	};

	PlsInstance.prototype.openSearch = function () {
		this.isOpen = true;
		this.$wrapper.addClass( 'pls-open' );
		this.$toggle.attr( 'aria-expanded', 'true' );
		this.$input.attr( 'tabindex', '0' );
		var self = this;
		setTimeout( function () { self.$input.trigger( 'focus' ); }, 360 );
	};

	PlsInstance.prototype.closeSearch = function ( clearAll ) {
		this.isOpen = false;
		this.$wrapper.removeClass( 'pls-open pls-loading' );
		this.$toggle.attr( 'aria-expanded', 'false' );
		this.$input.attr( 'tabindex', '-1' );
		this.closeResults();
		if ( clearAll ) {
			this.$input.val( '' );
			this.last = '';
			clearTimeout( this.timer );
		}
	};

	PlsInstance.prototype.onInput = function () {
		var self = this;
		var q    = $.trim( self.$input.val() );
		clearTimeout( self.timer );

		if ( q.length < MIN_LEN ) {
			self.closeResults();
			return;
		}

		self.timer = setTimeout( function () {
			if ( q !== self.last ) {
				self.last = q;
				self.doSearch( q );
			}
		}, DELAY );
	};

	PlsInstance.prototype.onKey = function ( e ) {
		var self   = this;
		var $items = self.$results.find( 'a.pls-item' );

		switch ( e.key ) {
			case 'Escape':
				if ( self.currentMode() === 'open' ) {
					self.closeResults();
					self.$input.val( '' );
					self.last = '';
				} else {
					self.closeSearch( true );
					self.$toggle.trigger( 'focus' );
				}
				break;

			case 'ArrowDown':
				e.preventDefault();
				self.focusIdx = Math.min( self.focusIdx + 1, $items.length - 1 );
				self.highlight( $items );
				break;

			case 'ArrowUp':
				e.preventDefault();
				self.focusIdx = Math.max( self.focusIdx - 1, 0 );
				self.highlight( $items );
				break;

			case 'Enter':
				var $f = self.$results.find( 'a.pls-item.pls-focused' );
				if ( $f.length ) window.location.href = $f.attr( 'href' );
				break;
		}
	};

	PlsInstance.prototype.highlight = function ( $items ) {
		$items.removeClass( 'pls-focused' );
		if ( this.focusIdx >= 0 ) $items.eq( this.focusIdx ).addClass( 'pls-focused' );
	};

	PlsInstance.prototype.doSearch = function ( q ) {
		var self = this;
		self.$wrapper.addClass( 'pls-loading' );
		self.focusIdx = -1;

		$.ajax( {
			url    : PlasSearch.ajax_url,
			method : 'POST',
			data   : {
				action : 'plasnox_search',
				nonce  : PlasSearch.nonce,
				query  : q,
			},
			success: function ( res ) {
				self.$wrapper.removeClass( 'pls-loading' );
				if ( res && res.success ) self.render( res.data );
			},
			error: function () {
				self.$wrapper.removeClass( 'pls-loading' );
			},
		} );
	};

	PlsInstance.prototype.render = function ( data ) {
		var self = this;
		var i18n = PlasSearch.i18n;
		self.$results.empty();
		self.focusIdx = -1;
		var any = false;

		if ( data.products && data.products.length ) {
			any = true;
			self.$results.append( mkGroup( i18n.products ) );
			$.each( data.products, function ( i, item ) { self.$results.append( mkItem( item ) ); } );
		}
		if ( data.categories && data.categories.length ) {
			any = true;
			self.$results.append( mkGroup( i18n.categories ) );
			$.each( data.categories, function ( i, item ) { self.$results.append( mkItem( item ) ); } );
		}
		if ( data.solutions && data.solutions.length ) {
			any = true;
			self.$results.append( mkGroup( i18n.solutions ) );
			$.each( data.solutions, function ( i, item ) { self.$results.append( mkItem( item ) ); } );
		}
		if ( ! any ) {
			self.$results.append( '<p class="pls-empty">' + esc( i18n.no_results ) + '</p>' );
		}
		self.openResults();
	};

	PlsInstance.prototype.openResults  = function () { this.$results.addClass( 'pls-open' ); };
	PlsInstance.prototype.closeResults = function () { this.$results.removeClass( 'pls-open' ); this.focusIdx = -1; };

	// ── Helpers ──────────────────────────────────

	function mkGroup( label ) {
		return $( '<span>' ).addClass( 'pls-group' ).text( label );
	}

	function mkItem( item ) {
		var thumb = item.thumb
			? '<img class="pls-thumb" src="' + esc( item.thumb ) + '" alt="" loading="lazy">'
			: '<span class="pls-thumb-ph">' + SVG_BLANK + '</span>';
		var meta = item.meta
			? '<span class="pls-meta">' + esc( item.meta ) + '</span>'
			: '';
		return $( '<a>' )
			.addClass( 'pls-item' )
			.attr( 'href', item.url )
			.html(
				thumb +
				'<span class="pls-text"><span class="pls-title">' + esc( item.title ) + '</span>' + meta + '</span>' +
				'<span class="pls-arr" aria-hidden="true">' + SVG_ARR + '</span>'
			);
	}

	function esc( s ) {
		return String( s )
			.replace( /&/g, '&amp;' ).replace( /</g, '&lt;' )
			.replace( />/g, '&gt;' ).replace( /"/g, '&quot;' );
	}

	// ── Init: inicializa todas as instâncias na página ──

	function initAll() {
		$( '.pls-wrapper' ).each( function () {
			if ( ! $( this ).data( 'pls-init' ) ) {
				$( this ).data( 'pls-init', new PlsInstance( $( this ) ) );
			}
		} );
	}

	$( document ).ready( initAll );

	// Suporte ao editor do Elementor (atualiza quando o widget é renderizado)
	$( window ).on( 'elementor/frontend/init', function () {
		if ( window.elementorFrontend ) {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/plasnox_search.default', function ( $el ) {
				$el.find( '.pls-wrapper' ).each( function () {
					$( this ).removeData( 'pls-init' );
				} );
				initAll();
			} );
		}
	} );

}( jQuery ) );
