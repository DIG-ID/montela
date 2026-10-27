# Changelog

All notable changes to this theme are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)
and this project adheres to [Semantic Versioning](https://semver.org/) (`MAJOR.MINOR.PATCH`).

- **MAJOR** (`1.0.0` → `2.0.0`) — structural or breaking changes (redesign, feature removal, changes requiring manual intervention).
- **MINOR** (`1.0.0` → `1.1.0`) — new features / implementations (new template, new CPT, new section).
- **PATCH** (`1.0.0` → `1.0.1`) — bug fixes and small adjustments that do not add functionality.

---

## [1.10.0] — 2026-10-27

### Added
- Header on desktop, tablet and mobile: logo, main menu (desktop), "Book now" button, language switcher and menu button. It sits over the hero and stays in place below the admin bar.
- WPML language switcher: the current language in a box that opens the other languages one after another.
- Mega menu, opened by the menu button: a full-screen panel with a photo and the "Mega Menu" links. Items with sub-pages open their own view with a back button. Animated opening and closing, the menu icon turning into a close icon, and the page behind locked while it is open. Built for desktop, tablet and mobile.
- Footer on desktop and tablet: call-to-action box, logo, newsletter (Mailchimp for WP), contact details, socials, footer navigation, partner logos, copyright and legal links.
- "Theme Settings" options page with the header and footer content, and a new "Legal Menu" location.

### Changed
- The "Theme Settings" options page and its fields are defined in `acf-json/`: after deploying, run **Sync** in ACF → Options Pages and ACF → Field Groups.
- The theme logo (Montela, cream) is used when no custom logo is set.

## [1.9.0] — 2026-10-27

### Added
- Activity single page template, starting with the hero and intro modules, and an "Activity" field group (one tab per module).
- Hero: a desktop position for each title line (left, indented, centre or right), set in the editor.

### Changed
- Hero: the standard layout is now the inner-page one (900px tall on desktop, all lines the same size, last line in italic). The homepage keeps its full-screen hero as an exception.

## [1.8.0] — 2026-10-27

### Added
- "Activities" custom post type (`/aktivitaeten/…`), registered through ACF: title, card image, excerpt and revisions; content built from ACF modules.
- "Seasons" taxonomy for activities (Sommer / Winter), used to filter them.

## [1.7.0] — 2026-10-27

### Added
- Image band module: a full-width photo between sections, cropped per screen size.
- "Module — Image band" field group for cloning, and an Image band tab on the homepage.

## [1.6.0] — 2026-10-27

### Added
- Explore module: sage text panel (title, small photo, text, button) beside a photo slider; slider on top on tablet and mobile.
- "Module — Explore" field group for cloning, and an Explore tab on the homepage.
- Sage slider arrows for use over photos.

## [1.5.0] — 2026-10-27

### Added
- Homepage "Find your apartment" section: title, text, button and two photos.
- Homepage "Direct booking" section: benefits list with icons on a sage background.
- Homepage "Premium Hotel Stay" section: photos, services list with icons and a full-width photo slider with arrows.
- Reusable icon list component (icon, bold title, text) shared by the homepage sections.
- Homepage field group tabs for the three new sections.

### Changed
- Slider arrows can sit outside the slider element (e.g. below it on mobile).
- The Swiper slider is now loaded on the homepage.

## [1.4.0] — 2026-10-27

### Added
- Hero module: full-screen background image with an optional looping video and a three-line page title.
- Intro module: centred title and lead text over the mountains line drawing.
- ACF field groups as Local JSON (`acf-json/`): "Module — Hero" and "Module — Intro" for cloning, and "Page — Home" with one tab per section. All groups use WPML Expert mode (images/video copied, text translated).

### Changed
- The homepage template is now built from the hero and intro modules.

### Removed
- Starter example hero section on the homepage.

## [1.3.0] — 2026-10-27

### Added
- Layout grid: `.theme-container` (1820px max content width, 32/64/50px side margins) and `.theme-grid` (2/6/12 columns, 20px gutter).
- Buttons from the design system: `.btn-primary`, `.btn-secondary` and `.btn-outline` text buttons with arrow, and `.btn-icon` square buttons (arrow right, arrow down, home), with hover and keyboard focus states.
- Reusable button components: `template-parts/components/button.php` (takes an ACF Link field) and `button-icon.php`.
- "Design System" page template showing the theme's colours, typography classes and buttons.

### Changed
- SVG icons are served as separate cached files instead of being inlined into the main stylesheet.

### Removed
- Unused Google Fonts (Inter) default URL.

## [1.2.0] — 2026-10-27

### Added
- Typography classes from the design system: `.title-h1`–`.title-h4`, `.title-intro`, `.body-intro`, `.body-quote`, `.body-text` and `.body-small`, with mobile, tablet and desktop sizes.

### Changed
- Theme fonts set to FreightBig Pro (headings) and Roboto (body) via Adobe Fonts.
- Default body text now follows the design system's text style.

## [1.1.0] — 2026-10-26

### Changed
- JavaScript split into a small critical bundle (`main.js`) and separate, non-critical bundles, so WP Rocket can delay everything except `main.js`.
- GSAP, ScrollTrigger and Lenis moved to their own `animations.js` bundle.
- Swiper, Fancybox and Isotope are now optional modules, loaded (with their CSS) only on pages that use them via `montela_enqueue_module()`.
- Swiper and Fancybox CSS removed from the main stylesheet.
- The theme no longer loads jQuery for its main script.

### Fixed
- Google Maps init script now declares its jQuery dependency.

## [1.0.0] — 2026-10-26

### Added
- Project bootstrapped from dig.id Theme Starter Vite v2.0.2.
- Montela colour palette (cream, sage, mustard, walnut) in Tailwind and CSS custom properties.
- Navigation menus: Main Menu, Mega Menu, Footer Menu.
- Coding conventions for Claude Code in `.claude/rules/` (PHP, SASS, JS, editor content, Figma grid), taken from Theme Starter Vite v2.3.0.
