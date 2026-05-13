document.addEventListener('DOMContentLoaded', () => {
	const nav = document.getElementById('siteNav');
	const navLinks = document.getElementById('navLinks');
	const navToggle = document.getElementById('navToggle');
	const heroImg = document.getElementById('heroImg');
	const newsletterForm = document.getElementById('newsletterForm');
	const anchorLinks = document.querySelectorAll('a[href^="#"]');
	const revealElements = document.querySelectorAll('.reveal');

	if (nav) {
		const syncNavState = () => {
			nav.classList.toggle('scrolled', window.scrollY > 60);
		};

		syncNavState();
		window.addEventListener('scroll', syncNavState, { passive: true });
	}

	if (navToggle && navLinks) {
		navToggle.addEventListener('click', () => {
			const isOpen = navLinks.classList.toggle('open');
			navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		});

		navLinks.querySelectorAll('a').forEach((link) => {
			link.addEventListener('click', () => {
				navLinks.classList.remove('open');
				navToggle.setAttribute('aria-expanded', 'false');
			});
		});
	}

	if (heroImg) {
		window.addEventListener(
			'scroll',
			() => {
				const scrolled = window.scrollY;

				if (scrolled < window.innerHeight) {
					heroImg.style.transform = `translateY(${scrolled * 0.25}px)`;
				}
			},
			{ passive: true }
		);
	}

	if (revealElements.length && 'IntersectionObserver' in window) {
		const revealObserver = new IntersectionObserver(
			(entries) => {
				entries.forEach((entry) => {
					if (entry.isIntersecting) {
						entry.target.classList.add('visible');
						revealObserver.unobserve(entry.target);
					}
				});
			},
			{ threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
		);

		revealElements.forEach((element) => revealObserver.observe(element));
	} else {
		revealElements.forEach((element) => element.classList.add('visible'));
	}

	if (newsletterForm) {
		newsletterForm.addEventListener('submit', (event) => {
			event.preventDefault();

			const button = newsletterForm.querySelector('button');

			if (!button) {
				return;
			}

			button.textContent = 'Subscribed';
			button.classList.add('is-success');

			window.setTimeout(() => {
				button.textContent = 'Subscribe';
				button.classList.remove('is-success');
				newsletterForm.reset();
			}, 3000);
		});
	}

	anchorLinks.forEach((anchor) => {
		anchor.addEventListener('click', (event) => {
			const targetSelector = anchor.getAttribute('href');

			if (!targetSelector || targetSelector === '#') {
				return;
			}

			const target = document.querySelector(targetSelector);

			if (!target) {
				return;
			}

			event.preventDefault();
			target.scrollIntoView({ behavior: 'smooth', block: 'start' });
		});
	});
});
