(function ($) {

'use strict';

/*!==========================================================================
 * ==========================================================================
 * ==========================================================================
 *
 * Rhye – AJAX Portfolio WordPress Theme
 *
 * [Table of Contents]
 *
 * 1. Arrow
 * 2. Form
 * 3. Header
 * 4. Preloader
 * 5. Mobile Bar Height
 * 6. Run On High Performance GPU
 *
 * ==========================================================================
 * ==========================================================================
 * ==========================================================================
 */

/**
 * Global Vars
 */
window.$document = $(document);
window.$window = $(window);
window.$body = $('body');
window.$html = $('html');
window.$spinner = $('#js-spinner');
window.$barbaWrapper = $('[data-barba="wrapper"]');
window.$pageHeader = $('#page-header');
window.$pageWrapper = $('#page-wrapper');
window.$pageContent = $('.page-wrapper__content');
window.$pagePreloader = $('#js-preloader');
window.$blockingCurtain = $('#js-page-blocking-curtain');
window.PagePreloader = new Preloader({
	scope: window.$document,
	target: window.$pagePreloader,
	curtain: {
		element: $('#js-page-transition-curtain'),
		background: $('.section-masthead').attr('data-background-color')
	},
	counter: {
		easing: 'power4.out',
		duration: 25,
		start: 0,
		target: 100,
		prefix: '',
		suffix: ''
	}
});

/**
 * Begin Page Load
 */
window.PagePreloader.start();

/**
 * Default Theme Options
 * Used to prevent errors if there is
 * no data provided from backend
 */
if (typeof window.theme === 'undefined') {
	window.theme = {
		ajax: {
			enabled: true,
			preventRules: '', // jQuery selectors of the elements to exclude them from AJAX transitions
			evalInlineContainerScripts: false,
			loadMissingScripts: true,
			loadMissingStyles: true
		},
		animations: {
			triggerHook: 0.85, // more info https://scrollmagic.io/docs/ScrollMagic.Scene.html#triggerHook
			timeScale: {
				onScrollReveal: 1, // on-scroll animations global speed
				overlayMenuOpen: 1, // fullscreen menu open speed
				overlayMenuClose: 1, // fullscreen menu close speed
				preloader: 0.9,
				ajaxFlyingImageTransition: 1,
				ajaxCurtainTransition: 1
			},
			scrollDown: {
				enabled: true,
				offsetDesktop: 0.0,
				offsetMobile: 0.0
			}
		},
		cursorFollower: {
			enabled: true,
			labels: {
				slider: 'Drag'
			},
			factorTrailing: 6,
			animationDuration: 0.25,
			elements: {
				socialItems: true,
				blogPagination: true
			},
		},
		smoothScroll: { // more info https://github.com/idiotWu/smooth-scrollbar/tree/develop/docs
			enabled: true,
			damping: 0.12,
			renderByPixels: true,
			continuousScrolling: false,
			plugins: {
				edgeEasing: true
			},
			lenisLerp: 0.1,
			anchorOffset: 'header',
			anchorOffsetCustom: 0,
		},
		contactForm7: {
			customModals: true
		},
		customJSInit: '',
		updateHeadNodes: '',
		mobileBarFix: {
			enabled: true,
			update: true
		},
		elementor: {
			isEditor: false
		},
		highPerformance: true,
		async: {
			enabled: true,
			assets: [],
			promises: []
		}
	}
}

/**
 * ScrollMagic Setup
 */
// window.SMController = new ScrollMagic.Controller();
// window.SMController.enabled(false); // don't start animations yet
window.SMSceneTriggerHook = window.theme.animations.triggerHook;
window.SMSceneReverse = false;

if (typeof window.setGoogleMapLoaded === 'undefined') {
	window.setGoogleMapLoaded = function () { };
}

if (typeof window.googleMapLoaded === 'undefined') {
	window.googleMapLoaded = new Promise((resolve) => {
		window.setGoogleMapLoaded = resolve;
	});
}

/**
 * Don't save scroll position
 * after AJAX transition
 */
if ('scrollRestoration' in history) {
	history.scrollRestoration = 'manual';
}


/**
 * Page Load Strategy
 */
document.addEventListener('DOMContentLoaded', (e) => {

	// init on AJAX transition
	if (e.detail) {

		initComponents(e.detail);

	} else { // init on initial page load

		initComponents({
			scope: window.$document,
			scrollToHashElement: false
		});

		initComponentsOnce({
			scope: window.$document
		});

	}

});

/**
 * Init Template Components
 * You can init your custom scripts here
 * in that function
 */
function initComponents({
	scope = window.$document,
	container = window.$pageWrapper,
	scrollToHashElement = true
}) {
	const
		$arrow = scope.find('.js-arrow'),
		$smoothScrollContainer = container.filter('.js-smooth-scroll'),
		$sectionBlog = scope.find('.section-blog.section-grid'),
		$section404 = scope.find('.section-404'),
		$scrollDown = scope.find('[data-arts-scroll-down]'),
		$changeTextHover = scope.find('.js-change-text-hover:not(.js-change-text-hover .js-change-text-hover)'), // exclude nested elements
		$sectionMasthead = scope.find('.section-masthead:not(.d-none):not(.js-cancel-init)'),
		$sectionNavPortfolioPrevNext = scope.find('#page-bottom-nav.section-list');

	if ($smoothScrollContainer.length) {
		window.rhye.components.AssetsManager
			.load(window.theme.assets['smooth-scrolling-js'])
			.finally(() => {
				new window.rhye.components.SmoothScroll({
					target: $smoothScrollContainer,
					adminBar: $('#wpadminbar'),
					absoluteElements: $('[data-arts-scroll-absolute]'), // correct handling of absolute elements OUTSIDE scrolling container
					fixedElements: $('[data-arts-scroll-fixed]') // correct handling of fixed elements INSIDE scrolling container
				});
			});
	}

	if ($sectionMasthead.length) {
		window.rhye.components.AssetsManager.load(window.theme.assets['section-masthead-js'])
			.finally(() => {
				new window.rhye.components.SectionMasthead({
					target: $sectionMasthead,
					scope
				});
			});
	}

	if ($scrollDown.length) {
		window.rhye.components.AssetsManager
			.loadScrollDown()
			.finally(() => {
				new window.rhye.components.ScrollDown({
					target: scope.find('[data-arts-scroll-down]'),
					scope,
					duration: 0.8
				});
			});
	}

	if ($arrow.length) {
		new window.rhye.components.Arrow({
			target: $arrow
		});
	}

	if ($changeTextHover.length) {
		window.rhye.components.AssetsManager
			.loadChangeTextHover()
			.finally(() => {
				new window.rhye.components.ChangeTextHover({
					target: $changeTextHover,
					scope,
					pageIndicator: scope.find('.js-page-indicator'), // fixed page indicator
					triggers: scope.find('.js-page-indicator-trigger'), // elements that triggers the change of page indicator
				});
			});
	}

	if ($sectionBlog.length) {
		if (typeof window.plugin === 'undefined') {
			$sectionBlog.attr('data-arts-os-animation', 'animated');
		} else {
			window.rhye.components.AssetsManager
				.loadMasonryGrid()
				.finally(() => {
					new window.rhye.components.SectionGrid({
						target: $sectionBlog,
						scope
					});

					new window.rhye.components.SectionContent({
						target: $sectionBlog,
						scope
					});
				});
		}
	}

	if ($section404.length) {
		if (typeof window.plugin === 'undefined') {
			$section404.attr('data-arts-os-animation', 'animated');
		} else {
			window.rhye.components.AssetsManager
				.loadSectionContent()
				.finally(() => {
					new window.rhye.components.SectionContent({
						target: $section404,
						scope
					});
				});
		}
	}

	if ($sectionNavPortfolioPrevNext.length) {
		window.rhye.components.AssetsManager
			.loadSectionList()
			.finally(() => {
				new window.rhye.components.SectionList({
					target: $sectionNavPortfolioPrevNext,
					scope
				});
			});
	}

	// mobile bottom bar height fix
	if (window.theme.mobileBarFix.enabled) {
		new MobileBarHeight();
	}

	// floating input fields
	new Form({
		target: scope,
		scope
	});

	// refresh animation triggers
	// for Waypoints library
	if (typeof Waypoint !== 'undefined') {
		Waypoint.refreshAll();
	}

	// custom JS code
	if (window.theme.customJSInit) {
		try {
			window.eval(window.theme.customJSInit);
		} catch (error) {
			console.warn(error);
		}
	}

	// scroll to anchor from URL hash
	if (scrollToHashElement) {
		window.rhye.components.Scroll.scrollToAnchorFromHash();
	}

}

/**
 * Init Template Components
 * only once after the initial
 * page load
 */
function initComponentsOnce({
	scope = window.$document,
	container = window.$pageWrapper,
	scrollToHashElement = true
}) {
	const $sectionNavProjects = container.find('.section-nav-projects');
	const $smoothScrollLenisContainer = container.filter('.js-smooth-scroll-lenis');

	if ($smoothScrollLenisContainer.length && !window.Modernizr.touchevents && !window.theme.elementor.isEditor) {
		window.rhye.components.AssetsManager
			.load(window.theme.assets['smooth-scrolling-lenis-js'])
			.finally(() => {
				new window.rhye.components.SmoothScrollLenis({
					target: $smoothScrollLenisContainer,
					adminBar: $('#wpadminbar')
				});
			});
	}

	// Init page header
	if (window.$pageHeader.length) {
		window.theme.Header = new Header({
			target: window.$pageHeader,
			scope
		});
	}

	// Init cursor only on non-touch browsers
	if (window.theme.cursorFollower.enabled && !window.Modernizr.touchevents) {
		let highlightElements;
		let exclusionString = '';
		const loaders = [
			window.rhye.components.AssetsManager.load(window.theme.assets['cursor-js']),
		];

		if (window.theme.cursorFollower.elements.socialItems) {
			exclusionString += ':not(.social__item a)';
		}

		if (window.theme.cursorFollower.elements.blogPagination) {
			exclusionString += ':not(a.page-numbers)';
		}

		highlightElements = `a:not(a[data-arts-cursor])${exclusionString}:not(.section-video__link):not(.no-highlight), button:not(button[data-arts-cursor]), .filter__item, .section-nav-projects__header, .section-nav-projects__next-image`;

		if (!window.theme.cursorFollower.elements.sliderDots) {
			highlightElements += ' ,.slider__dot';
		}

		if (!window.theme.cursorFollower.elements.circleArrows) {
			highlightElements += ' ,.js-arrow';
		}

		if (!document.head.querySelector('link[id="cursor-css"]')) {
			loaders.push(window.rhye.components.AssetsManager.load(window.theme.assets['cursor-css']));
		}

		Promise
			.all(loaders)
			.then(() => window.PagePreloader.finish())
			.finally(() => {
				window.rhye.components.Scroll.scrollToTop();

				new window.rhye.components.Cursor({
					scope: window.$document,
					target: $('#js-cursor'),
					cursorElements: '[data-arts-cursor]',
					highlightElements, // links to highlight
					highlightScale: 1.5, // default highlight scaling
					magneticElements: '[data-arts-cursor-magnetic]', // magnetic elements
					magneticScaleCursorBy: 1.3, // default magnetic scaling
					factorTrailing: window.theme.cursorFollower.factorTrailing,
					animDuration: window.theme.cursorFollower.animationDuration,
				});

				// begin animations
				window.rhye.functions.enableAnimations();
				// scroll to anchor from URL hash
				if (scrollToHashElement) {
					window.rhye.components.Scroll.scrollToAnchorFromHash();
				}
			});
	} else {
		window.PagePreloader.finish().finally(() => {
			// scroll to anchor from URL hash
			if (scrollToHashElement) {
				window.rhye.components.Scroll.scrollToAnchorFromHash();
			}

			// Begin animations
			window.rhye.functions.enableAnimations();
		});
	}

	// init AJAX navigation
	if (window.theme.ajax.enabled && window.$barbaWrapper.length) {
		window.rhye.components.AssetsManager
			.loadPJAX()
			.finally(() => {
				new window.rhye.components.PJAX({
					target: window.$barbaWrapper,
					scope: window.$document
				});
			});
	}

	// Init auto scroll navigation
	if ($sectionNavProjects.length) {
		window.rhye.components.AssetsManager
			.loadSectionNavProjects()
			.finally(() => {
				new window.rhye.components.SectionNavProjects({
					target: $sectionNavProjects,
					scope
				});
			});
	}

	// Compatibility for D&D Multiple File Upload for CF7 plugin
	if (jQuery('.wpcf7-drag-n-drop-file').length === 0) {
		document.addEventListener('wpcf7mailsent', function () {

			// Get input type file element
			var inputFile = jQuery('.wpcf7-drag-n-drop-file');
			var $form = inputFile.parents('form');

			// Reset upload list for multiple fields
			if (inputFile.length > 0) {
				jQuery.each(inputFile, function () {
					// Reset file counts
					localStorage.setItem(jQuery(this).attr('data-name') + '_count_files', 1);
				});
			} else {
				// Reset file counts
				localStorage.setItem(inputFile.attr('data-name') + '_count_files', 1);
			}

			// Remove status / progress bar
			jQuery('.dnd-upload-status', $form).remove();
			jQuery('.dnd-upload-counter span', $form).text('0');
			jQuery('span.has-error-msg', $form).remove();

		}, false);
	}

	// Try to use high performance GPU on dual-GPU systems
	runOnHighPerformanceGPU();
}

/*!========================================================================
	1. Arrow
	======================================================================!*/
window.rhye.components.Arrow = class Arrow extends window.rhye.components.Base {

	constructor({
		scope,
		target
	}) {
		super({
			target,
			scope
		});

	}

	run($el) {
		this._bindEvents($el);
	}

	set($el) {
		this.$circles = $el.find('.circle');
		this.initialSVGPath = '10% 90%';

		gsap.set(this.$circles, {
			clearProps: 'all',
		});

		gsap.set(this.$circles, {
			rotation: 180,
			drawSVG: this.initialSVGPath,
			transformOrigin: 'center center',
		});
	}

	_bindEvents($el) {
		const
			$circle = $el.find(this.$circles),
			tl = new gsap.timeline();

		$el
			.on('mouseenter touchstart', () => {
				tl
					.clear()
					.to($circle, {
						duration: 0.3,
						drawSVG: '0% 100%',
						rotation: 180,
						transformOrigin: 'center center'
					});
			})
			.on('mouseleave touchend', () => {
				tl
					.clear()
					.to($circle, {
						duration: 0.3,
						drawSVG: this.initialSVGPath,
						rotation: 180,
						transformOrigin: 'center center'
					});
			});

	}

}

/*!========================================================================
	2. Form
	======================================================================!*/
class Form {
	constructor({
		scope,
		target
	}) {
		this.$scope = scope;
		this.$target = target;

		if (this.$scope.length) {
			this.set();
			this.run();
		}
	}

	set() {
		this.input = '.input-float__input';
		this.inputClassNotEmpty = 'input-float__input_not-empty';
		this.inputClassFocused = 'input-float__input_focused';
		this.$inputs = this.$scope.find(this.input);
	}

	run() {
		this._floatLabels();
		this._bindEvents();

		if (typeof window.theme !== 'undefined' && window.theme.contactForm7.customModals) {
			if ($('.wpcf7-form').length) {
				window.rhye.components.AssetsManager.loadBootstrapModal().finally(() => this._attachModalsEvents());
			}
		}
	}

	_floatLabels() {
		const self = this;

		if (!this.$inputs || !this.$inputs.length) {
			return false;
		}

		this.$inputs.each(function () {
			const
				$el = $(this),
				$controlWrap = $el.parent('.wpcf7-form-control-wrap');

			// not empty value
			if ($el.val()) {
				$el.addClass(self.inputClassNotEmpty);
				$controlWrap.addClass(self.inputClassNotEmpty);
				// empty value
			} else {
				$el.removeClass([self.inputClassFocused, self.inputClassNotEmpty]);
				$controlWrap.removeClass([self.inputClassFocused, self.inputClassNotEmpty]);
			}

			// has placeholder & empty value
			if ($el.attr('placeholder') && !$el.val()) {
				$el.addClass(self.inputClassNotEmpty);
				$controlWrap.addClass(self.inputClassNotEmpty);
			}
		});

	}

	_bindEvents() {
		const self = this;

		this.$scope
			.off('focusin')
			.on('focusin', self.input, function () {
				const
					$el = $(this),
					$controlWrap = $el.parent('.wpcf7-form-control-wrap');

				$el.addClass(self.inputClassFocused).removeClass(self.inputClassNotEmpty);
				$controlWrap.addClass(self.inputClassFocused).removeClass(self.inputClassNotEmpty);

			})
			.off('focusout')
			.on('focusout', self.input, function () {

				const
					$el = $(this),
					$controlWrap = $el.parent('.wpcf7-form-control-wrap');

				// not empty value
				if ($el.val()) {
					$el.removeClass(self.inputClassFocused).addClass(self.inputClassNotEmpty);
					$controlWrap.removeClass(self.inputClassFocused).addClass(self.inputClassNotEmpty);
				} else {
					// has placeholder & empty value
					if ($el.attr('placeholder')) {
						$el.addClass(self.inputClassNotEmpty);
						$controlWrap.addClass(self.inputClassNotEmpty);
					}

					$el.removeClass(self.inputClassFocused);
					$controlWrap.removeClass(self.inputClassFocused);
				}

			});

	}

	_attachModalsEvents() {
		window.$document.off('wpcf7submit').on('wpcf7submit', (e) => {

			const $modal = $('#modalContactForm7');
			let template;

			$modal.modal('dispose').remove();

			if (e.detail.apiResponse.status === 'mail_sent') {

				template = this._getModalTemplate({
					icon: 'icon-success.svg',
					message: e.detail.apiResponse.message,
				});

				this._createModal({
					template,
					onDismiss: () => {
						$(e.srcElement).find(this.input).parent().val('').removeClass(this.inputClassFocused).removeClass(this.inputClassNotEmpty);
					}
				});
			}

			if (e.detail.apiResponse.status === 'mail_failed') {
				template = this._getModalTemplate({
					icon: 'icon-error.svg',
					message: e.detail.apiResponse.message
				});

				this._createModal({ template });
			}

		});
	}

	_getModalTemplate({
		icon,
		message
	}) {
		return `
      <div class="modal fade" id="modalContactForm">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content radius-img">
            <div class="modal__close" data-dismiss="modal"><img src="${window.theme.themeURL}/img/general/icon-close.svg"/></div>
              <header class="text-center mb-1">
								<img src="${window.theme.themeURL}/img/general/${icon}" width="80px" height="80px" alt=""/>
                <p class="modal__message h4"><strong>${message}</strong></p>
              </header>
              <button type="button" class="button button_solid bg-dark-1 button_fullwidth" data-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    `;
	}

	_createModal({
		template,
		onDismiss
	}) {

		if (!template) {
			return false;
		}

		let $modal;
		window.$body.append(template);
		$modal = $('#modalContactForm');

		$modal.modal('show');
		$modal.on('hidden.bs.modal', () => {
			if (typeof onDismiss === 'function') {
				onDismiss();
			}
			$modal.modal('dispose').remove();
		});
	}

}

/*!========================================================================
	3. Header
	======================================================================!*/
class Header extends window.rhye.components.Base {
  constructor({
    scope,
    target
  }) {
    super({
      target,
      scope
    });
  }

  mount($el) {
    return new Promise((resolve) => {
      // prepare all the texts
      document.fonts.ready
        .then(() => window.rhye.components.SetText.splitText({
          target: $el.find('.js-split-text, .header__widget.split-text > *')
        }))
        .then(() => window.rhye.components.SetText.setLines({
          target: $el.find('.split-text[data-split-text-set="lines"]')
        }))
        .then(() => window.rhye.components.SetText.setWords({
          target: $el.find('.split-text[data-split-text-set="words"]')
        }))
        .then(() => window.rhye.components.SetText.setChars({
          target: $el.find('.split-text[data-split-text-set="chars"]')
        }))
        .finally(() => resolve(true));
    });
  }

  run() {
    this.overlayBackground = this.$el.attr('data-arts-header-overlay-background');
    this.stickyTheme = this.$stickyHeader.attr('data-arts-header-sticky-theme');

    if (typeof this.stickyScene !== 'undefined') {
      this.stickyScene.kill(true);
    }

    this.timeline = new gsap.timeline();

    this._correctTopOffset();
    this._stick();
    this._bindEvents();
    this._handleAnchors();
    this._runSmoothScrollOverlayMenu();
    this._closeOnResizeIfClassicLayout();
  }

  set() {
    this.$controls = this.$el.find('.header__controls');
    this.$stickyHeader = this.$el.filter('.js-header-sticky');
    this.$adminBar = $('#wpadminbar');
    this.$burger = $('#js-burger');
    this.$curtain = $('#js-header-curtain');
    this.$curtainTransition = $('#js-header-curtain-transition');
    this.$overlay = $('.header__wrapper-overlay-menu');
    this.burgerOpenClass = 'header__burger_opened';
    this.$headerColumns = this.$el.find('.header__col');
    this.$headerLeft = this.$el.find('.header__col-left');
    this.$overlayWidgets = this.$el.find('.header__wrapper-overlay-widgets');
    this.$allLinksOverlay = this.$el.find('.menu-overlay a');
    this.$allLinksClassic = this.$el.find('.menu a');
    this.$divider = this.$el.find('.header__wrapper-overlay-widgets__border');

    // Menu
    this.$menuClassic = this.$el.find('.menu');
    this.$menuOverlay = this.$overlay.find('.js-menu-overlay');
    this.$menuLinks = this.$overlay.find('.menu-overlay > li > a');
    this.selectedClass = 'selected';
    this.openClass = 'opened';

    // Submenu
    this.$submenu = this.$overlay.find('.menu-overlay .sub-menu');
    this.$submenuButton = $('#js-submenu-back');
    this.$submenuOpeners = this.$overlay.find('.menu-item-has-children > a');
    this.$submenuLinks = this.$submenu.find('> li > a');

    // Sticky
    this.stickyScene = undefined;
    this.stickyClass = 'header_sticky';

    // Scrollbar
    this.SB = undefined;

    gsap.fromTo(this.$headerColumns, {
      immediateRender: true,
      autoAlpha: 0,
    }, {
      delay: window.$pagePreloader.length ? 2.4 : 0,
      autoAlpha: 1,
      stagger: 0.2,
      duration: 0.6
    });

    this.setMenu();
  }

  setBurger(open = false) {
    if (open) {
      this.$el.addClass(this.openClass);
      this.$burger.addClass(this.burgerOpenClass);
    } else {
      this.$el.removeClass(this.openClass);
      this.$burger.removeClass(this.burgerOpenClass);
    }
  }

  setMenu() {
    if (this.$overlay.length) {
      this._scrollToTop(false);
      gsap.set(this.$overlay, {
        autoAlpha: 0,
        display: 'none'
      });
    }

    if (this.$submenu.length) {
      gsap.set(this.$submenu, {
        autoAlpha: 0
      });
    }

    if (this.$submenuButton.length) {
      gsap.set(this.$submenuButton, {
        autoAlpha: 0
      });
    }

    if (this.$divider.length) {
      gsap.set(this.$divider, {
        scaleX: 0
      });
    }

    this.$submenu.removeClass(this.openClass);
    this.$el.removeClass(this.openClass);
    this.$burger.removeClass(this.burgerOpenClass);

    if (this.$menuLinks.length) {
      gsap.effects.hideLines(this.$menuLinks, {
        autoAlpha: 1,
        y: '-100%',
        duration: 0,
      });
    }

    if (this.$submenuLinks.length) {
      gsap.effects.hideLines(this.$submenuLinks, {
        autoAlpha: 1,
        y: '-100%',
        duration: 0,
      });
    }

    if (this.$overlayWidgets.length) {
      gsap.effects.hideLines(this.$overlayWidgets, {
        autoAlpha: 1,
        y: this._isMediumScreen() ? '-100%' : '100%',
        duration: 0
      });
    }

    if (this.$curtain.length) {
      gsap.set(this.$curtain, {
        display: 'none',
        autoAlpha: 0
      });
    }

    if (typeof this.SB !== 'undefined') {
      this.SB.scrollTop = 0;
    }
  }

  openMenu() {
    this.overlayBackground = this.$el.attr('data-arts-header-overlay-background');

    return this.timeline
      .clear()
      .set(this.$curtain, {
        display: 'block',
      })
      .setCurtain(this.$curtain, {
        background: this.overlayBackground,
        y: '100%'
      })
      .set(this.$curtain.find('.curtain-svg'), {
        fill: this.overlayBackground
      })
      .set(this.$overlay, {
        autoAlpha: 1,
        display: 'flex'
      })
      .add([() => {
        this._setTransition(true);
        this._unstick();
      }])
      .set(this.$adminBar, {
        position: 'fixed',
      })
      .to(this.$headerLeft, {
        duration: 1.2,
        x: 30,
        autoAlpha: 0,
        ease: 'expo.inOut'
      }, 'start')
      .moveCurtain(this.$curtain, {
        duration: 1.2,
        y: '0%',
      }, 'start')
      .add(() => {
        this.$el.addClass(this.openClass);
      }, '-=0.6')
      .add([
        gsap.effects.animateLines(this.$menuLinks, {
          stagger: {
            amount: 0.2,
            from: 'end'
          },
          duration: 1.2,
          ease: 'power4.out'
        }),
        gsap.effects.animateLines(this.$overlayWidgets, {
          stagger: {
            amount: 0.2,
            from: this._isMediumScreen() ? 'end' : 'start'
          },
          duration: 1.2,
          ease: 'power4.out'
        }),
        gsap.to(this.$divider, {
          scaleX: 1,
          transformOrigin: 'center center',
          duration: 1.2,
          ease: 'expo.inOut'
        })
      ], '-=0.6')
      .add(() => {
        this._setTransition(false);
      }, '-=0.6')
      .timeScale(window.theme.animations.timeScale.overlayMenuOpen || 1);
  }

  closeMenu(force = false, cb) {
    if (!this.$el.hasClass(this.openClass) && !force) {
      return this.timeline;
    }

    const
      $submenuLinksCurrent = this.$submenu.filter(`.${this.openClass}`).find(this.$submenuLinks);

    return this.timeline
      .clear()
      .add(() => {
        this._setTransition(true);
        this._stick();

        if (typeof window.SB !== 'undefined' && window.SB.offset.y >= 1) {
          this.$stickyHeader.addClass(this.stickyClass);
        }
      })
      .set(this.$adminBar, {
        clearProps: 'position'
      })
      .to(this.$headerLeft, {
        duration: 1.2,
        x: 0,
        autoAlpha: 1,
        ease: 'expo.inOut'
      }, 'start')
      .to(this.$submenuButton, {
        x: -10,
        autoAlpha: 0,
        duration: 0.3,
        ease: 'expo.inOut'
      }, 'start')
      .moveCurtain(this.$curtain, {
        duration: 1.2,
        y: '-100%',
        curve: 'bottom'
      }, 'start')
      .add(() => {
        this.$el.removeClass(this.openClass);
      }, '-=0.9')
      .add([
        gsap.effects.hideLines([$submenuLinksCurrent, this.$menuLinks, this.$overlayWidgets], {
          stagger: {
            amount: 0,
            from: 'end'
          },
          y: '100%',
          duration: 0.6,
        }),
        gsap.to(this.$divider, {
          scaleX: 0,
          transformOrigin: 'center center',
          duration: 0.6,
          ease: 'expo.inOut'
        })
      ], 'start')
      .add(() => {
        if (typeof cb === 'function') {
          cb();
        }
        this.$el.attr('data-arts-header-animation', '');
      }, '-=0.3')
      .add(() => {
        this.setMenu();
      })
      .timeScale(window.theme.animations.timeScale.overlayMenuClose || 1);
  }

  closeMenuTransition(force = false) {
    if (!this.$el.hasClass(this.openClass) && !force) {
      return this.timeline;
    }

    const
      $submenuLinksCurrent = this.$submenu.filter(`.${this.openClass}`).find(this.$submenuLinks);

    return this.timeline
      .clear()
      .add(() => {
        this._setTransition(true);
        // Scroll.restoreScrollTop();
        this._stick();

        if (typeof window.SB !== 'undefined' && window.SB.offset.y >= 1) {
          this.$stickyHeader.addClass(this.stickyClass);
        }
      })
      .to(this.$headerLeft, {
        duration: 1.2,
        x: 0,
        autoAlpha: 1,
        ease: 'expo.inOut',
        clearProps: 'transform'
      }, 'start')
      .to(this.$submenuButton, {
        x: -10,
        autoAlpha: 0,
        duration: 0.3,
        ease: 'expo.inOut'
      }, 'start')
      .add(() => {
        this.$el.removeClass(this.openClass);
      }, '-=0.9')
      .add([
        gsap.effects.hideLines([$submenuLinksCurrent, this.$menuLinks, this.$overlayWidgets], {
          stagger: {
            amount: 0,
            from: 'end'
          },
          y: '100%',
          duration: 0.6,
        }),
        gsap.to(this.$divider, {
          scaleX: 0,
          transformOrigin: 'center center',
          duration: 0.6,
          ease: 'expo.inOut'
        })
      ], 'start')
      .add(() => {
        this.$el.attr('data-arts-header-animation', '');
      }, '-=0.3')
      .add(() => {
        this.setMenu();
      });
  }

  _bindEvents() {
    const self = this;

    if (this.$adminBar.length && this.$stickyHeader.length) {
      window.$window.on('resize', window.rhye.functions.debounce(() => {
        this._correctTopOffset();
      }, 250));
    }

    if (this.$burger.length) {
      this.$burger.off('click').on('click', (e) => {
        e.preventDefault();

        if (this._isInTransition()) {
          return;
        }

        if (this.$burger.hasClass(this.burgerOpenClass)) {
          this.closeMenu();
          this.$burger.removeClass(this.burgerOpenClass);
        } else {
          this.openMenu();
          this.$burger.addClass(this.burgerOpenClass);
        }
      });
    }

    if (this.$submenuOpeners.length) {
      this.$submenuOpeners.on('click', function (e) {
        e.preventDefault();

        if (self._isInTransition()) {
          return;
        }

        const
          $el = $(this),
          $currentMenu = $el.parents('ul'),
          $submenu = $el.next('.sub-menu');

        $el.addClass(self.linkSelectedClass);

        self._openSubmenu({
          submenu: $submenu,
          currentMenu: $currentMenu
        });
      });
    }

    if (this.$submenuButton.length) {
      this.$submenuButton.on('click', (e) => {
        e.preventDefault();

        if (self._isInTransition()) {
          return;
        }

        const $submenu = this.$submenu.filter(`.${this.openClass}`),
          $prevMenu = $submenu.parent('li').parent('ul');

        self._closeSubmenu({
          submenu: $submenu,
          currentMenu: $prevMenu
        });
      });
    }

    window.$window
      .on('arts/barba/transition/start', () => {
        this.$controls.addClass('pointer-events-none');
        this._unstick();
      })
      .on('arts/barba/transition/end', () => {
        this.$controls.removeClass('pointer-events-none');
      });
  }

  isOverlayOpened() {
    return this.$el.hasClass(this.openClass);
  }

  _isMediumScreen() {
    return true; //window.Modernizr.mq('(max-width: 991px)');
  }

  _isInTransition() {
    return this.$el.attr('data-arts-header-animation') === 'intransition';
  }

  _setTransition(inTransition = true) {
    return this.$el.attr('data-arts-header-animation', inTransition ? 'intransition' : '');
  }

  _correctTopOffset() {
    this.$adminBar = $('#wpadminbar');
    const top = this.$adminBar.length ? this.$adminBar.height() : 0;

    if (this.$stickyHeader.length && top > 0) {
      gsap.to(this.$el, {
        duration: 0.6,
        top
      });
    }
  }

  _stick() {
    if (!this.$stickyHeader.length) {
      return;
    }

    this.stickyScene = ScrollTrigger.create({
      trigger: window.$pageContent,
      start: `top+=1px top`,
      scrub: true,
      once: false,
      onToggle: ({
        isActive
      }) => {
        if (isActive) {
          this.$stickyHeader.addClass([this.stickyTheme, this.stickyClass].join(' '));
        } else {
          this.$stickyHeader.removeClass([this.stickyTheme, this.stickyClass].join(' '));
        }
      }
    });
  }

  _unstick() {
    if (!this.$stickyHeader.length || !this.stickyScene) {
      return;
    }

    this.stickyScene.kill(true);
    this.stickyScene = undefined;
    this.$stickyHeader.removeClass([this.stickyTheme, this.stickyClass].join(' '));
  }

  _openSubmenu({
    submenu,
    currentMenu
  }) {
    const
      $currentLinks = currentMenu.find('> li > a .menu-overlay__item-wrapper'),
      $submenuLinks = submenu.find('> li > a .menu-overlay__item-wrapper');

    this.timeline
      .clear()
      .add(() => {
        this._setTransition(true);
        this.$submenu.removeClass(this.openClass);
        submenu.not(this.$menuOverlay).addClass(this.openClass);
        this._scrollToTop();

        if (this.$submenu.hasClass(this.openClass)) {
          gsap.to(this.$submenuButton, {
            autoAlpha: 1,
            x: 0,
            duration: 0.3
          });

          if (this._isMediumScreen()) {
            gsap.effects.hideLines(this.$overlayWidgets, {
              stagger: {
                amount: 0.1,
                from: 'end'
              },
              y: '100%',
              duration: 1.2,
              ease: 'power4.out',
            });
            gsap.to(this.$divider, {
              scaleX: 0,
              transformOrigin: 'center center',
              duration: 0.6,
              ease: 'expo.inOut'
            });
          }
        } else {
          gsap.to(this.$submenuButton, {
            autoAlpha: 0,
            x: -10,
            duration: 0.3
          });

          gsap.effects.animateLines(this.$overlayWidgets, {
            stagger: {
              amount: 0.2,
              from: 'end'
            },
            duration: 1.2,
            ease: 'power4.out',
          });
        }
      })
      .set(submenu, {
        autoAlpha: 1,
        zIndex: 100
      })
      .add(gsap.effects.hideLines($currentLinks, {
        stagger: {
          amount: 0.2,
          from: 'end'
        },
        y: '100%',
        duration: 1.2,
        ease: 'power4.out'
      }))
      .add(gsap.effects.animateLines($submenuLinks, {
        stagger: {
          amount: 0.2,
          from: 'end'
        },
        duration: 1.2,
        ease: 'power4.out'
      }), '-=1.0')
      .add(() => {
        this.$menuLinks.removeClass(this.openClass);
        this._setTransition(false);
      }, '-=0.6')
      .timeScale(1.25);
  }

  _closeSubmenu({
    submenu,
    currentMenu
  }) {
    const
      $currentLinks = currentMenu.find('> li > a .menu-overlay__item-wrapper'),
      $submenuLinks = submenu.find('> li > a .menu-overlay__item-wrapper');

    this.timeline
      .clear()
      .add(() => {
        this._setTransition(true);
        this.$submenu.removeClass(this.openClass);
        currentMenu.not(this.$menuOverlay).addClass(this.openClass);
        this._scrollToTop();

        if (this.$submenu.hasClass(this.openClass)) {
          gsap.to(this.$submenuButton, {
            autoAlpha: 1,
            x: 0,
            duration: 0.3
          });

          if (this._isMediumScreen()) {
            gsap.effects.hideLines(this.$overlayWidgets, {
              stagger: {
                amount: 0.1,
                from: 'start'
              },
              y: '100%',
              duration: 1.2,
              ease: 'power4.out',
            });
          }
        } else {
          gsap.to(this.$submenuButton, {
            autoAlpha: 0,
            x: -10,
            duration: 0.3
          });

          gsap.effects.animateLines(this.$overlayWidgets, {
            stagger: {
              amount: 0.2,
              from: 'start'
            },
            duration: 1.2,
            ease: 'power4.out',
          });

          gsap.to(this.$divider, {
            scaleX: 1,
            transformOrigin: 'center center',
            duration: 1.2,
            ease: 'expo.inOut'
          });
        }
      })
      .set(submenu, {
        zIndex: -1
      })
      .add(gsap.effects.animateLines($currentLinks, {
        y: '100%',
        duration: 0
      }), 'start')
      .add(gsap.effects.hideLines($submenuLinks, {
        stagger: {
          amount: 0.1,
          from: 'start'
        },
        y: '-100%',
        duration: 1.2,
        ease: 'power4.out'
      }))
      .add(
        gsap.effects.animateLines($currentLinks, {
          stagger: {
            amount: 0.2,
            from: 'start'
          },
          duration: 1.2,
          ease: 'power4.out'
        }), '-=1.0')
      .set(submenu, {
        autoAlpha: 0,
      })
      .add(() => {
        this._setTransition(false);
      }, '-=0.6')
      .timeScale(1.25);
  }

  _handleAnchors() {

    const self = this;

    // overlay anchor links
    this.$allLinksOverlay.filter('a[href*="#"]:not([href="#"]):not([href*="#elementor-action"])').each(function () {
      const
        $current = $(this),
        url = $current.attr('href');

      self._scrollToAnchorFromMenu({
        element: $current,
        url,
        menu: 'overlay'
      });
    });

    // regular menu anchor links
    this.$allLinksClassic.filter('a[href*="#"]:not([href="#"]):not([href*="#elementor-action"])').each(function () {
      const
        $current = $(this),
        url = $current.attr('href');

      self._scrollToAnchorFromMenu({
        element: $current,
        url,
        menu: 'classic'
      });
    });

  }

  _scrollToTop(animate = true) {
    const
      x = 0,
      y = 0,
      duration = !!animate ? 600 : 0;

    if (typeof this.SB !== 'undefined') {
      if (this.SB.scrollTop > 0) {
        this.SB.scrollTo(x, y, duration, {
          easing: (pos) => window.rhye.components.Scroll.getEasingScroll(pos),
        });
      }
    } else {
      if (this.$overlay.length && this.$overlay.scrollTop() > 0) {
        this.$overlay.animate({
          scrollLeft: x,
          scrollTop: y
        }, duration, 'easeInOutExpo');
      }
    }
  }

  _scrollToAnchorFromMenu({
    element,
    url,
    menu = 'classic'
  }) {
    if (!url || !element) {
      return;
    }

    const filteredUrl = url.substring(url.indexOf('#'));

    try {
      if (filteredUrl.length) {
        const $el = window.$pageWrapper.find(filteredUrl);

        if ($el.length) {
          element.on('click', (e) => {
            e.stopPropagation();
            e.preventDefault();

            if (menu === 'classic') {
              window.rhye.components.Scroll.scrollTo({
                y: $el.offset().top - this.$el.innerHeight(),
                duration: 800
              });
            }

            if (menu === 'overlay') {
              this.closeMenu(false, () => {
                window.rhye.components.Scroll.scrollTo({
                  y: $el.offset().top - this.$el.innerHeight(),
                  duration: 800
                });
              });
            }
          });

        } else {
          element.off('click');
        }
      }
    } catch (error) {
      console.error('An error occured while handling menu anchor links: ' + error);
    }

  }

  _runSmoothScrollOverlayMenu() {
    if (!window.Modernizr.touchevents && this.$overlay.hasClass('js-smooth-scroll-container')) {
      window.rhye.components.AssetsManager
        .load(window.theme.assets['smooth-scrolling-js'])
        .finally(() => {
          this.SB = window.Scrollbar.init(this.$overlay[0], window.theme.smoothScroll);
        });
    }
  }

  _closeOnResizeIfClassicLayout() {
    if (!this.$menuClassic.length || !this.$menuOverlay.length || !this.$burger.length) {
      return;
    }

    window.$window.on('resize', window.rhye.functions.debounce(() => {
      if (this.$menuClassic.is(':visible') && !this.$burger.is(':visible') && this.isOverlayOpened()) {
        this.closeMenu();
      }
    }, 250));
  }
}

/*!========================================================================
	4. Preloader
	======================================================================!*/
function Preloader({
  scope = window.$document,
  target = $('#js-preloader'),
  curtain = {
    element: $('#js-page-transition-curtain'),
    background: $('.section-masthead').attr('data-background-color')
  },
  cursor = {
    element: $('#js-cursor'),
    offset: {
      top: 0.0,
      left: 0.0
    }
  },
  counter = {
    easing: 'power4.out',
    duration: 25,
    start: 0,
    target: 100,
    prefix: '',
    suffix: ''
  }
}) {

  const self = this;
  this.$scope = scope;
  this.$target = target;

  // Preloader
  this.$header = this.$target.find('.preloader__header');
  this.$content = this.$target.find('.preloader__content');
  this.$wrapperCounter = this.$target.find('.preloader__counter');
  this.$counter = this.$target.find('.preloader__counter-current');
  this.$wrapperCircle = this.$target.find('.preloader__circle');

   // Cursor
  this.cursor = cursor;

  if (this.cursor.element) {
    gsap.set(this.cursor.element, {
      autoAlpha: 1
    });
  }

  this.cursor.centerX = parseFloat(this.$wrapperCircle.innerWidth() / 2);
  this.cursor.centerY = parseFloat(this.$wrapperCircle.innerHeight() / 2);
  this.cursor.posX = 0;
  this.cursor.posY = 0;
  this.cursor.follower = {};
  this.cursor.follower.element = this.cursor.element.find('.cursor__follower');
  this.cursor.follower.inner = this.cursor.element.find('#inner');
  this.cursor.follower.outer = this.cursor.element.find('#outer');
  this.cursor.follower.size = {
    element: {
      width: this.cursor.follower.element.width(),
      height: this.cursor.follower.element.height()
    },
    inner: {
      cx: this.cursor.follower.inner.attr('cx'),
      cy: this.cursor.follower.inner.attr('cy'),
      r: this.cursor.follower.inner.attr('r')
    },
    outer: {
      cx: this.cursor.follower.outer.attr('cx'),
      cy: this.cursor.follower.outer.attr('cy'),
      r: this.cursor.follower.outer.attr('r')
    }
  }; // original circles dimensions

  // Mouse Coordinates
  this.mouseX = window.mouseX || window.innerWidth / 2;
  this.mouseY = window.mouseY || window.innerHeight / 2;

  // Curtain
  this.curtain = curtain;
  this.curtain.svg = this.curtain.element.find('.curtain-svg');
  this.curtain.rect = this.curtain.element.find('.curtain__rect');

  // Counter
  this.counter = counter;
  this.counter.val = 0;

  // Main Preloader Timeline
  this.timeline = new gsap.timeline({});

  // Animation Tweens
  this.tweens = {
    drawCircle: gsap.fromTo(this.cursor.follower.outer, {
      rotate: 90,
      drawSVG: '100% 100%',
      transformOrigin: 'center center',
    }, {
      drawSVG: '0% 100%',
      rotate: 0,
      transformOrigin: 'center center',
      ease: this.counter.easing,
      duration: this.counter.duration,
      paused: true,
    }),
    count: gsap.to(this.counter, {
      duration: this.counter.duration,
      val: this.counter.target,
      ease: this.counter.easing,
      paused: true,
      onUpdate: () => {
        const value = parseFloat(this.counter.val).toFixed(0);
        this.$counter.text(this.counter.prefix + value + this.counter.suffix);
      },
    }),
    followMouse: gsap.to({}, {
      paused: true,
      duration: 0.01,
      repeat: -1,
      onRepeat: () => {
        this.cursor.posX += (window.mouseX - this.cursor.posX);
        this.cursor.posY += (window.mouseY - this.cursor.posY - this.cursor.offset.top);
        gsap.to(this.cursor.element, {
          duration: 0.3,
          top: 0,
          left: 0,
          scale: (window.theme.cursorFollower.enabled && this.cursor.posX && this.cursor.posY) ? 1 : 0,
          autoAlpha: (this.cursor.posX && this.cursor.posY) ? 1 : 0,
          x: this.cursor.posX || window.innerWidth / 2,
          y: this.cursor.posY + this.cursor.offset.top || window.innerHeight / 2,
        });
      },
    })
  };

  _bindEvents();

  this.start = () => {
    window.dispatchEvent(new CustomEvent('arts/preloader/start'));

    if (!this.$target.length) {
      return;
    }

    window.$body.addClass('cursor-progress');

    if (this.cursor.element.length) {
      gsap.set(this.cursor.element, {
        display: 'block',
        top: '50%',
        left: '50%',
      });

      gsap.set(this.cursor.follower.element, {
        width: this.$wrapperCircle.innerWidth(),
        height: this.$wrapperCircle.innerHeight(),
      });

      gsap.set([this.cursor.follower.inner, this.cursor.follower.outer], {
        attr: {
          cx: this.cursor.centerX,
          cy: this.cursor.centerY,
          r: this.cursor.centerX - 1,
        }
      });
    }

    if (this.curtain.element.length) {
      gsap.set(this.curtain.svg, {
        fill: this.curtain.background
      });

      gsap.set(this.curtain.rect, {
        background: this.curtain.background
      });

      gsap.set(window.$pageContent, {
        autoAlpha: 0
      });
    }

    this.timeline.add([
      this.tweens.count.play(),
      this.tweens.drawCircle.play()
    ]);

  }

  this.finish = () => {
    return new Promise((resolve, reject) => {
      if (!this.$target.length) {
        window.dispatchEvent(new CustomEvent('arts/preloader/end'));
        resolve(true);
        return;
      }

      this.timeline
        .clear()
        .set(this.cursor.follower.outer, {
          attr: {
            transform: ''
          }
        })
        .to(this.cursor.follower.outer, {
          drawSVG: '0% 100%',
          rotate: 0,
          transformOrigin: 'center center',
          ease: 'expo.inOut',
          duration: 2.0
        }, 'start')
        .add([
          gsap.to(this.counter, {
            duration: 2.0,
            val: this.counter.target,
            ease: 'expo.inOut',
            onUpdate: () => {
              const value = parseFloat(this.counter.val).toFixed(0);
              this.$counter.text(this.counter.prefix + value + this.counter.suffix);
            }
          }),
        ], 'start')
        .add([
          this.tweens.followMouse.play(),
          gsap.to(this.cursor.follower.element, {
            width: this.cursor.follower.size.element.width,
            height: this.cursor.follower.size.element.height,
            ease: 'expo.out',
            duration: 1.2
          }),
          gsap.to(this.cursor.follower.inner, {
            attr: this.cursor.follower.size.inner,
            ease: 'expo.out',
            duration: 1.2,
          }),
          gsap.to(this.cursor.follower.outer, {
            attr: this.cursor.follower.size.outer,
            ease: 'expo.out',
            autoAlpha: 0,
            duration: 1.2,
          }),
        ])
        .add([
          gsap.effects.moveCurtain(this.curtain.element, {
            duration: 1.2
          }),
          gsap.to(this.$content, {
            y: -30,
            delay: 0.1,
            duration: 0.8,
            ease: 'power3.inOut',
          }),
          gsap.to(this.$target, {
            delay: 0.2,
            display: 'none',
            duration: 0.8,
            ease: 'power3.inOut',
          })
        ], '-=1.2')
        .set(window.$pageContent, {
          autoAlpha: 1
        })
        .to(this.curtain.element, {
          autoAlpha: 0,
          delay: 0.4,
          duration: 0.3
        })
        .set([this.$target, this.curtain.element], {
          y: '-100%',
          display: 'none',
        })
        .set(this.cursor.element, {
          clearProps: 'top,left',
          x: '-50%',
          y: '-50%'
        })
        .add(() => {
          window.dispatchEvent(new CustomEvent('arts/preloader/end'));
          window.$body.removeClass('cursor-progress');
          this.tweens.followMouse.kill();
          resolve(true);
        }, '-=0.6')

    });
  }

  function _bindEvents() {
    self.$scope.on('mousemove', (e) => {
      window.mouseX = e.clientX;
      window.mouseY = e.clientY;
    });
  }

}

/*!========================================================================
	5. Mobile Bar Height
	======================================================================!*/
class MobileBarHeight {
	constructor() {
		this.vh = 0;

		this._setVh();
		if (window.theme.mobileBarFix.update) {
			this._bindEvents();
		}
	}

	_setVh() {
		this.vh = document.documentElement.clientHeight * 0.01;
		document.documentElement.style.setProperty('--fix-bar-vh', `${this.vh}px`);
	}

	_bindEvents() {
		const event = window.Modernizr.touchevents ? 'orientationchange' : 'resize';

		window.$window.on(event, window.rhye.functions.debounce(() => {
			this._setVh();
		}, 250))
			.on('arts/barba/transition/clone/before', () => {
				this._setVh();
			});
	}
}

/*!========================================================================
	6. Run On High Performance GPU
	======================================================================!*/
function runOnHighPerformanceGPU() {
	if (!window.Modernizr.touchevents && window.theme.highPerformance) {
		let element = document.getElementsByTagName('canvas')[0];

		if (typeof element === 'undefined' || element === null) {
			element = document.createElement('canvas');
			element.getContext('webgl', {
				powerPreference: 'high-performance'
			});
		}
	}

}


})(jQuery);
