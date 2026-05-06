/* Plasnox Search v1.5.0 */
( function ( $ ) {
	'use strict';

	var DELAY   = 300;
	var MIN_LEN = 2;

	var SVG_BLANK = '<svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>';
	var SVG_ARR   = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>';

	function PlsInstance( $wrapper ) {
		var self      = this;
		self.$wrapper = $wrapper;
		self.$toggle  = $wrapper.find( '.pls-toggle' ).first();
		self.$input   = $wrapper.find( '.pls-input' ).first();
		self.$results = $wrapper.find( '.pls-results' ).first();

		self.timer    = null;
		self.last     = '';
		self.focusIdx = -1;
		self.isOpen   = false;

		// Lê o modo correto para o viewport atual diretamente dos data attributes
		self.mode = self.getMode();

		// Estado inicial limpo
		self.$wrapper.removeClass( 'pls-open pls-mode-open pls-loading' );
		self.$results.removeClass( 'pls-open' );
		self.$input.val( '' ).attr( 'tabindex', '-1' );

		if ( self.mode === 'open' ) {
			self.$wrapper.addClass( 'pls-mode-open' );
			self.$input.attr( 'tabindex', '0' );
			self.isOpen = true;
		}

		self.bind();
	}

	PlsInstance.prototype.getMode = function () {
		var w = window.innerWidth;
		if ( w <= 767 ) {
			return this.$wrapper.data( 'mode-m' ) || 'closed';
		}
		if ( w <= 1024 ) {
			return this.$wrapper.data( 'mode-t' ) || 'closed';
		}
		return this.$wrapper.data( 'mode-d' ) || 'closed';
	};

	PlsInstance.prototype.bind = function () {
		var self = this;

		self.$toggle.on( 'click', function () {
			if ( self.mode === 'open' ) return;
			self.isOpen ? self.close() : self.open();
		} );

		self.$input.on( 'input', function () { self.onInput(); } );
		self.$input.on( 'keydown', function ( e ) { self.onKey( e ); } );

		// Fecha ao clicar fora
		$( document ).on( 'click touchend', function ( e ) {
			if ( ! self.isOpen ) return;
			if ( $( e.target ).closest( self.$wrapper ).length ) return;
			if ( self.mode === 'open' ) {
				self.closeResults();
			} else {
				self.close();
			}
		} );

		// Fecha ao clicar em resultado (modo ícone)
		self.$results.on( 'click', 'a.pls-item', function () {
			if ( self.mode !== 'open' ) { self.close(); }
		} );
	};

	PlsInstance.prototype.open = function () {
		this.isOpen = true;
		this.$wrapper.addClass( 'pls-open' );
		this.$toggle.attr( 'aria-expanded', 'true' );
		this.$input.attr( 'tabindex', '0' );
		var self = this;
		setTimeout( function () { self.$input.trigger( 'focus' ); }, 360 );
	};

	PlsInstance.prototype.close = function () {
		this.isOpen = false;
		this.$wrapper.removeClass( 'pls-open pls-loading' );
		this.$toggle.attr( 'aria-expanded', 'false' );
		this.$input.attr( 'tabindex', '-1' ).val( '' );
		this.closeResults();
		this.last = '';
		clearTimeout( this.timer );
	};

	PlsInstance.prototype.onInput = function () {
		var self = this;
		var q    = $.trim( self.$input.val() );
		clearTimeout( self.timer );
		if ( q.length < MIN_LEN ) { self.closeResults(); return; }
		self.timer = setTimeout( function () {
			if ( q !== self.last ) { self.last = q; self.doSearch( q ); }
		}, DELAY );
	};

	PlsInstance.prototype.onKey = function ( e ) {
		var self   = this;
		var $items = self.$results.find( 'a.pls-item' );
		switch ( e.key ) {
			case 'Escape':
				self.mode === 'open' ? self.closeResults() : self.close();
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
			data   : { action: 'plasnox_search', nonce: PlasSearch.nonce, query: q },
			success: function ( res ) {
				self.$wrapper.removeClass( 'pls-loading' );
				if ( res && res.success ) self.render( res.data );
			},
			error: function () { self.$wrapper.removeClass( 'pls-loading' ); },
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
		var meta = item.meta ? '<span class="pls-meta">' + esc( item.meta ) + '</span>' : '';
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

	// ── Init ─────────────────────────────────────

	function initAll() {
		$( '.pls-wrapper' ).each( function () {
			var $w = $( this );
			if ( ! $w.data( 'pls-init' ) ) {
				$w.data( 'pls-init', new PlsInstance( $w ) );
			}
		} );
	}

	$( document ).ready( initAll );

	// Limpa estado ao voltar via bfcache
	window.addEventListener( 'pageshow', function ( e ) {
		if ( ! e.persisted ) return;
		$( '.pls-wrapper' ).each( function () {
			var inst = $( this ).data( 'pls-init' );
			if ( inst && inst.mode !== 'open' ) { inst.close(); }
		} );
	} );

	// Suporte ao editor Elementor
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
