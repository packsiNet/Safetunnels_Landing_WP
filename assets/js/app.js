/**
 * SafeTunnels front-end.
 *
 * A dependency-free port of the behaviour that used to live in
 * TunnelNetworkPage.tsx, PurchaseModal.tsx and analytics.ts: blob parallax,
 * the FAQ accordion, the app carousel, review submission, the pricing toggle
 * and the RevenueCat checkout hand-off.
 *
 * Everything degrades gracefully — with JavaScript off the page still shows
 * every price, every answer and every download link.
 */
(function () {
	'use strict';

	var CFG = window.SAFETUNNELS || {};
	var GUID_RE = /^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i;

	var $ = function (selector, scope) {
		return (scope || document).querySelector(selector);
	};
	var $$ = function (selector, scope) {
		return Array.prototype.slice.call((scope || document).querySelectorAll(selector));
	};

	// ── API ────────────────────────────────────────────────────────────
	// With the proxy on, every call is same-origin through the WordPress REST
	// API; with it off, the browser talks to the backend directly, exactly
	// like the React build did.

	function apiUrl(proxyPath, directPath) {
		if (CFG.useProxy && CFG.restUrl) {
			return CFG.restUrl.replace(/\/$/, '') + proxyPath;
		}
		return (CFG.apiBase || '').replace(/\/$/, '') + directPath;
	}

	function request(url, options) {
		var opts = options || {};
		var headers = { Accept: 'application/json' };

		if (opts.body) {
			headers['Content-Type'] = 'application/json';
		}
		if (CFG.useProxy && CFG.nonce) {
			headers['X-WP-Nonce'] = CFG.nonce;
		}

		return fetch(url, {
			method: opts.method || 'GET',
			headers: headers,
			body: opts.body ? JSON.stringify(opts.body) : undefined,
			keepalive: !!opts.keepalive
		}).then(function (response) {
			return response.text().then(function (text) {
				var data = null;
				try {
					data = text ? JSON.parse(text) : null;
				} catch (e) {
					data = text;
				}
				if (!response.ok) {
					var message = data && data.message ? data.message : null;
					var error = new Error(message || 'request_failed');
					error.status = response.status;
					error.data = data;
					throw error;
				}
				return data;
			});
		});
	}

	// ── Analytics (fire and forget, never blocks the UI) ───────────────

	function trackView() {
		try {
			if (sessionStorage.getItem('st_visit')) {
				return;
			}
			sessionStorage.setItem('st_visit', '1');
		} catch (e) {
			/* private mode — count it anyway */
		}

		var url = apiUrl('/track', '/api/stats/login');
		var body = CFG.useProxy ? { type: 'view' } : undefined;

		request(url, { method: 'POST', body: body, keepalive: true })['catch'](function () {});
	}

	function trackDownload(packageName) {
		if (!packageName) {
			return;
		}

		var url = apiUrl('/track', '/api/stats/download-click');
		var body = CFG.useProxy
			? { type: 'download', packageName: packageName }
			: { packageName: packageName };

		request(url, { method: 'POST', body: body, keepalive: true })['catch'](function () {});
	}

	function bindDownloadTracking() {
		document.addEventListener('click', function (event) {
			var link = event.target.closest('[data-st-track="download"]');
			if (link) {
				trackDownload(link.getAttribute('data-st-package'));
			}
		});
	}

	// ── Background parallax ────────────────────────────────────────────

	function initBlobs() {
		var blobs = $$('.blob');
		if (!blobs.length || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
			return;
		}
		if (window.matchMedia('(pointer:coarse)').matches) {
			return;
		}

		var tX = 0;
		var tY = 0;
		var cX = 0;
		var cY = 0;

		window.addEventListener(
			'mousemove',
			function (event) {
				tX = event.clientX / window.innerWidth - 0.5;
				tY = event.clientY / window.innerHeight - 0.5;
			},
			{ passive: true }
		);

		(function frame() {
			cX += (tX - cX) * 0.06;
			cY += (tY - cY) * 0.06;
			blobs.forEach(function (blob, index) {
				var depth = (index + 1) * 7;
				blob.style.translate = -cX * depth + 'px ' + -cY * depth + 'px';
			});
			window.requestAnimationFrame(frame);
		})();
	}

	// ── FAQ accordion ──────────────────────────────────────────────────

	function initFaq() {
		$$('.faq-item').forEach(function (item) {
			var question = $('.faq-q', item);
			if (!question) {
				return;
			}

			question.addEventListener('click', function () {
				var open = item.classList.contains('open');

				// One panel at a time, matching the React original.
				var list = item.closest('.faq-list');
				if (list) {
					$$('.faq-item.open', list).forEach(function (other) {
						other.classList.remove('open');
						var button = $('.faq-q', other);
						if (button) {
							button.setAttribute('aria-expanded', 'false');
						}
					});
				}

				if (!open) {
					item.classList.add('open');
					question.setAttribute('aria-expanded', 'true');
				}
			});
		});
	}

	// ── App carousel ───────────────────────────────────────────────────

	function initCarousel() {
		var scroller = $('#st-apps-scroll');
		if (!scroller) {
			return;
		}

		$$('[data-st-scroll]').forEach(function (button) {
			button.addEventListener('click', function () {
				var direction = button.getAttribute('data-st-scroll') === 'left' ? -1 : 1;
				scroller.scrollBy({ left: direction * 320, behavior: 'smooth' });
			});
		});
	}

	// ── Mobile drawer ──────────────────────────────────────────────────

	function initDrawer() {
		var burger = $('#st-nav-burger');
		var drawer = $('#st-nav-drawer');
		if (!burger || !drawer) {
			return;
		}

		function close() {
			drawer.hidden = true;
			drawer.classList.remove('open');
			burger.setAttribute('aria-expanded', 'false');
			burger.classList.remove('open');
		}

		burger.addEventListener('click', function () {
			var open = burger.getAttribute('aria-expanded') === 'true';
			if (open) {
				close();
				return;
			}
			drawer.hidden = false;
			// Next frame, so the transition has a starting state to animate from.
			window.requestAnimationFrame(function () {
				drawer.classList.add('open');
			});
			burger.setAttribute('aria-expanded', 'true');
			burger.classList.add('open');
		});

		$$('a', drawer).forEach(function (link) {
			link.addEventListener('click', close);
		});

		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape') {
				close();
			}
		});
	}

	// ── Back to top ────────────────────────────────────────────────────

	function initBackToTop() {
		var button = $('#st-to-top');
		if (!button) {
			return;
		}

		function update() {
			button.hidden = window.scrollY < 700;
		}

		window.addEventListener('scroll', update, { passive: true });
		update();

		button.addEventListener('click', function () {
			window.scrollTo({ top: 0, behavior: 'smooth' });
		});
	}

	// ── Scroll reveal ──────────────────────────────────────────────────

	function initReveal() {
		var items = $$('.st-reveal');
		if (!items.length || !('IntersectionObserver' in window)) {
			return;
		}
		if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
			items.forEach(function (item) {
				item.classList.add('is-visible');
			});
			return;
		}

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						entry.target.classList.add('is-visible');
						observer.unobserve(entry.target);
					}
				});
			},
			{ rootMargin: '0px 0px -60px 0px' }
		);

		items.forEach(function (item) {
			observer.observe(item);
		});
	}

	// ── Modal plumbing ─────────────────────────────────────────────────

	var openModal = null;

	function showModal(modal) {
		if (!modal) {
			return;
		}
		openModal = modal;
		modal.hidden = false;
		document.body.style.overflow = 'hidden';

		var focusable = modal.querySelector('input, button:not([disabled])');
		if (focusable) {
			focusable.focus();
		}
	}

	function hideModal(modal) {
		var target = modal || openModal;
		if (!target) {
			return;
		}
		target.hidden = true;
		openModal = null;
		document.body.style.overflow = '';
	}

	function initModalDismissal() {
		document.addEventListener('click', function (event) {
			if (event.target.closest('[data-st-close]')) {
				hideModal(event.target.closest('.modal-overlay'));
				return;
			}

			var overlay = event.target.classList && event.target.classList.contains('modal-overlay')
				? event.target
				: null;
			if (overlay) {
				hideModal(overlay);
			}
		});

		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape' && openModal) {
				hideModal(openModal);
			}
		});
	}

	function setError(scope, message) {
		$$('[data-st-error]', scope).forEach(function (box) {
			var text = $('[data-st-error-text]', box);
			if (message) {
				if (text) {
					text.textContent = message;
				}
				box.hidden = false;
			} else {
				box.hidden = true;
			}
		});
	}

	function setBusy(button, busy) {
		if (!button) {
			return;
		}
		button.disabled = busy;
		$$('[data-st-submit-idle], [data-st-verify-idle], [data-st-pay-idle]', button).forEach(function (el) {
			el.hidden = busy;
		});
		$$('[data-st-submit-busy], [data-st-verify-busy], [data-st-pay-busy]', button).forEach(function (el) {
			el.hidden = !busy;
		});
	}

	// ── Review modal ───────────────────────────────────────────────────

	function initReviewModal() {
		var modal = $('#st-review-modal');
		if (!modal) {
			return;
		}

		var form = $('#st-review-form', modal);
		var nameInput = $('#st-review-name', modal);
		var commentInput = $('#st-review-comment', modal);
		var counter = $('[data-st-char-count]', modal);
		var submit = $('[data-st-submit]', modal);
		var hint = $('[data-st-rating-hint]', modal);
		var formWrap = $('[data-st-review-form]', modal);
		var success = $('[data-st-review-success]', modal);
		var rating = 0;

		function syncSubmit() {
			submit.disabled = !nameInput.value.trim() || rating === 0;
		}

		function paintStars(value) {
			$$('.star-btn', modal).forEach(function (star) {
				var starValue = parseInt(star.getAttribute('data-value'), 10);
				var on = starValue <= value;
				star.classList.toggle('active', on);
				star.setAttribute('aria-checked', starValue === rating ? 'true' : 'false');
				var icon = star.querySelector('i');
				if (icon) {
					icon.className = 'ti ' + (on ? 'ti-star-filled' : 'ti-star');
				}
			});
		}

		$$('.star-btn', modal).forEach(function (star) {
			var value = parseInt(star.getAttribute('data-value'), 10);
			star.addEventListener('mouseenter', function () {
				paintStars(value);
			});
			star.addEventListener('mouseleave', function () {
				paintStars(rating);
			});
			star.addEventListener('click', function () {
				rating = value;
				paintStars(rating);
				if (hint) {
					hint.hidden = true;
				}
				syncSubmit();
			});
		});

		nameInput.addEventListener('input', syncSubmit);

		commentInput.addEventListener('input', function () {
			if (counter) {
				counter.textContent = commentInput.value.length + ' / 1000';
			}
		});

		$$('[data-st-open="review"]').forEach(function (trigger) {
			trigger.addEventListener('click', function () {
				setError(modal, '');
				showModal(modal);
			});
		});

		form.addEventListener('submit', function (event) {
			event.preventDefault();

			if (!nameInput.value.trim() || rating === 0) {
				if (hint && rating === 0) {
					hint.hidden = false;
				}
				return;
			}

			setError(modal, '');
			setBusy(submit, true);

			var payload = {
				fullName: nameInput.value.trim(),
				rating: rating
			};
			if (commentInput.value.trim()) {
				payload.comment = commentInput.value.trim();
			}

			request(apiUrl('/reviews', '/api/reviews'), { method: 'POST', body: payload })
				.then(function () {
					formWrap.hidden = true;
					success.hidden = false;
				})
				['catch'](function (error) {
					setError(modal, (error && error.message !== 'request_failed' && error.message) || (CFG.i18n && CFG.i18n.reviewFailed) || 'Error posting comment');
				})
				.then(function () {
					setBusy(submit, false);
					syncSubmit();
				});
		});
	}

	// ── Pricing toggle ─────────────────────────────────────────────────

	function initPricingToggle() {
		var card = $('#st-premium-plan');
		if (!card || !CFG.plans) {
			return;
		}

		var priceEl = $('[data-st-price]', card);
		var perEl = $('[data-st-per]', card);
		var cadenceEl = $('[data-st-cadence]', card);
		var weeklyEl = $('[data-st-weekly]', card);
		var badgeEl = $('[data-st-badge]', card);
		var fallbackEl = $('[data-st-badge-fallback]', card);
		var ctaPrice = $('[data-st-cta-price]', card);
		var buyButton = $('[data-st-buy="premium"]', card);

		$$('[data-st-period]', card).forEach(function (button) {
			button.addEventListener('click', function () {
				var key = button.getAttribute('data-st-period');
				var plan = CFG.plans[key];
				if (!plan || plan.disabled) {
					return;
				}

				$$('[data-st-period]', card).forEach(function (other) {
					var active = other === button;
					other.classList.toggle('active', active);
					other.setAttribute('aria-selected', active ? 'true' : 'false');
				});

				if (priceEl) {
					priceEl.textContent = plan.price;
				}
				if (perEl) {
					perEl.textContent = plan.per;
				}
				if (cadenceEl) {
					cadenceEl.textContent = plan.cadence;
				}
				if (weeklyEl) {
					weeklyEl.textContent = plan.weeklyEq ? ' · ' + plan.weeklyEq : '';
					weeklyEl.hidden = !plan.weeklyEq;
				}
				if (badgeEl) {
					badgeEl.textContent = plan.badge || '';
					badgeEl.hidden = !plan.badge;
				}
				if (fallbackEl) {
					fallbackEl.hidden = !!plan.badge;
				}
				if (ctaPrice) {
					ctaPrice.textContent = plan.price;
				}
				if (buyButton) {
					buyButton.setAttribute('data-st-buy-period', key);
				}
			});
		});
	}

	// ── Purchase modal ─────────────────────────────────────────────────

	function initPurchaseModal() {
		var modal = $('#st-purchase-modal');
		if (!modal) {
			return;
		}

		var form = $('#st-purchase-form', modal);
		var keyInput = $('#st-purchase-key', modal);
		var verifyButton = $('[data-st-verify]', modal);
		var confirmWrap = $('[data-st-confirm]', modal);
		var confirmText = $('[data-st-confirm-text]', modal);
		var payButton = $('[data-st-pay]', modal);
		var resetButton = $('[data-st-reset]', modal);
		var planNameEl = $('[data-st-plan-name]', modal);
		var planPriceEl = $('[data-st-plan-price]', modal);

		var target = null;

		function planLabel(spec) {
			if (spec.kind === 'noads') {
				return {
					name: (CFG.i18n && CFG.i18n.noAdsName) || 'No Ads (Lifetime)',
					price: CFG.noads ? CFG.noads.price : ''
				};
			}
			var plan = CFG.plans[spec.period];
			return {
				name: ((CFG.i18n && CFG.i18n.premiumName) || 'Premium') + ' (' + plan.label + ')',
				price: plan.price
			};
		}

		function checkoutUrl(spec, key) {
			var base;
			var packageId;

			if (spec.kind === 'noads') {
				base = CFG.noads.link;
				packageId = CFG.noads.packageId;
			} else {
				base = CFG.plans[spec.period].link;
				packageId = CFG.plans[spec.period].packageId;
			}

			return (
				base.replace(/\/$/, '') +
				'/' +
				encodeURIComponent(key) +
				'/checkout?package_id=' +
				encodeURIComponent(packageId)
			);
		}

		function reset() {
			form.hidden = false;
			confirmWrap.hidden = true;
			setError(modal, '');
			setBusy(verifyButton, false);
			setBusy(payButton, false);
			verifyButton.disabled = !GUID_RE.test(keyInput.value.trim());
		}

		keyInput.addEventListener('input', function () {
			verifyButton.disabled = !GUID_RE.test(keyInput.value.trim());
			setError(modal, '');
		});

		$$('[data-st-buy]').forEach(function (trigger) {
			trigger.addEventListener('click', function () {
				var kind = trigger.getAttribute('data-st-buy');
				var period = trigger.getAttribute('data-st-buy-period') || CFG.defaultPeriod;

				target = kind === 'noads' ? { kind: 'noads' } : { kind: 'premium', period: period };

				var label = planLabel(target);
				if (planNameEl) {
					planNameEl.textContent = 'Get ' + label.name;
				}
				if (planPriceEl) {
					planPriceEl.textContent = label.price;
				}

				reset();
				showModal(modal);
			});
		});

		form.addEventListener('submit', function (event) {
			event.preventDefault();

			var key = keyInput.value.trim().toLowerCase();
			if (!GUID_RE.test(key)) {
				return;
			}

			setError(modal, '');
			setBusy(verifyButton, true);

			var url = CFG.useProxy
				? apiUrl('/verify-key', '') + '?userId=' + encodeURIComponent(key)
				: (CFG.apiBase || '').replace(/\/$/, '') + '/api/billing/verify-key?userId=' + encodeURIComponent(key);

			request(url)
				.then(function (data) {
					if (!data || !data.exists) {
						setError(modal, (CFG.i18n && CFG.i18n.keyNotFound) || 'Account key not found.');
						return;
					}

					form.hidden = true;
					confirmWrap.hidden = false;
					setError(modal, '');

					var label = planLabel(target);
					if (confirmText) {
						confirmText.textContent =
							'Buying ' + label.name + ' for account ' + (data.maskedEmail || key) + '.';
					}
				})
				['catch'](function (error) {
					var message =
						(error && error.data && error.data.message) ||
						(CFG.i18n && CFG.i18n.verifyFailed) ||
						'Could not verify your key. Please try again.';
					setError(modal, message);
				})
				.then(function () {
					setBusy(verifyButton, false);
				});
		});

		payButton.addEventListener('click', function () {
			if (!target) {
				return;
			}
			setBusy(payButton, true);
			window.location.href = checkoutUrl(target, keyInput.value.trim().toLowerCase());
		});

		resetButton.addEventListener('click', reset);
	}

	// ── Smooth anchors, offset for the sticky nav ──────────────────────

	function initAnchors() {
		document.addEventListener('click', function (event) {
			var link = event.target.closest('a[href^="#"]');
			if (!link) {
				return;
			}

			var id = link.getAttribute('href');
			if (!id || id === '#' || id.length < 2) {
				return;
			}

			var targetEl = document.getElementById(id.slice(1));
			if (!targetEl) {
				return;
			}

			event.preventDefault();
			var top = targetEl.getBoundingClientRect().top + window.scrollY - 70;
			window.scrollTo({ top: top, behavior: 'smooth' });
			history.replaceState(null, '', id);
		});
	}

	// ── Boot ───────────────────────────────────────────────────────────

	function boot() {
		initBlobs();
		initFaq();
		initCarousel();
		initDrawer();
		initBackToTop();
		initReveal();
		initModalDismissal();
		initReviewModal();
		initPricingToggle();
		initPurchaseModal();
		initAnchors();
		bindDownloadTracking();
		trackView();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
})();
